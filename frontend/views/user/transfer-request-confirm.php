<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Payments;


$this->title = Yii::t('view/user/reclame', 'Подтверждение перевода');
?>
        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-side-bar">
                    <div class="b-side-bar-menu">
                        <?=$this->render('_transfer_menu')?>
                    </div>
                </div>
                <div class="b-categorys">
                    <div class="b-settings-wrapper">
                        <?php if ($payments->pay_status == Payments::PAY_STATUS_NEW):?>
                            <h1><?= Yii::t('transfer', 'Подтверждение запроса на перевод')?></h1>
                        <?php elseif ($payments->pay_status == Payments::PAY_STATUS_DONE):?>
                            <h1><?= Yii::t('transfer', 'Перевод выполнен успешно')?></h1>
                        <?php elseif ($payments->pay_status == Payments::PAY_STATUS_ERROR):?>
                            <h1><?= Yii::t('transfer', 'Ошибка! Отмена транзакции.')?></h1>
                        <?php endif;?>
                        
                        <p><?= Yii::t('transfer', 'Адресат:')?> <?= $payments->pay_pc_provider_account; ?></p>
                        <p><?= Yii::t('transfer', 'Сумма перевода:')?> <?= $payments->pay_summ; ?></p>
                        <p><?= Yii::t('transfer', 'Сумма списания:')?> <?= $payments->pay_summ_from; ?></p>
                        <p><?= Yii::t('transfer', 'Комментарий:')?> <?= $payments->pay_comment; ?></p>
                        <br />
                        
                        <?php if ($payments->pay_status == Payments::PAY_STATUS_NEW && $payments->pay_is_payed == Payments::PAY_NOT_PAYED):?>
                        <h1><?=$this->title?></h1>
                        <div class="b-payment-form" style="padding-top: 0px;">
                        <?php $form = ActiveForm::begin(['id' => 'form-transfer-confirm', 'options' => ['class' => 'b-form']]); ?>
                            <table class="b-table-payment b-submit-form">
                                    <tbody>
                                        <tr> <td class="b-name"><?=Yii::t('app', 'Мы отправили вам SMS с кодом для подтверждения операции');?></td></tr>
                                        <tr><td class="b-name"><?= $payments->getAttributeLabel('pay_smsCode')?>:</td>
                                            <td><div class="b-field"><?= Html::textInput('pay_smsCode', '', ['class' => 'b-input'])?></div></td>
                                        </tr>
                                        <!--<tr> <td class="b-name">Подсказка: <span id="smscode"><?/*=$payments->pay_smsCode;*/?></span></td></tr>-->
                                        <tr> <td class="b-name">    <?php 
                                                echo Html::a(Yii::t('yii', 'Прислать код повторно'),'/payments/resend-sms', [
                                                        'title' => Yii::t('yii', 'Прислать код повторно'),
                                                            'onclick'=>"$.ajax({
                                                                        type     :'POST',
                                                                        cache    : false,
                                                                        url  : '/payments/resend-sms',
                                                                        data: {id: ". $payments->pay_id ."},
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
                                        <tr>
                                            <td>
                                                <input class="b-submit" value="<?= Yii::t('payment', 'ПОДТВЕРДИТЬ')?>" type="submit" name="agree">
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>
                        <?php ActiveForm::end(); ?>
                        <?php endif;?>
                        </div>
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