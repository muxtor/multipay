<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\models\Payments;

$this->title = Yii::t('paysystem','Пополнение кошелька');


//$this->registerJs(<<<JS
//     $(document).ready(function(){
//            $('.b-select').styler();
//        });
//JS
//        , yii\web\View::POS_READY);
?>



<!--<div class="b-content-wrapper">-->
    <div class="b-content">
        <div class="b-content-full-width">
            <div class="b-settings-wrapper">
                <?php
                $form = ActiveForm::begin([
                            'options' => ['class' => 'form_complete'],
                ]);
                ?>
                    <h1 style="padding: 0 20px;"><?=$this->title;?></h1>
                    <div class="b-payment-form" style="padding: 20px;">
                            <?= Html::hiddenInput('pay_id', $payment->pay_id)?>
                        <div class="b-recipient-text"><?= Yii::t('payment', 'ПОЛУЧАТЕЛЬ_ПЛАТЕЖА')?>: <?= Yii::t('payment', 'MultiPay')?></div>
                            <table class="b-table-payment b-submit-form">
                                <tbody>
                                    <tr>
                                        <td class="b-name"><?= $payment->getAttributeLabel('pay_system')?>:</td>
                                        <td><?= Yii::$app->user->isGuest ? Payments::systemPayedLabels()[$payment->pay_system] : Payments::systemPayedLabelsUser()[$payment->pay_system]?></td>
                                    </tr>
                                    <tr><td class="b-name"><?=Yii::t('paysystem','Сумма пополнения');?>:</td><td><?= $payment->pay_summ_from . ' ' . Yii::t('paysystem','AZN')?></td></tr>
                                    <tr><td class="b-name"><?=Yii::t('paysystem','К оплате');?>:</td><td><?= $payment->pay_summ . ' ' . $payment->pay_currency;?></td></tr>

                                    <?php if (!\Yii::$app->user->isGuest):?>
                                    <tr> <td class="b-name"><?=Yii::t('app', 'Мы отправили вам SMS с кодом для подтверждения операции');?></td></tr>
                                    <tr><td class="b-name"><?= $payment->getAttributeLabel('pay_smsCode')?>:</td>
                                        <td><div class="b-field"><?= Html::textInput('pay_smsCode', '', ['class' => 'b-input'])?></div></td>
                                    </tr>
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


                                    <?php endif;?>
                                    <tr>
        <!--                                <td class="b-name">
                                            <input class="b-submit b-edit" value="<?= Yii::t('payment', 'ИЗМЕНИТЬ_ДАННЫЕ')?>" type="submit" name="reject">
                                        </td>-->
                                        <td>
                                            <input class="b-submit" value="<?= Yii::t('payment', 'ПОДТВЕРДИТЬ')?>" type="submit" name="agree">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>

                <?php ActiveForm::end(); ?>        
            </div>
        </div>
    </div>
<!--</div>-->

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
