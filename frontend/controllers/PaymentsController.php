<?php

namespace frontend\controllers;

use common\models\MoneyBallance;
use frontend\components\FrontendController;
use yii\db\Expression;
use yii\filters\AccessControl;
use common\models\Payments;
use frontend\models\PaymentSearchForm;
use common\models\Providers;
use common\models\PayTemplate;
use yii\helpers\VarDumper;
use yii\data\ActiveDataProvider;
use Yii;
use yii\web\ForbiddenHttpException;
use common\components\CheckPcSend;
use common\components\PayPcSend;

class PaymentsController extends FrontendController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['history','PayFile'],
                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['?', '@']
//                    ],
                    /*[
//                        'actions' => array('check', 'pay'),
                        'allow' => true,
                        'roles' => ['?', '@'],
                        'verbs' => ['POST', 'GET']
                    ],*/

                    [
                        'actions' => ['history','PayFile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionStepOne($id, $edit = false)
    {
        $provider = Providers::findOne($id);
        if (!$provider || count($provider->children()->all()) || !$provider->pc_id) {
            \Yii::$app->session->setFlash('warning', 'You can`t pay for choosen provider!');
            return $this->redirect('payment-error');
        }
        $template = false;
        $pID = \Yii::$app->session->get('paymentID');
        \Yii::$app->session->remove('paymentID');
        if ($edit == true && !empty($pID)) {
            $model = new Payments();
            $payment = Payments::findOne(['pay_id' => $pID]);
            
            if (!empty($payment)) {
                $model->pay_pc_provider_account = $payment->pay_pc_provider_account;
                $model->pay_currency = $payment->pay_currency;
                $model->pay_summ = $payment->pay_summ;
                $model->pay_system = $payment->pay_system;
            }
        } else {
            if (!\Yii::$app->user->isGuest) {
                $template = PayTemplate::find()->where('pt_user_id = :user', [':user' => \Yii::$app->user->id])
                    ->andWhere('pt_provider_id = :provider', [':provider' => $provider->id])
                    ->orderBy('pt_id DESC')
                    ->one();
            }
        
            $model = new Payments();
            if ($template) {
                $model->pay_pc_provider_account = $template->pt_accaunt;
                $model->pay_currency = $template->pt_currency;
                $model->pay_summ = $template->pt_summ;
                $model->pay_system = $template->pt_system;
            }else{
                $model->pay_currency = $provider->currency;
            }
        }
        $model->setScenario('step_one');
        if ($model->load(\Yii::$app->request->post())) {
            if (!in_array($model->pay_currency, [Payments::CURRENCY_AZN, Payments::CURRENCY_RUB, Payments::CURRENCY_USD])) {
                \Yii::$app->session->setFlash('warning', Yii::t('payments', 'CURRENCY_NOT_AVAILABLE'));
                return $this->redirect('payment-error');
            }
            if ($model->pay_system == Payments::PAY_SYSTEM_WALLET && Yii::$app->user->isGuest) {
                throw new ForbiddenHttpException();
            }
            if (!\Yii::$app->user->isGuest) {
                $model->pay_user_id = \Yii::$app->user->id;
            }
            
            $model->pay_provider_id = $provider->id;
            $model->pay_pc_provider_id = $provider->pc_id;
            $model->pay_type = Payments::PAY_TYPE_PROVIDER;
            $model->pay_summ_from = $model->pay_summ;// + ceil(($model->pay_summ*$provider->comission_percent)/100)
            $model->pay_provider_currency_pc = $provider->currency;
            //генерим код подтверждения для зарегистрированных пользователей
            if (!Yii::$app->user->isGuest) {
                $model->pay_smsCode = \common\components\Helper::generateSmsCode();
            }
            //оплата с баланса авторизированным пользователем
            if ($model->pay_system == Payments::PAY_SYSTEM_WALLET && !Yii::$app->user->isGuest) {
                if (Yii::$app->user->identity->ballance->money_amount < $model->pay_summ_from) {
                    \Yii::$app->session->setFlash('error', Yii::t('payments', 'NOT_ENOUGH_MONEY_ON_BALANCE'));
                    return $this->redirect('payment-error');
                }
            }
            
            if ($model->validate()) {
                if ($model->save()) {
                    if (!Yii::$app->user->isGuest AND $model->pay_provider_id!=28) {
                        Yii::$app->mainsms->api->sendSMS(Yii::$app->user->identity->phone, Yii::t('payments', 'Код подтверждения: ', [], Yii::$app->user->identity->user_language).$model->pay_smsCode);
                        $session = Yii::$app->session;
                        $session->set('sms_code_time', time());
                    }
                    return $this->redirect(['check', 'id' => $model->pay_id]);
                } else {
                    \Yii::$app->session->setFlash('warning', 'Service temporary unavailable!');
                    return $this->redirect('payment-error');
                }
            }
                
        }    
        return $this->render('step_one', ['provider' => $provider, 'model' => $model]);
        
    }
    
    public function actionCheck($id)
    {
        /** @var $pay Payments */
        $pay = Payments::findOne($id);
        if (!$pay) {
            \Yii::$app->session->setFlash('warning', 'Incorrect model data!');
            return $this->redirect('payment-error');
        }
        
        $result = CheckPcSend::checkPay($pay->pay_id, $pay->pay_pc_provider_id, $pay->pay_pc_provider_account, $pay->pay_summ_from, $pay->pay_currency);
        if (!$result) {
            \Yii::$app->session->setFlash('warning', 'No response from server!');
            return $this->redirect('payment-error');
        }
        $response = new \SimpleXMLElement($result);
        if ((string)$response['result'] !== '0') {
            $error_code = (string)$response['result'];
            $error_desc = (string)$response['result-description'];
            $pay->pay_check_result = $error_code;
            $pay->pay_check_result_desc = $error_desc;
            $pay->pay_is_checked = Payments::CHECK_CHECKED;
            if ($pay->save()) {
                \Yii::$app->session->setFlash('warning', Yii::t('error','Неверный логин/номер счета или провайдер временно не активен'));//'Error type #'.$error_code.'. '.$error_desc
            } else {
                \Yii::$app->session->setFlash('warning', 'There was error in your result. Unable to save check results');
            }
            return $this->redirect('payment-error');
        } else {
            if ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_PROGRESS) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                if ($pay->save()) {
                    return $this->redirect(['check-wait-result', 'id' => $id]);
                }
                \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                return $this->redirect('payment-error');
            } elseif ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_SUCCESS) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                if ($pay->save()) {
                    return $this->redirect(['step-two', 'id' => $id]);
                }
                \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                return $this->redirect('payment-error');
            } elseif ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_ERROR) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                if ($pay->save()) {
                    \Yii::$app->session->setFlash('warning', Yii::t('error','Неверный логин/номер счета или провайдер временно не активен'));//'Error type #'.$pay->pay_check_result.'. '.$pay->pay_check_result_desc
                    return $this->redirect('payment-error');
                }
                \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                return $this->redirect('payment-error');
            } elseif ((string)$response->{'check-payment'}->payment['result'] == (string)Payments::CHECK_RESULT_UNKNOWN_ERROR) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['result-description'];
                $pay->pay_check_status = Payments::CHECK_STATUS_ERROR;
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                if ($pay->save()) {
                    \Yii::$app->session->setFlash('warning', Yii::t('error','Неверный логин/номер счета или провайдер временно не активен'));//'Error type #'.$pay->pay_check_result.'. '.$pay->pay_check_result_desc
                    return $this->redirect('payment-error');
                }
                \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                return $this->redirect('payment-error');
            }
        }
        \Yii::$app->session->setFlash('warning', 'Unaible to process your request');
        return $this->redirect('payment-error');
        
    }
    
    public function actionCheckWaitResult($id)
    {
        return $this->render('check_wait', ['id' => $id, 'cur_date' => date("Y-m-d H:m:s")]);
    }

    public function actionChangeCheckStatus()
    {
        if (\Yii::$app->request->isAjax) {
            $json = [];
            $payment_id = \Yii::$app->request->post('id');
            $pay = Payments::findOne($payment_id);
            /* @var $pay Payments */
            if ($pay) {
                $result = CheckPcSend::checkPay($pay->pay_id, $pay->pay_pc_provider_id, $pay->pay_pc_provider_account,
                    $pay->pay_summ_from, $pay->pay_currency);
                if (!$result) {
                    \Yii::$app->session->setFlash('warning', 'No response from server!');
                    return $this->redirect('payment-error');
                }
                $response = new \SimpleXMLElement($result);
                if ((string)$response['result'] !== '0') {
                    $error_code = (string)$response['result'];
                    $error_desc = (string)$response['result-description'];
                    $pay->pay_check_result = $error_code;
                    $pay->pay_check_result_desc = $error_desc;
                    $pay->pay_is_checked = Payments::CHECK_CHECKED;
                    if ($pay->save()) {
                        \Yii::$app->session->setFlash('warning', 'Error type #'.$error_code.'. '.$error_desc);
                    } else {
                        \Yii::$app->session->setFlash('warning', 'There was error in your result. Unable to save check results');
                    }
                    return $this->redirect('payment-error');
                } else {
                    if ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_PROGRESS) {
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        $json['success'] = 'yes';
                        return $json;
                    } elseif ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_SUCCESS) {
                        $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                        $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                        $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                        $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                        $pay->pay_is_checked = Payments::CHECK_CHECKED;
                        if ($pay->save()) {
                            return $this->redirect(['step-two', 'id' => $payment_id]);
                        }
                        \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                        return $this->redirect('payment-error');
                    } elseif ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_ERROR) {
                        $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                        $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                        $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                        $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                        $pay->pay_is_checked = Payments::CHECK_CHECKED;
                        if ($pay->save()) {
                            \Yii::$app->session->setFlash('warning', 'Error type #'.$pay->pay_check_result.'. '.$pay->pay_check_result_desc);
                            return $this->redirect('payment-error');
                        }
                        \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                        return $this->redirect('payment-error');
                    } elseif ((string)$response->{'check-payment'}->payment['result'] == (string)Payments::CHECK_RESULT_UNKNOWN_ERROR) {
                        $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                        $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['result-description'];
                        $pay->pay_check_status = Payments::CHECK_STATUS_ERROR;
                        $pay->pay_is_checked = Payments::CHECK_CHECKED;
                        if ($pay->save()) {
                            \Yii::$app->session->setFlash('warning', 'Error type #'.$pay->pay_check_result.'. '.$pay->pay_check_result_desc);
                            return $this->redirect('payment-error');
                        }
                        \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                        return $this->redirect('payment-error');
                    }
                }
            }
            \Yii::$app->session->setFlash('warning', 'Wrong model data while checking');
            return $this->redirect('payment-error');
        }
    }

    public function actionStepTwo($id)
    {
        \Yii::$app->session->set('paymentID', $id);
        
        $pay = Payments::findOne($id);
        /* @var $pay Payments */
        if (!$pay) {
            \Yii::$app->session->setFlash('warning', 'Incorrect payment model data on step two!');
            return $this->redirect('payment-error');
        }
        $provider = Providers::findOne($pay->pay_provider_id);
        if (!$provider) {
            \Yii::$app->session->setFlash('warning', 'Incorrect provider model data on step two!');
            return $this->redirect('payment-error');
        }
        return $this->render('step_two', ['payment' => $pay, 'provider' => $provider]);
    }
    
    public function actionPay()
    {
        $post = \Yii::$app->request->post();
        if (empty($post['pay_id'])) {
            \Yii::$app->session->setFlash('warning', 'Incorrect data while confirm payment!');
            return $this->goHome();
        }
        
        $pay = Payments::findOne($post['pay_id']);
        /* @var $pay Payments */
        if (!$pay) {
            \Yii::$app->session->setFlash('warning', 'Incorrect model data!');
            return $this->goHome();
        }
        
        if (isset($post['reject'])) {
            if (!empty($pay->provider->id)) {
                return $this->redirect(['step-one', 'id' => $pay->provider->id, 'edit' => true]);
            } else {
                \Yii::$app->session->setFlash('warning', 'Unable to change payments details!');
                return $this->redirect('payment-error');
            }
        }
        
        if (!empty($pay->pay_user_id) && $pay->pay_smsCode != $post['pay_smsCode'] && isset($post['agree'])) {
            \Yii::$app->session->setFlash('warning', 'Incorrect Sms Code!');
            return $this->redirect(['step-two', 'id' => $pay->pay_id]);
        }
        
        if ($pay->pay_system == Payments::PAY_SYSTEM_WMR) {
            return $this->redirect(['/webmoneypay/default/wm-pc', 'id' => $pay->pay_id]);
        }
        
        $cur_date = date('Y-m-d H:i:s');
        $result = PayPcSend::makePay($pay->pay_id, $cur_date, $pay->pay_pc_provider_id, $pay->pay_pc_provider_account,
            $pay->pay_summ_from, $pay->pay_currency);
        if (!$result) {
            \Yii::$app->session->setFlash('warning', 'No response from server on step two!');
            return $this->redirect('payment-error');
        }
        $response = new \SimpleXMLElement($result);
        if ((string)$response['result'] !== '0') {
            $error_code = (string)$response['result'];
            $error_desc = (string)$response['result-description'];
            $pay->pay_result = $error_code;
            $pay->pay_result_desc = $error_desc;
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            if ($pay->save()) {
                \Yii::$app->session->setFlash('warning', 'Error type #'.$error_code.'. '.$error_desc);
            } else {
                \Yii::$app->session->setFlash('warning', 'There was error in your result. Unable to save check results');
            }
            return $this->redirect('payment-error');
        }
        if ((string)$response->{'add-payment'}->payment['id'] != (string)$post['pay_id']) {
            \Yii::$app->session->setFlash('warning', 'Payment does not match!');
            return $this->redirect('payment-error');
        }
        if ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_NEW) {
            $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
            $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
            $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
            $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            if ($pay->save()) {
                return $this->redirect(['step-three', 'id' => $pay->pay_id]);
                //return $this->redirect(['pay-wait-result', 'id' => $post['pay_id'], 'cur_date' => $cur_date]);
            }
            \Yii::$app->session->setFlash('warning', 'Unable to save check results');
            return $this->redirect('payment-error');
        } elseif ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_DONE) {
            $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
            $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
            $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
            $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            if ($pay->save()) {
                return $this->redirect(['step-three', 'id' => $pay->pay_id]);
            }
            \Yii::$app->session->setFlash('warning', 'Unable to save check results');
            return $this->redirect('payment-error');
        } elseif ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_ERROR) {
            $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
            $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
            $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
            $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            if ($pay->save()) {
                \Yii::$app->session->setFlash('warning', 'Error type #'.$pay->pay_result.'. '.$pay->pay_result_desc);
                return $this->redirect('payment-error');
            }
            \Yii::$app->session->setFlash('warning', 'Unable to save check results');
            return $this->redirect('payment-error');
        }
        
        \Yii::$app->session->setFlash('warning', 'Unable to save pay results');
        return $this->redirect('payment-error');
    }
    
    public function actionPayWaitResult($id, $cur_date)
    {
        return $this->render('pay_wait', ['id' => $id, 'cur_date' => $cur_date]);
    }
    
    public function actionPayCheckStatus()
    {
        if (\Yii::$app->request->isAjax) {
            $json = [];
            $payment_id = \Yii::$app->request->post('id');
            $payment_date = \Yii::$app->request->post('date');
            if (empty($payment_id) || empty($payment_date)) {
                \Yii::$app->session->setFlash('warning', 'Wrong pay request data!');
                return $this->redirect('payment-error');
            }
            $pay = Payments::findOne($payment_id);
            /* @var $pay Payments */
            if ($pay) {
                $result = PayPcSend::makePay($pay->pay_id, $payment_date, $pay->pay_pc_provider_id, $pay->pay_pc_provider_account, $pay->pay_summ_from, $pay->pay_currency);
                if (!$result) {
                    \Yii::$app->session->setFlash('warning', 'No response from server!');
                    return $this->redirect('payment-error');
                }
                $response = new \SimpleXMLElement($result);

                if ((string)$response['result'] !== '0') {
                    $error_code = (string)$response['result'];
                    $error_desc = (string)$response['result-description'];
                    $pay->pay_result = $error_code;
                    $pay->pay_result_desc = $error_desc;
                    $pay->pay_payed = $payment_date;
                    $pay->pay_is_payed = Payments::PAY_PAYED;
                    if ($pay->save()) {
                        \Yii::$app->session->setFlash('warning', 'Error type #'.$error_code.'. '.$error_desc);
                    } else {
                        \Yii::$app->session->setFlash('warning', 'There was error in your result. Unable to save check results');
                    }
                    return $this->redirect('payment-error');
                }
                if ((string)$response->{'add-payment'}->payment['id'] != (string)$payment_id) {
                    \Yii::$app->session->setFlash('warning', 'Payment does not match!');
                    return $this->redirect('payment-error');
                }
                if ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_NEW) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $json['success'] = 'yes';
                    return $json;
                } elseif (in_array((string)$response->{'add-payment'}->payment['status'], [(string)  Payments::PAY_STATUS_DONE, (string)  Payments::PAY_STATUS_ERROR])) {
                    $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
                    $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
                    $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
                    $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
                    $pay->pay_payed = $payment_date;
                    $pay->pay_is_payed = Payments::PAY_PAYED;
                    if ($pay->save()) {
                        if ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_DONE) {
                            return $this->redirect(['step-three', 'id' => $pay->pay_id]);
                        } elseif ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_ERROR) {
                            \Yii::$app->session->setFlash('warning', 'Error type #'.$pay->pay_result.'. '.$pay->pay_result_desc);
                            return $this->redirect('payment-error');
                        }
                    }
                    \Yii::$app->session->setFlash('warning', 'Unable to save check results');
                    return $this->redirect('payment-error');
                }
        
                \Yii::$app->session->setFlash('warning', 'Unable to save pay results');
                return $this->redirect('payment-error');
            }//end if ($pay)
            \Yii::$app->session->setFlash('warning', 'Requested payment not found!');
            return $this->redirect('payment-error');
        }//end if ajax request
    }

    public function actionStepThree($id)
    {
        $payment = Payments::findOne($id);
        /* @var $payment Payments */
        if (!$payment) {
            throw new \yii\web\NotFoundHttpException;
        }
        $provider = Providers::findOne($payment->pay_provider_id);
        if (!$provider) {
            throw new \yii\web\NotFoundHttpException;
        }
        //если авторизированный пользователь оплатил со своего кошелька (баланса), то снимаем с него деньги
        if ($payment->pay_system == Payments::PAY_SYSTEM_WALLET && !Yii::$app->user->isGuest) {
            Yii::$app->db->createCommand()->update(MoneyBallance::tableName(), [
                    'money_amount' => new Expression('money_amount - :pay_amount', [':pay_amount' => $payment->pay_summ_from])
                ], 'money_user_id = :id', [':id' => \Yii::$app->user->id])->execute();
        }
        if (!\Yii::$app->user->isGuest) {
            $template = PayTemplate::find()->where('pt_user_id = :user', [':user' => \Yii::$app->user->id])
                ->andWhere('pt_provider_id = :provider', [':provider' => $payment->pay_provider_id])
                ->andWhere('pt_accaunt = :account', [':account' => $payment->pay_pc_provider_account])
                ->andWhere('pt_currency = :currency', [':currency' => $payment->pay_currency])
                ->andWhere('pt_summ = :summ', [':summ' => $payment->pay_summ])
                ->andWhere('pt_system = :sys', [':sys' => $payment->pay_system])
                ->one();
            if (!$template) {
                $template = new PayTemplate();
                $template->pt_user_id = \Yii::$app->user->id;
                $template->pt_provider_id = $payment->pay_provider_id;
                $template->pt_accaunt = $payment->pay_pc_provider_account;
                $template->pt_currency = $payment->pay_currency;
                $template->pt_summ = $payment->pay_summ;
                $template->pt_system = $payment->pay_system;
                $template->save();
            }
        }
        return $this->render('step_three', ['payment' => $payment, 'provider' => $provider]);
    }
    
    public function actionPaymentError()
    {
        return $this->render('payment_error');
    }
    
    public function actionPaymentSearch()
    {
        $model = new PaymentSearchForm();
        if ($model->load(\Yii::$app->request->post())) {
            if (!$model->validate()) {
                return $this->render('pay_search_form', ['model' => $model]);
            }
//            $date = implode('-',array_reverse(explode('.', explode(' ', $model->check_date)[0]))).'T'.explode(' ', $model->check_date)[1].'.000+0000';
            $date = implode('-',array_reverse(explode('-', $model->check_date)));
            $data = ["terminalId" => $model->terminal_id, "payDate" => $date, "checkNumber" => $model->check_number];
            $data_string = json_encode($data);
            $url = 'https://pays-api-2012.armax.ru/rest/agent/search_pay_by_check_start';
            $auth = 'Basic '. base64_encode('testmultipay@2915:AsW258951testmp5');
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string),
                'Authorization: '. $auth
            ]);

            if( ! $result = curl_exec($ch))
            {
                trigger_error(curl_error($ch));
            } 
            curl_close($ch);

            return $this->render('pay_search_result', ['model' => $model, 'result' => json_decode($result, true)]);
        }
        return $this->render('pay_search_form', ['model' => $model]);
        
    }
    
    public function actionHistory()
    {
//        $params = Yii::$app->request->getQueryParams();
        $params = \Yii::$app->request->post();
        $query = Payments::find()->where(['pay_user_id' => \Yii::$app->user->identity->id]);
        $query->orderBy('pay_created DESC');
        
        if (!empty($params['ID'])) {
            $query->andFilterWhere(['like', 'pay_id', $params['ID']]);
        }
        if (!empty($params['date_start'])) {
            $query->andWhere('pay_created>=:date', [':date' => $params['date_start']]);
        }
        if (!empty($params['date_end'])) {
            $query->andWhere('pay_created<=:date', [':date' => $params['date_end']]);
        }
        if (!empty($params['pay_type'])) {
            $query->andWhere(['in', 'pay_type', $params['pay_type']]);
        }
        if (!empty($params['pay_status'])) {
            $query->andWhere(['in', 'pay_status', $params['pay_status']]);
        }
//        VarDumper::dump($params, 10, 1);
//        VarDumper::dump($query, 10, 1);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => array('pageSize' => 16),
        ]);
        return $this->render('history', [
            'models' => $dataProvider->models
        ]);
    }
    
    public function actionPayFile()
    {
        $paymentsList = Payments::find()->where(['pay_user_id' => \Yii::$app->user->identity->id])->all();
        $paymentStr = Yii::t('payments/model', 'ID транзакции') . ';' 
                . Yii::t('payments/model', 'Дата') . ';' 
                . Yii::t('payments/model', 'Доход') . ';'
                . Yii::t('payments/model', 'Расход') . ';'
                . Yii::t('payments/model', 'ПС') . ';'
                . Yii::t('payments/model', 'Номер') . ';'
                . Yii::t('payments/model', 'Комментарий') . ';'
                . Yii::t('payments/model', 'Статус') . ';'
                . "\r\n";
        if (!empty($paymentsList)) {
            foreach ($paymentsList as $payment) {
                $paymentStr .= $payment->pay_id . ';' 
                        . $payment->pay_created . ';' 
                        . $payment->pay_summ . ';'
                        . $payment->pay_summ_from . ';'
                        . $payment->getProviderName() . ';'
                        . $payment->pay_pc_provider_account . ';'
                        . $payment->pay_comment . ';'
                        . $payment->payStatusLabel() . ';'
                        . "\r\n";
            }
            header('Content-Type: "application/octet-stream"');
            header('Content-Disposition: attachment; filename="Multipay_history_payments_'.\Yii::$app->user->identity->phone.'_'.  date('d-m-Y') .'.csv"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); // Для IE
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($paymentStr));
            exit($paymentStr);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('payments/model', 'Нет платежей'));
             $this->redirect('history');
        }
    }
    
    public function actionResendSms()
    {
        $session = Yii::$app->session;
            
        if (!$session->has('sms_code_time')) {
            $session->set('sms_code_time', time());
        }
        
        if (Yii::$app->request->isAjax && !Yii::$app->user->isGuest && $session->has('sms_code_time')) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = \Yii::$app->request->post();
            $result = [
                'success' => false,
                'mes' => 'ждите'
            ];

            $pay = Payments::findOne($post['id']);
            if((time()-$session->get('sms_code_time'))>60){
                $pay->pay_smsCode = \common\components\Helper::generateSmsCode();
                $pay->save(false);
                //send SMS
                Yii::$app->mainsms->api->sendSMS(Yii::$app->user->identity->phone, 'Code: '.$pay->pay_smsCode);
                $session->set('sms_code_time', time());
                $result = [
                    'success' => TRUE,
                    'mes' => Yii::t('sendSMS', 'новый код отправлен'),
                    'code' => $pay->pay_smsCode
                ];
            } else {
                $time = 60 - (time()-$session->get('sms_code_time'));
                $result = [
                    'success' => false,
                    'mes' => Yii::t('sendSMS', 'повторная отправка sms будет доступна через ').'<span id="timer">'.$time . '</span>'.Yii::t('sendSMS', ' сек.'),
                    'code' => $pay->pay_smsCode,
                    'time' => $time
                ];
            }
            return $result;
            
        }
    }
    
}
