<?php

use yii\helpers\Html;
use common\models\Payments;

?>
<div class="b-content">
    <?= frontend\widgets\ProvidersList\ProvidersSidebarWidget::widget();?>
    <div class="b-inner-page">
        <h1>Оплата</h1>
        <div class="b-payment-wrapper">
            <div class="b-payment-steps b-step-3">
                <div class="b-line"></div>
                <div class="b-step-icon-1"><span>1</span><?= Yii::t('payment', 'ВВОД_ДАННЫХ')?></div>
                <div class="b-step-icon-2"><span>2</span><?= Yii::t('payment', 'ПОДТВЕРЖДЕНИЕ')?></div>
                <div class="b-step-icon-3"><span>3</span><?= Yii::t('payment', 'РЕЗУЛЬТАТ')?></div>
            </div>
            <div class="b-operator-block">
                <a class="b-operator" href="#">
                    <div class="b-image"><?= !empty($provider->logo)?Html::img("/uploads/providers-logo/".$provider->logo, ['alt' => $provider->name]):''?></div>
                    <div class="b-text"><?= $provider->name?></div>
                </a>
            </div>
            <div class="b-payment-form">
                <b><?= Yii::t('payment/step3', 'Спасибо, Ваш платёж выполнен!')?></b><br><br>
                <div class="b-recipient-text"><?= Yii::t('payment/step3', 'Получатель платежа')?>: <?= $provider->name?></div>
                <table class="b-table-payment b-submit-form">
                    <tbody><tr><td class="b-name"><?= Yii::t('payment/step3', 'Ваш логин')?>:</td><td><?= $payment->pay_pc_provider_account?></td></tr>
                        <tr>
                            <td class="b-name"><?= Yii::t('payment/step3', 'Способ оплаты')?><?/*= $payment->getAttributeLabel('pay_system')*/?>:</td>
                            <td><?= Yii::$app->user->isGuest ? Payments::systemPayedLabels()[$payment->pay_system] : Payments::systemPayedLabelsUser()[$payment->pay_system]?></td>
                        </tr>
                        <tr><td class="b-name"><?= Yii::t('payment/step3', 'Сумма оплаты')?><?/*= $payment->getAttributeLabel('pay_summ_from')*/?>:</td><td><?= $payment->pay_summ_from . ' ' . $payment->pay_currency?></td></tr>
                        <tr><td class="b-name"><?= Yii::t('payment/step3', 'Зачислено')?>:</td><td><?= round($payment->pay_summ_from*$payment->pay_rate, 2) . ' ' . $payment->pay_provider_currency_pc?></td></tr>
                        <tr><td class="b-name"><?= Yii::t('payment/step3', 'Дата')?>:</td><td><?= Yii::$app->formatter->asDate($payment->pay_payed, 'php:d.m.Y')?></td></tr>
                        <tr><td class="b-name"><?= Yii::t('payment/step3', '№ платежа')?>:</td><td><?= $payment->pay_id//pay_pc_id?></td></tr>
                        <tr><td class="b-name"><input class="b-submit b-edit" value="<?= Yii::t('payment/step3', 'Закрыть')?>" type="submit" id="close-step-three"></td><td></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>
