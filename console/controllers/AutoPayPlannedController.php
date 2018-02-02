<?php

namespace console\controllers;

use common\models\Invoice;
use common\models\Payments;
use common\models\PayPlanned;
use common\models\Providers;
use common\models\User;
use Yii;
use yii\console\Controller;
use yii\db\Expression;
use yii\db\Query;
use common\components\CheckPcSend;

/**
 * Class AutoPayPlannedController
 * @package console\controllers
 * develop to pay every hour
 */

class AutoPayPlannedController extends Controller
{

    public function actionIndex()
    {
        $now = new \DateTime();
        $pays_once = PayPlanned::find()
            ->where('pp_type = :type', [':type' => PayPlanned::TYPE_ONCE])
//            ->andWhere(['between', 'pp_pay_date', $now->format('Y-m-d H:i').':00', $now->format('Y-m-d H:i').':59'])//every minute
            ->andWhere(['between', 'pp_pay_date', $now->format('Y-m-d H').':00:00', $now->format('Y-m-d H').':59:59'])//every hour
            ->andWhere('pp_system = :sys', [':sys' => Payments::PAY_SYSTEM_WALLET])
            ->andWhere('pp_currency = :curr', [':curr' => Payments::CURRENCY_AZN])
            ->all();

        $pays_week = PayPlanned::find()
            ->innerJoin('{{%pay_planned_week}}', 'ppw_pay_plan_id = pp_id')
            ->where('pp_type = :type', [':type' => PayPlanned::TYPE_WEEK])
            ->andWhere(['ppw_day' => date('N')])
//                ->andWhere(['between', 'pp_pay_date', $now->format('Y-m-d H:i').':00', $now->format('Y-m-d H:i').':59'])//every minute
            ->andWhere(['between', 'pp_pay_date', $now->format('Y-m-d H').':00:00', $now->format('Y-m-d H').':59:59'])//every hour
            ->andWhere('pp_system = :sys', [':sys' => Payments::PAY_SYSTEM_WALLET])
            ->andWhere('pp_currency = :curr', [':curr' => Payments::CURRENCY_AZN])
            ->all();

        $pays_month = PayPlanned::find()
            ->innerJoin('{{%pay_planned_month}}', 'ppm_pay_plan_id = pp_id')
            ->where('pp_type = :type', [':type' => PayPlanned::TYPE_MONTH])
            ->andWhere(['ppm_day' => date('j')])
//                ->andWhere(['between', 'pp_pay_date', $now->format('Y-m-d H:i').':00', $now->format('Y-m-d H:i').':59'])//every minute
            ->andWhere(['between', 'pp_pay_date', $now->format('Y-m-d H').':00:00', $now->format('Y-m-d H').':59:59'])//every hour
            ->andWhere('pp_system = :sys', [':sys' => Payments::PAY_SYSTEM_WALLET])
            ->andWhere('pp_currency = :curr', [':curr' => Payments::CURRENCY_AZN])
            ->all();

        $planned_pays = array_merge($pays_once, $pays_week, $pays_month);
        if ($planned_pays) {
            /* @var $item PayPlanned */
            foreach ($planned_pays as $item) {
                /* @var $provider Providers */
                $provider = Providers::findOne($item->pp_provider_id);
                /* @var $user User */
                $user = User::findOne($item->pp_user_id);
                //найден пользователь, провайдер, провайдер дочерний элемент и у него есть id в ПЦ
                if ($provider && !count($provider->children()->all()) && $provider->pc_id && $user) {
                    $full_summ = $item->pp_summ + ceil(($item->pp_summ*$provider->comission_percent)/100);
                    if ($user->ballance->money_amount < $full_summ) {
                        $model = new Payments();
                        $model->pay_user_id = $item->pp_user_id;
                        $model->pay_pc_provider_id = $provider->pc_id;
                        $model->pay_summ = $item->pp_summ;
                        $model->pay_summ_from = $full_summ;
                        $model->pay_payed = new Expression('NOW()');
                        $model->pay_currency = $item->pp_currency;
                        $model->pay_pc_provider_account = $item->pp_account;
                        $model->pay_is_checked = Payments::CHECK_CHECKED;
                        $model->pay_check_result = Payments::CHECK_RESULT_INTERNAL_ERROR;
                        $model->pay_check_result_desc = 'Not enough money on balance';
                        $model->pay_check_status = Payments::CHECK_STATUS_ERROR;
                        $model->pay_type = Payments::PAY_TYPE_PROVIDER;
                        $model->pay_system = $item->pp_system;
                        $model->save();
                        if ($user->notice_plannedPayments_isEmail && $user->email) {
                            Yii::$app->mailer->compose('pay_planned_notif_email_error', ['model' => $model, 'lang' => $user->user_language])
                                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                                ->setTo($user->email)
                                ->setSubject(Yii::t('payments/mail', 'Уведомление о просроченном платеже', [], $user->user_language))
                                ->send();
                        }
                    } else {
                        if (!$item->pp_is_auto) {
                            $model = new Payments();
                            $model->pay_user_id = $item->pp_user_id;
                            $model->pay_provider_id = $provider->id;
                            $model->pay_pc_provider_id = $provider->pc_id;
                            $model->pay_summ = $item->pp_summ;
                            $model->pay_summ_from = $full_summ;
                            $model->pay_currency = $item->pp_currency;
                            $model->pay_pc_provider_account = $item->pp_account;
                            $model->pay_type = Payments::PAY_TYPE_PROVIDER;
                            $model->pay_system = $item->pp_system;
                            if ($model->save()) {
                                Yii::$app->db->createCommand("SET foreign_key_checks = 0;")->execute();
                                //сохраняем счет
                                $invoice = new Invoice();
                                $invoice->payment_id = $model->pay_id;
                                $invoice->from_user_id = 0;
                                $invoice->to_user_id = $model->pay_user_id;
                                $invoice->status = Invoice::STATUS_WAIT;
                                $invoice->comment = Yii::t('invoice', 'PLANNED_PAYMENT');
                                if (!$invoice->save()) {
                                    Payments::findOne($model->pay_id)->delete();
                                }
                                Yii::$app->db->createCommand("SET foreign_key_checks = 1;")->execute();
                            }
                        } else {
                            $model = new Payments();
                            $model->pay_user_id = $item->pp_user_id;
                            $model->pay_provider_id = $provider->id;
                            $model->pay_pc_provider_id = $provider->pc_id;
                            $model->pay_summ = $item->pp_summ;
                            $model->pay_summ_from = $full_summ;
                            $model->pay_currency = $item->pp_currency;
                            $model->pay_pc_provider_account = $item->pp_account;
                            $model->pay_type = Payments::PAY_TYPE_PROVIDER;
                            $model->pay_system = $item->pp_system;
                            if ($model->save()) {
                                $result = CheckPcSend::checkPay($model->pay_id, $model->pay_pc_provider_id, $model->pay_pc_provider_account,
                                    $model->pay_summ_from, $model->pay_currency);
                                if ($result) {
                                    CheckPcSend::analizeCheckResponce($model->pay_id, $result);
                                }
                            }
                        }
                    }
                    //удаляем одиночный платеж
                    if ($item->pp_type = PayPlanned::TYPE_ONCE) {
                        $item->delete();
                    }
                }
            }
        }
    }

}
