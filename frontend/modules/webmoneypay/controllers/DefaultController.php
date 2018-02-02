<?php

namespace frontend\modules\webmoneypay\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\modules\webmoneypay\models\Paysystem;
use common\models\Payments;
use Yii;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => array('success', 'fail', 'result'),
                        'allow' => true,
                        'roles' => ['?'],
                        'verbs' => ['POST', 'GET']
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = ($action->id !== 'success');
        $this->enableCsrfValidation = ($action->id !== 'fail');
        $this->enableCsrfValidation = ($action->id !== 'result');
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionWmBalance()
    {
        $model = new Paysystem();
        $wmpurses = \common\models\WmSetting::find()->all();
        $wmpurses = \yii\helpers\ArrayHelper::map($wmpurses, 'wms_name', 'wms_rate');
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $wmpurse = \common\models\WmSetting::find()->where(['wms_name'=>$model->type])->one();
                
                $payments = new Payments();
//                $payments->setScenario('balance');
                $payments->pay_user_id = \Yii::$app->user->id;
                $payments->pay_status = Payments::PAY_STATUS_NEW;
                $payments->pay_summ = round($model->amount/$wmpurse->wms_rate,2);
                $payments->pay_summ_from = round($model->amount,2);
                $payments->pay_currency = $model->type;
                $payments->pay_type = Payments::PAY_TYPE_BALANCE;
                $payments->pay_system = $model->type;
                $payments->pay_comment = 'Пополнение баланса пользователя ' . \Yii::$app->user->identity->phone;
                $payments->pay_smsCode = \common\components\Helper::generateSmsCode();
                Yii::$app->mainsms->api->sendSMS(Yii::$app->user->identity->phone, Yii::t('payments', 'Код подтверждения: ', [], Yii::$app->user->identity->user_language).$payments->pay_smsCode);
                $session = Yii::$app->session;
                $session->set('sms_code_time', time());
//        \yii\helpers\VarDumper::dump($payments->save(),10,1);
                if ($payments->save()) {
                    return $this->redirect(['wm-balance-confirm', 'id' => $payments->pay_id]);
                    return $this->render('pay', ['model' => $payments, 'wmpurse' => $wmpurse]);
                }
            }
        }
