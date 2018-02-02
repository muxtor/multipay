<?php namespace frontend\controllers;

use frontend\components\FrontendController;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\TariffPlan;
use common\models\TariffPlanRules;
use common\models\Payments;
use common\models\Invoice;
use common\models\User;
use yii\data\ActiveDataProvider;
use common\models\MoneyBallance;

use Yii;

class UserController extends FrontendController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'verbs' => ['POST', 'GET']
                    ],
                    [
                        'actions' => ['bonus-plans'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                        'verbs' => ['POST', 'GET']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    // personal account cabinet
    public function actionIndex()
    {
        $user = $this->loadModel(\Yii::$app->user->identity->id);
        if ($user->load(\Yii::$app->request->post())) {
//            $user->setScenario('profile');
            if ($user->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Изменения успешно сохранены!');
                return $this->redirect('index');
            }
        }
        return $this->render('index', ['user' => $user]);
    }
    
    //Profile settings Personal
    public function actionSettingsPersonal()
    {
        /**
         * @var $user User
         */
        $user = $this->loadModel(\Yii::$app->user->identity->id);
        
        if ($user->load(\Yii::$app->request->post())) {
            $profil = FALSE;    
//            \yii\helpers\VarDumper::dump(\Yii::$app->request->post(), 10, 1);
//            \yii\helpers\VarDumper::dump($user, 10, 1);
//            die();
            
            if ((empty($user->oldAttributes['email']) || empty($user->oldAttributes['firstName']) || empty($user->oldAttributes['lastName']) || empty($user->oldAttributes['date_bird'])) 
                    && (!empty($user->email) && !empty($user->firstName) && !empty($user->lastName) && !empty($user->date_bird))) {
                $profil = true;
            }
            if (empty($user->oldAttributes['email']) && !empty($user->email)) {
                $user->notice_safety_isEmail = 1;
                $user->notice_plannedPayments_isEmail = 1;
                $user->notice_latePayments_isEmail = 1;
                $user->notice_news_isEmail = 1;
                $user->notice_balannce_isEmail = 1;
            } elseif (!empty($user->oldAttributes['email']) && empty($user->email)) {
                $user->notice_safety_isEmail = 0;
                $user->notice_plannedPayments_isEmail = 0;
                $user->notice_latePayments_isEmail = 0;
                $user->notice_news_isEmail = 0;
                $user->notice_balannce_isEmail = 0;
            }
            //пользователь впервые включил уведомление об изменении баланса
            if (!$user->notice_balannce_isPhone_activationDate) {
                $on_site = (strtotime(date('Y-m-d H:i:s')) - $user->created_at)/(60*60*24);
                if ($on_site > (int)Yii::$app->settings->get('sms_notif_balance_free_period', 'currency')) {//пользователь на сайте дольше бесплатного времени подписки
                    $daysInMonth = (int) date('t');
                    $serviceCost = (int) Yii::$app->settings->get('sms_change_balance', 'currency');
                    $currentDay = (int) date('j');
                    $priceTillMonthEnd = ceil(($serviceCost/$daysInMonth) * ($daysInMonth - $currentDay));//стоимость подписки до конца месяца
                    if ($user->ballance->money_amount < $priceTillMonthEnd) {//нет денег до конца месяца
                        //на сколько дней есть денег на балансе
                        $daysEnough = floor($user->ballance->money_amount/((int) Yii::$app->settings->get('sms_change_balance', 'currency')/$daysInMonth));
                        if ($daysEnough > 0) {
                            $today = new \DateTime(date('Y-m-d'));
                            $dateEnd = $today->add(new \DateInterval('P'.$daysEnough.'D'));
                            $user->notice_balannce_isPhone_activationDateEnd = $dateEnd->format('Y-m-d') . ' 23:59:59';
                            //снимаем деньги с баланса
                            $user->ballance->money_amount -= ceil(($serviceCost/$daysInMonth) * $daysEnough);
                            $user->ballance->save(false);
                        }
                    } else {
                        $user->notice_balannce_isPhone_activationDateEnd = date('Y-m-t') . ' 23:59:59';
                        $user->ballance->money_amount -= $priceTillMonthEnd;
                        $user->ballance->save(false);
                    }
                }
                $user->notice_balannce_isPhone_activationDate = new Expression('NOW()');//записали дату активации услуги
            } else {//пользователь не в первый раз включает подписку
                $currentDateEnd = new \DateTime($user->notice_balannce_isPhone_activationDateEnd);
                //месяц окончания подписки совпадает с текущим или меньше текущего
                if ((int)$currentDateEnd->format('mm') <= (int) date('m')) {
                    if (strtotime($user->notice_balannce_isPhone_activationDateEnd < strtotime(date('Y-m-t') . ' 23:59:59'))) {//до конца месяца не проплачено
                        //проверяем на сколько хватает баланса на счету, снимаем деньги и продлеваем подписку
                        $daysInMonth = (int) date('t');
                        $serviceCost = (int) Yii::$app->settings->get('sms_change_balance', 'currency');
                        $currentDay = (int) date('j');
                        $priceTillMonthEnd = ceil(($serviceCost/$daysInMonth) * ($daysInMonth - $currentDay));//стоимость подписки до конца месяца
                        if ($user->ballance->money_amount < $priceTillMonthEnd) {//нет денег до конца месяца
                            //на сколько дней есть денег на балансе
                            $daysEnough = floor($user->ballance->money_amount/((int) Yii::$app->settings->get('sms_change_balance', 'currency')/$daysInMonth));
                            if ($daysEnough > 0) {
                                $today = new \DateTime(date('Y-m-d'));
                                $dateEnd = $today->add(new \DateInterval('P'.$daysEnough.'D'));
                                $user->notice_balannce_isPhone_activationDateEnd = $dateEnd->format('Y-m-d') . ' 23:59:59';
                                //снимаем деньги с баланса
                                $user->ballance->money_amount -= ceil(($serviceCost/$daysInMonth) * $daysEnough);
                                $user->ballance->save(false);
                            }
                        } else {
                            $user->notice_balannce_isPhone_activationDateEnd = date('Y-m-t') . ' 23:59:59';
                            $user->ballance->money_amount -= $priceTillMonthEnd;
                            $user->ballance->save(false);
                        }
                    }
                }
            }

            if ($user->save()) {
                if ($profil) {
                    \common\components\Helper::setBonus($user->id, TariffPlanRules::TYPE_PROFIL);
                }
                
                \Yii::$app->getSession()->setFlash('success', 'Изменения успешно сохранены');
                return $this->redirect('settings-personal');
            }
        }
        return $this->render('settings-personal', [
            'user' => $user
        ]);
    }
    //Profile settings Safety
    public function actionSettingsSafety()
    {
        $user = $this->loadModel(\Yii::$app->user->identity->id);
        $ulstats = \common\models\UserLoginStats::find()->where(['uls_user_id'=>\Yii::$app->user->identity->id])->orderBy('uls_date_visit DESC')->limit(5)->all();
//        \yii\helpers\VarDumper::dump($ulstats, 10, 1);
//        die();
        if ($user->load(\Yii::$app->request->post())) {
//            $user->setScenario('settings');
            if ($user->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Изменения успешно сохранены');
                return $this->redirect('settings-safety');
            }
        }
        return $this->render('settings-safety', [
            'user' => $user,
            'ulstats' => $ulstats
        ]);
    }
    //Profile settings Access Management
    public function actionSettingsAccessManagement()
    {
        $user = $this->loadModel(\Yii::$app->user->identity->id);
        $user->setScenario('access');
        if ($user->load(\Yii::$app->request->post()) && $user->validate()) {
            $user->setPassword($user->new_password);
            $user->save(false);
            \Yii::$app->getSession()->setFlash('success', 'Изменения успешно сохранены');
            
            $session = Yii::$app->session;
            if (!$session->has('notice_safety_change_pass')) {
                $session->set('notice_safety_change_pass', TRUE);
                $message = Yii::t('user/notice_safety', 'Пароль к Вашему аккаунту на MultiPay был изменен!');
                Yii::$app->mainsms->api->sendSMS($user->phone, $message, [], $user->user_language);
                if ($user->notice_safety_isEmail && $user->email) {
                    Yii::$app->mailer->compose()
                        ->setTo($user->email)
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                        ->setSubject(Yii::t('user/notice_safety', 'Уведомление безопасности от MultiPay!'))
                        ->setTextBody($message)
                        ->send();
                }
            }
            
            
            return $this->redirect('settings-personal');
        }
        return $this->render('settings-access-management', [
            'user' => $user
        ]);
    }
    
    public function actionBonus()
    {
        $user = Yii::$app->user->identity;
        $settings = Yii::$app->settings;
        
        $params = \Yii::$app->request->post();
        
        $query = \common\models\BonusHistory::find()->where(['bh_user_id' => \Yii::$app->user->identity->id]);
        $query->orderBy('bh_create DESC');
        
        if (!empty($params['date_start'])) {
            $query->andWhere('bh_create>=:date', [':date' => $params['date_start']]);
        }
        if (!empty($params['date_end'])) {
            $query->andWhere('bh_create<=:date', [':date' => $params['date_end']]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => array('pageSize' => 16),
        ]);
        
        return $this->render('bonus', [
            'user' => $user,
            'settings' => $settings,
            'history' => $dataProvider->models
        ]);
    }    
    
    public function actionBonusTransfer()
    {
        $user = Yii::$app->user->identity;
        $settings = Yii::$app->settings;
        $model = new \frontend\models\BonusTransferForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->ballance->money_amount += round($model->bonus/$settings->get('AZN_to_balance', 'currency'),2);
            $user->ballance->money_bonus_ballance -= $model->bonus;
            $user->ballance->save(false);

            //show in history
            $user_from = User::find()->where(['id'=>Yii::$app->user->id])->one();
            $payments = new Payments();
            $payments->pay_user_id = $user_from->id;
            $payments->pay_pc_provider_id = 'MultiPay';
            $payments->pay_pc_provider_account = Yii::$app->user->identity->phone;

            $payments->pay_summ = round($model->bonus/$settings->get('AZN_to_balance', 'currency'),2);
            $payments->pay_summ_from = round($user->ballance->money_amount,2);
            $payments->pay_comment = 'Бонус';

            $payments->pay_currency = Payments::CURRENCY_AZN; //деволтная валюта системы
            $payments->pay_type = Payments::PAY_TYPE_BALANCE; //тип платежа - счет на оплату
            $payments->pay_system = Payments::PAY_SYSTEM_WALLET; //с кошелька

            $payments->pay_status = Payments::PAY_PAYED;
            $payments->pay_is_payed = Payments::PAY_PAYED;
            //$payments->pay_smsCode = \common\components\Helper::generateSmsCode();
            if ($payments->save()) {
                //Бонус успешного добавлено в истории
            }
            return $this->redirect('bonus');
        }
        
        return $this->render('bonus-transfer', [
            'model' => $model,
            'settings' => $settings,
        ]);
    }    
    
    public function actionTransfer()
    {
        $user = Yii::$app->user->identity;
        
        return $this->render('transfer', [
            'user' => $user,
        ]);
    }    
    
    public function actionReclame()
    {
        $user = Yii::$app->user->identity;
        
        return $this->render('reclame', [
            'user' => $user,
        ]);
    }    
    
    public function actionBonusPlans($tp_id = false)
    {
        $user = Yii::$app->user->identity;
        
        $model = TariffPlan::find()->with('translations')->all();
        
        return $this->render('bonus-plans', [
            'model' => $model,
            'user' => $user,
            'tp_id' => $tp_id,
        ]);
    }    
    
        
    public function actionGetReferralLink()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $user = Yii::$app->user->identity;
            $result = [
                'success' => TRUE,
                'link' => $user->referralLink
            ];
            return $result;
        }
    }
    
    public function actionTransferAdd()
    {
        $transfer = new \frontend\models\TransferForm();
        $transfer->protected_code = $transfer->generateSmsCode();
        
        if ($transfer->load(Yii::$app->request->post()) && $transfer->validate() ) {
            $payments = $this->savePay($transfer);
//            $payments = $this->saveTransfer($transfer);
            if ($payments) {
                return $this->redirect(['transfer-add-confirm', 'id' => $payments->pay_id]);
//                return $this->redirect(['transfer-success', 'id' => $payments->pay_id]);
            }
            return $this->redirect('/payments/payment-error');
        }
        
        return $this->render('transfer-add', [
            'transfer' => $transfer,
        ]);
    } 
    
    public function actionTransferAddConfirm($id)
    {
        $payments = $this->loadTransfer($id);
        $post = \Yii::$app->request->post();
        if ($payments->pay_user_id != Yii::$app->user->id) {
            \Yii::$app->session->setFlash('warning', Yii::t('common.models.user', 'Вы не являетесь отправителем этого перевода!'));
            return $this->redirect(['/']);
        }
            
        if (isset($post['pay_smsCode'])) {
            if ($payments->pay_smsCode != $post['pay_smsCode'] && isset($post['agree'])) {
                \Yii::$app->session->setFlash('warning', 'Incorrect Sms Code!');
            } elseif ($this->saveTransfer($payments)) {
                return $this->redirect(['transfer-success', 'id' => $payments->pay_id]);
            }
        }
        
        
        return $this->render('transfer-add-confirm', ['payments' => $payments]);
    }
    
    //Подтверждение защищенного перевода получателем
    public function actionTransferConfirm($id)
    {
        $payments = $this->loadTransfer($id);
        
        if ($payments->pay_pc_provider_account != Yii::$app->user->identity->phone) {
            \Yii::$app->session->setFlash('warning', Yii::t('common.models.user', 'Вы не являетесь получателем этого перевода!'));
            return $this->redirect(['/']);
        }
        $code = Yii::$app->request->post('code');
        if ($payments->pay_status == Payments::PAY_STATUS_NEW && $payments->pay_is_payed == Payments::PAY_NOT_PAYED && !empty($code)) {
            if ($code == $payments->pay_protected_code) {
                $user_to = User::findByPhone($payments->pay_pc_provider_account);
                $money_to = MoneyBallance::find()->where(['money_user_id' => $user_to->id])->one();
                $money_to->money_amount += $payments->pay_summ;
                $money_to->save(false);
                $payments->pay_status = Payments::PAY_STATUS_DONE;
                $payments->pay_is_payed = Payments::PAY_PAYED;
                $payments->pay_result = Payments::PAY_RESULT_SUCCESS;
                $payments->save(false);
               \Yii::$app->session->setFlash('success', Yii::t('common.models.user', 'Перевод подтвержден'));
            } else {
                \Yii::$app->session->setFlash('warning', Yii::t('common.models.user', 'Неверный код протекции'));
            }
        }
        
        return $this->render('transfer-confirm', [
            'payments' => $payments,
        ]);
    }
    
    public function actionTransferSuccess($id)
    {
        $payments = $this->loadTransfer($id);
        if ($payments->pay_user_id != Yii::$app->user->id) {
            \Yii::$app->session->setFlash('warning', Yii::t('common.models.user', 'Вы не являетесь отправителем этого перевода!'));
            return $this->redirect(['/']);
        }
        return $this->render('transfer-success', [
            'payments' => $payments,
        ]);
    }
    
