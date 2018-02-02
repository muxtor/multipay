<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Query;
use common\models\Payments;
use common\components\CheckPcSend;

/**
 * Class AutoPayInCheckController
 * @package console\controllers
 * develop to pay every minute
 */

class AutoPayInCheckController extends Controller
{
    public function actionIndex()
    {
        $payments = (new Query())
            ->select('payment_id')
            ->from('{{%payment_in_check}}')
            ->column();
        if ($payments) {
            foreach ($payments as $payment) {
                $model = Payments::findOne($payment);
                if ($model) {
                    /* @var $model Payments*/
                    $result = CheckPcSend::checkPay($model->pay_id, $model->pay_pc_provider_id, $model->pay_pc_provider_account,
                        $model->pay_summ_from, $model->pay_currency);
                    if ($result) {
                        CheckPcSend::analizeCheckResponce($model->pay_id, $result);
                    }
                }
            }
        }
        return false;
    }
}