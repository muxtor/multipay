<?php

namespace console\controllers;

use common\models\PayPlanned;
use common\models\Payments;
use common\models\User;
use Yii;
use yii\console\Controller;

/**
 * Class SmsPlannedPaymentController
 * @package console\controllers
 * @var $user User
 * develop to send SMS every day
 */

class SmsPlannedPaymentController extends Controller
{

    public function actionIndex()
    {
        $pays = PayPlanned::find()
            ->where(['pp_is_notif' => 1])
            ->andWhere('TO_DAYS(pp_pay_date) - TO_DAYS(NOW())  = pay_notif_day_amount')
            ->all();
        if ($pays) {
            foreach ($pays as $pay) {
                /**
                 * @var $pay PayPlanned
                 */
                $user = $pay->getUser();
                $message = Yii::t('payments', 'У Вас запланирована оплата на ', [], $user->user_language) . Yii::$app->formatter->asDate('pp_pay_date', 'php:d-m-Y') . $pay->getProvider()->name . ' ' . $pay->pp_summ . ' ' .$pay->pp_currency;
                if ($user->ballance->money_amount > (double)Yii::$app->settings->get('sms_pay_planned', 'currency')) {
                    Yii::$app->mainsms->api->sendSMS($pay->getUser()->phone, $message);
                    $user->ballance->money_amount -= (double)Yii::$app->settings->get('sms_pay_planned', 'currency');
                    $user->ballance->save(false);
                } else {
                    $payments = new Payments();
                    $payments->pay_user_id = $user->id;
                    $payments->pay_pc_provider_id = 'MultiPay';
                    $payments->pay_pc_provider_account = $user->phone;
                    $payments->pay_summ = (double)Yii::$app->settings->get('sms_pay_planned', 'currency');
                    $payments->pay_summ_from = (double)Yii::$app->settings->get('sms_pay_planned', 'currency');
                    $payments->pay_comment = Yii::t('payments', 'Нудачное списание средств за sms-уведомление - баланс недостаточен.');
                    $payments->pay_currency = Payments::CURRENCY_AZN; //деволтная валюта системы
                    $payments->pay_type = Payments::PAY_TYPE_BALANCE; 
                    $payments->pay_system = Payments::PAY_SYSTEM_WALLET; //с кошелька
                    $payments->pay_status = Payments::PAY_STATUS_ERROR;
                    $payments->pay_is_payed = Payments::PAY_PAYED;
                    $payments->save(false);
                }
                if (!empty($user->email) && $user->notice_plannedPayments_isEmail) {
                    Yii::$app->mailer->compose('pay_planned_notif_email', ['model' => $pay, 'lang' => $user->user_language])
                        ->setTo($user->email)
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                        ->setSubject(Yii::t('user/notice_plannedPayments_isEmail', 'Напоминание о запланированном платеже', [], $user->user_language))
                        ->send();
                }
                
            }
        }
    }
}