//    public function actionTransferError($id)
//    {
//        
//        return $this->render('transfer-error', [
//            'payments' => $payments,
//        ]);
//    }
    
    public function actionTransferRequest()
    {
        $transfer = new \frontend\models\TransferForm();
        $transfer->setScenario('request');
         if ($transfer->load(Yii::$app->request->post()) && $transfer->validate() ) {
            $user_from = User::findByPhone($transfer->phone);
            $payments = new Payments();
            $payments->pay_user_id = $user_from->id;
            $payments->pay_pc_provider_id = 'MultiPay';
            $payments->pay_pc_provider_account = Yii::$app->user->identity->phone;

            $payments->pay_summ = round($transfer->sum_to,2);
            $payments->pay_summ_from = round($transfer->sum_from,2);
            $payments->pay_comment = $transfer->comment;

            $payments->pay_currency = Payments::CURRENCY_AZN; //деволтная валюта системы
            $payments->pay_type = Payments::PAY_TYPE_INVOICE; //тип платежа - счет на оплату
            $payments->pay_system = Payments::PAY_SYSTEM_WALLET; //с кошелька

            $payments->pay_status = Payments::PAY_STATUS_NEW;
            $payments->pay_is_payed = Payments::PAY_NOT_PAYED;
            $payments->pay_smsCode = \common\components\Helper::generateSmsCode();
            if ($payments->save()) {
                //сохраняем счет
                $invoice = new Invoice();
                $invoice->payment_id = $payments->pay_id;
                $invoice->from_user_id = Yii::$app->user->identity->getId();
                $invoice->to_user_id = $payments->pay_user_id;
                $invoice->status = Invoice::STATUS_WAIT;
                $invoice->comment = $payments->pay_comment;
                $invoice->save();

                \Yii::$app->session->setFlash('success', Yii::t('common.models.user', 'Ваш запрос отправлен'));
                return $this->redirect('/');
            } else {
                \Yii::$app->session->setFlash('warning', Yii::t('common.models.user', 'Возникла ошибка при формировании запроса.'));
            }
        }
        return $this->render('transfer-request', [
            'transfer' => $transfer,
        ]);
    } 
    
    //Подтверждение запроса на перевод
    public function actionTransferRequestConfirm($id)
    {
        $payments = $this->loadTransfer($id);
        $user_from = User::findOne(\Yii::$app->user->id);
        
        if ($payments->pay_user_id != $user_from->id) {
            \Yii::$app->session->setFlash('warning', Yii::t('common.models.user', 'Вы не являетесь получателем этого запроса!'));
            return $this->redirect(['/']);
        }
//        $confirm = Yii::$app->request->post('confirm');
        $post = \Yii::$app->request->post();
        if ($user_from->ballance->money_amount >= $payments->pay_summ_from && $payments->pay_status == Payments::PAY_STATUS_NEW 
                && $payments->pay_is_payed == Payments::PAY_NOT_PAYED) {
            $session = Yii::$app->session;
            if (!$session->has('TransferRequestConfirm')) {
                Yii::$app->mainsms->api->sendSMS(Yii::$app->user->identity->phone, 'Code: '.$payments->pay_smsCode);
                $session->set('TransferRequestConfirm', TRUE);
                $session->set('sms_code_time', time());
            }

            if (isset($post['pay_smsCode'])) {
                if ($payments->pay_smsCode != $post['pay_smsCode'] && isset($post['agree'])) {
                    \Yii::$app->session->setFlash('warning', 'Incorrect Sms Code!');
                } elseif ($this->saveTransfer($payments)) {
                    \Yii::$app->session->setFlash('success', Yii::t('common.models.user', 'Перевод подтвержден'));
                }
            }

        } else {
            \Yii::$app->session->setFlash('warning', Yii::t('common.models.user', 'На Вашем счету недостаточно средств!'));
        }
        
        return $this->render('transfer-request-confirm', [
            'payments' => $payments,
        ]);
    }
    
    // return Active record model of USERS
    public function loadModel($id)
    {
        return User::findOne($id);
    }
    
    public function loadTransfer($id)
    {
        if (($model = Payments::find()->where([
                        'pay_id' => $id,
                        'pay_type' => Payments::PAY_TYPE_TRANSFER,
                        'pay_system' => Payments::PAY_SYSTEM_WALLET,
                    ])->one()) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    private function saveTransfer($payments)
    {
        $user_from = User::findOne(\Yii::$app->user->id);
        $user_to = User::findByPhone($payments->pay_pc_provider_account);
        
        $money_from = \common\models\MoneyBallance::find()->where(['money_user_id' => $user_from->id])->one();
        $money_from->money_amount -= $payments->pay_summ_from;

        if (!$money_from->save()){//если не списали - отмена перевода
            $payments->pay_status = Payments::PAY_STATUS_ERROR;
            $payments->pay_result_desc = Yii::t('SignupForm', 'Ошибка при списании средств. Отмена перевода.');
            $payments->save(false);
            return FALSE;
        }

        if (!$payments->pay_isProtected) {//перевод без протекции - завершаем тут операцию
            $money_to = \common\models\MoneyBallance::find()->where(['money_user_id' => $user_to->id])->one();
            $money_to->money_amount += $payments->pay_summ;

            if (!$money_to->save()){//если не зачислили - отмена перевода
                $payments->pay_status = Payments::PAY_STATUS_ERROR;
                $payments->pay_result_desc = Yii::t('SignupForm', 'Ошибка при зачислении средств адресату. Отмена перевода.');
                $payments->save(false);
                //возврат средств
                $money_from->money_amount += $payments->pay_summ_from;
                $money_from->save(false);
                return FALSE;
            }
            $payments->pay_status = Payments::PAY_STATUS_DONE;
            $payments->pay_is_payed = Payments::PAY_PAYED;
            $payments->pay_result = Payments::PAY_RESULT_SUCCESS;
            $payments->pay_type = Payments::PAY_TYPE_TRANSFER;//если тип платежа был "счет" меняем на "перевод"
            $payments->save(false);
        }

        return $payments;
    }
    
    private function savePay($transfer)
    {
        $user_from = User::findOne(\Yii::$app->user->id);
        $user_to = User::findByPhone($transfer->phone);
        
        $payments = new Payments();
        $payments->pay_user_id = $user_from->id;
        $payments->pay_pc_provider_id = 'MultiPay';
        $payments->pay_pc_provider_account = $user_to->phone;
        
        $payments->pay_summ = round($transfer->sum_to,2);
        $payments->pay_summ_from = round($transfer->sum_from,2);
        $payments->pay_comment = $transfer->comment;
        $payments->pay_isProtected = $transfer->isProtected;
        $payments->pay_protected_code = $transfer->protected_code;
        
        $payments->pay_currency = 'AZN'; //деволтная валюта системы
        $payments->pay_type = Payments::PAY_TYPE_TRANSFER; //тип платежа - перевод средств
        
        $payments->pay_system = Payments::PAY_SYSTEM_WALLET; //с кошелька
        
        $payments->pay_status = Payments::PAY_STATUS_NEW;
        $payments->pay_is_payed = Payments::PAY_NOT_PAYED;
        
        $payments->pay_smsCode = \common\components\Helper::generateSmsCode();
        if ($payments->save(FALSE)) {
            Yii::$app->mainsms->api->sendSMS(Yii::$app->user->identity->phone, Yii::t('payments', 'Код подтверждения: ', [], Yii::$app->user->identity->user_language).$payments->pay_smsCode);
            $session = Yii::$app->session;
            $session->set('sms_code_time', time());
            return $payments;
        }
    }
    
    public function actionTest()
    {
        $user = User::findOne(\Yii::$app->user->id);
        $res = \common\components\Helper::setBonus($user->id, TariffPlanRules::TYPE_PROFIL);
        if ($res) {
            echo 'ok';
        } else {
            echo 'no';
        }
        
    }
}
