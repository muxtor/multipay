<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Query;
use common\models\Payments;
use common\components\PayPcSend;
use yii\helpers\ArrayHelper;

/**
 * Class AutoPayInPayController
 * @package console\controllers
 * develop to pay every minute
 */

class AutoPayInPayController extends Controller
{
    public function actionIndex()
    {
        $payments = (new Query())
            ->select('payment_id, payment_date')
            ->from('{{%payment_in_pay}}')
            ->all();
        if ($payments) {
            $payments = ArrayHelper::map($payments, 'payment_id', 'payment_date');
            foreach ($payments as $id => $date) {
                $pay = Payments::findOne($id);
                if ($pay) {
                    /* @var $pay Payments*/
                    $result = PayPcSend::makePay($pay->pay_id, $date, $pay->pay_pc_provider_id, $pay->pay_pc_provider_account,
                        $pay->pay_summ_from, $pay->pay_currency);
                    if ($result) {
                        PayPcSend::analizePayResponce($pay->pay_id, $result, $date);
                    }
                }
            }
        }
        return false;
    }
}