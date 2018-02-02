<?php

use yii\helpers\Html;
use common\models\Payments;

?>

<div class="b-content">
    <?= frontend\widgets\ProvidersList\ProvidersSidebarWidget::widget();?>
    <div class="b-inner-page">
        <h1><?= Yii::t('payment', 'ОПЛАТА')?></h1>
        <div class="b-payment-wrapper">
            <div class="b-payment-steps b-step-2">
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
                <form method="post" action="/payments/pay">
                    <?= Html::hiddenInput('pay_id', $payment->pay_id)?>
                <div class="b-recipient-text"><?= Yii::t('payment', 'ПОЛУЧАТЕЛЬ_ПЛАТЕЖА')?>: <?= $provider->name?></div>
                    <table class="b-table-payment b-submit-form">
                        <tbody><tr><td class="b-name"><?= Yii::t('payment', 'ВАШ_ЛОГИН')?>:</td><td><?= $payment->pay_pc_provider_account?></td></tr>
                            <tr>
                                <td class="b-name"><?= $payment->getAttributeLabel('pay_system')?>:</td>
                                <td><?= Yii::$app->user->isGuest ? Payments::systemPayedLabels()[$payment->pay_system] : Payments::systemPayedLabelsUser()[$payment->pay_system]?></td>
                            </tr>
                            <tr><td class="b-name"><?= Yii::t('payment', 'Сумма оплаты')?>:</td><td><?= $payment->pay_summ_from . ' ' . $payment->pay_currency?></td></tr>
                            <tr><td class="b-name"><?= Yii::t('payment', 'Будет зачислено')?>:</td><td><?= round($payment->pay_summ_from*$payment->pay_rate, 2) . ' ' . $payment->pay_provider_currency_pc?></td></tr>

                            <?php if (!\Yii::$app->user->isGuest AND $payment->pay_provider_id!=28):?>
                            <tr><td colspan="2" class="b-name"><p><?=Yii::t('app', 'Мы отправили вам SMS с кодом для подтверждения операции');?></p></td></tr>
                            <tr><td class="b-name"><?= $payment->getAttributeLabel('pay_smsCode')?>:</td>
                                <td><div class="b-field"><?= Html::textInput('pay_smsCode', '', ['class' => 'b-input','placeholder'=>Yii::t('app', '6 цифр')])?></div></td>
                            </tr>
                           <!-- <tr> <td class="b-name">Подсказка: <span id="smscode"><?/*//=$payment->pay_smsCode;*/?></span></td></tr>-->
                            <tr> <td class="b-name">    <?php 
                            echo Html::a(Yii::t('yii', 'Прислать код повторно'),'/payments/resend-sms', [
                                    'title' => Yii::t('yii', 'Прислать код повторно'),
                                        'onclick'=>"$.ajax({
                                                    type     :'POST',
                                                    cache    : false,
                                                    url  : '/payments/resend-sms',
                                                    data: {id: ". $payment->pay_id ."},
                                                    success  : function(data) {
                                                        $('#smsMessage').html(data.mes);
                                                        $('#smscode').html(data.code);
                                                        if (data.time) {
                                                            startTimer(data.time, document.querySelector('#timer'));
                                                        }
                                                    }
                                                    });return false;",
                                                    ]);
                                ?>
                            <span id="smsMessage"></span>
                            <span id="timer"></span>
                                </td></tr>

                            <?php else: ?>
                                <?= Html::hiddenInput('pay_smsCode', $payment->pay_smsCode, ['class' => 'b-input'])?>
                            <?php endif;?>
                            <tr>
                                <td class="b-name">
                                    <input class="b-submit b-edit" value="<?= Yii::t('payment', 'ИЗМЕНИТЬ_ДАННЫЕ')?>" type="submit" name="reject">
                                </td>
                                <td>
                                    <input class="b-submit" value="<?= Yii::t('payment', 'ПОДТВЕРДИТЬ')?>" type="submit" name="agree">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>
<script>
    function startTimer(duration, display) {
    var timer = duration, seconds;
    setInterval(function () {
        seconds = parseInt(timer % 60, 10);

        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = seconds;
        
        if (--timer === 0) {
            $('#smsMessage').html('');
        }
    }, 1000);
}

</script>