//        \yii\helpers\VarDumper::dump($wmpurses,10,1);
//        die();
        return $this->render('wm-balance', [
                'model' => $model,
                'wmpurses' => $wmpurses
        ]);
    }
    
    public function actionWmBalanceConfirm($id)
    {
        $pay = Payments::findOne($id);
        $post = \Yii::$app->request->post();
        if (!$pay) {
            \Yii::$app->session->setFlash('warning', 'Incorrect payment model data on step two!');
            return $this->render('fail');
        }
            
        if (isset($post['pay_smsCode'])) {
            if (!empty($pay->pay_user_id) && $pay->pay_smsCode != $post['pay_smsCode'] && isset($post['agree'])) {
                \Yii::$app->session->setFlash('warning', 'Incorrect Sms Code!');
            } else {
                $wmpurse = \common\models\WmSetting::find()->where(['wms_name'=>$pay->pay_system])->one();
                return $this->render('pay', ['model' => $pay, 'wmpurse' => $wmpurse]);
            }
        }
        
        
        return $this->render('wm-balance-confirm', ['payment' => $pay]);
    }
    
    public function actionWmPc($id)
    {
        $pay = Payments::findOne($id);
        if ($pay) {
            $wmpurse = \common\models\WmSetting::find()->where(['wms_name'=>$pay->pay_system])->one();
            if ($wmpurse) {
                return $this->render('pay', ['model' => $pay, 'wmpurse' => $wmpurse]);
            }
        }
        
        return $this->render('fail');
    }
    
    public function actionPay()
    {
        return $this->render('pay');
    }
    
    public function actionSuccess()
    {
        
        $params = $_POST;
//        $params = $_GET;
        if (isset($params['LMI_PAYMENT_NO'])) {
            $payments = Payments::findOne($params['LMI_PAYMENT_NO']);
//            \yii\helpers\VarDumper::dump($params, 10,1);
//            die();
            if (!empty($payments) && $payments->pay_type == Payments::PAY_TYPE_PROVIDER) {
                return $this->redirect(['/payments/pay-wait-result', 'id' => $payments->pay_id, 'cur_date' => $payments->pay_payed]);
            }
        }
        
        return $this->render('success');
    }
    
    public function actionFail()
    {
        return $this->render('fail');
    }
    
    public function actionResult()
    {
        $params = $_POST;
        $result = false;
        if (isset($params['LMI_PAYEE_PURSE'])) {
            $wmpurse = \common\models\WmSetting::find()->where(['wms_purse'=>$params['LMI_PAYEE_PURSE']])->one();
        }
        if (isset($params['LMI_PAYMENT_NO'])) {
            $payments = Payments::findOne($params['LMI_PAYMENT_NO']);
        }
            
        if (isset($params['LMI_PREREQUEST']) && isset($wmpurse)) {
            if (!empty($payments) && $payments->pay_summ_from == $params['LMI_PAYMENT_AMOUNT']) {
                $result = 'YES';
            }
        } elseif (isset($wmpurse)) {
            if (!empty($payments) && $payments->pay_type == Payments::PAY_TYPE_BALANCE && $payments->pay_summ_from == $params['LMI_PAYMENT_AMOUNT'] && $this->successPurchase($payments, $wmpurse)) {
                $payments->pay_subpaysystem_transfer = json_encode($params);
                $payments->save(FALSE);
                $result = 'YES';
            } elseif (!empty($payments) && $payments->pay_type == Payments::PAY_TYPE_PROVIDER && $payments->pay_summ_from == $params['LMI_PAYMENT_AMOUNT'] && $this->successPurchasePC($payments)) {
                $payments->pay_subpaysystem_transfer = json_encode($params);
                $payments->save(FALSE);
                $result = 'YES';
            }
        }
        return $result;
    }
    
    public function successPurchase($payments, $wmpurse)
    {
//        $user = \common\models\User::findOne($payments->pay_user_id);
        $payments->pay_status = Payments::PAY_STATUS_DONE;
        $payments->pay_is_payed = Payments::PAY_PAYED;
        
        if ($payments->save()) {
            $money = \common\models\MoneyBallance::find()->where(['money_user_id' => $payments->pay_user_id])->one();
            if (!$money) {
                $money = new MoneyBallance();
                $money->money_user_id = $payments->pay_user_id;
            }
            $money->money_amount += ($payments->pay_summ);
            $money->money_transaction_amount += ($payments->pay_summ);
            if ($money->save()){
                return TRUE;
            } else {
                $payments->pay_status = Payments::PAY_STATUS_ERROR;
                $payments->pay_result_desc = Yii::t('SignupForm', 'Внутренняя ошибка сервиса при опработке платежа.');
                $payments->save(false);
                return FALSE;
            }
        }
        return FALSE;
//        $smstext = 'Операция по переводу средств прошла успешно';
//        \Yii::$app->sms->send_sms($user->phone, $smstext);
//        $this->sendEmail($user, $payments->pay_comment, $smstext);
    }
    
    public function successPurchasePC($pay)
    {
        $cur_date = date('Y-m-d H:i:s');
        $result = $this->makePay($pay->pay_id, $cur_date, $pay->pay_pc_provider_id, $pay->pay_pc_provider_account, $pay->pay_summ_from);
        if (!$result) {
            return FALSE;
        }
        $response = new \SimpleXMLElement($result);
        if ((string)$response['result'] !== '0') {
            $error_code = (string)$response['result'];
            $error_desc = (string)$response['result-description'];
            $pay->pay_result = $error_code;
            $pay->pay_result_desc = $error_desc;
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            $pay->save(FALSE);
            return FALSE;
        }
        $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
        $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
        $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
        $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
        $pay->pay_payed = $cur_date;
        $pay->pay_is_payed = Payments::PAY_PAYED;
        
        if ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_NEW ||
                (string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_DONE) {
            $pay->save(FALSE);
            return TRUE;
        } elseif ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_ERROR) {
            $pay->save(FALSE);
            return FALSE;
        }
        
        return FALSE;
    }
    
    
    public function makePay($payment_id, $pay_date, $pc_provider_id, $pc_provider_account, $summ)
    {
        $url = 'https://pays-api-2012.armax.ru/pays-api2012/api/v1/pays';
        $request = new \SimpleXMLElement('<request></request>');
        $auth = $request->addChild('auth');
        $auth->addAttribute('dealer', '2915');
        $auth->addAttribute('login', 'testmultipay');
        $auth->addAttribute('password', 'AsW258951testmp5');
        $auth->addAttribute('terminal', '6197');
        $add_payment = $request->addChild('add-payment');
        $payment = $add_payment->addChild('payment');
        $payment->addAttribute('id', $payment_id);
        $payment->addAttribute('date', explode(' ', $pay_date)[0].'T'.explode(' ', $pay_date)[1]);
        $from = $payment->addChild('from');
        $from->addAttribute('commission', '0.00');
        $from->addAttribute('currency', '944');
        $from->addAttribute('summ', $summ);
//        $from->addAttribute('summ', '1.00');
        $to = $payment->addChild('to');
        $to->addAttribute('account', $pc_provider_account);
        $to->addAttribute('provider', $pc_provider_id);
//        $to->addAttribute('account', 'R253182131671');
//        $to->addAttribute('provider', '237');
        $xml = $request->asXML();
        if (!$xml) {
            return FALSE;
        }
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain;charset=utf-8'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
}
