<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Invoice */
/* @var $payments \common\models\Payments */

use common\models\Invoice;
use common\models\Payments;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('view/user/reclame', 'Подтверждение счета');
?>

<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-side-bar">
            <div class="b-side-bar-menu">
                <ul>
                    <li><a href="/invoice/index"><i class="fa-perevod-zapros"></i><?= Yii::t('invoice', 'ВХОДЯЩИЕ') ?>
                        </a></li>
                    <li><a href="/invoice/out-invoice"><i class="fa-refresh"></i><?= Yii::t('invoice',
                                'ВЫСТАВЛЕННЫЕ') ?></a></li>
                    <li><a href="/invoice/add-invoice"><i class="fa-file-text-o"></i><?= Yii::t('invoice',
                                'ВЫСТАВИТЬ_СЧЕТ') ?></a></li>
                    <li><a href="history-invoice"><i class="fa-tasks"></i><?= Yii::t('invoice', 'ИСТОРИЯ') ?></a></li>
                </ul>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-bonus-wrapper b-checks-wrap">
                <h1><?= Yii::t('invoice', 'ПОДТВЕРЖДЕНИЕ_ОПЛАТЫ_СЧЕТА') ?></h1>
            </div>
            <div class="b-settings-wrapper">
                <p>Адресат: <?= $payments->pay_pc_provider_account; ?></p>
                <p>Сумма перевода: <?= $payments->pay_summ; ?></p>
                <p>Сумма к списанию: <?= $payments->pay_summ_from; ?></p>
<!--                <p>Перевод защищен: --><?//= $payments->pay_isProtected ? 'да' : 'нет'; ?><!--</p>-->
                <?php if ($payments->pay_isProtected): ?>
                    <p>Код протекции: <?= $payments->pay_protected_code; ?></p>
                <?php endif; ?>
                <p>Комментарий: <?= $payments->pay_comment; ?></p>
                <br/>

                <div class="b-payment-form" style="padding-top: 0px;">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'form_complete'],]); ?>

                    <table class="b-table-payment b-submit-form">
                        <tbody>
                        <tr>
                            <td class="b-name"><?= Yii::t('app',
                                    'Мы отправили вам SMS с кодом для подтверждения операции'); ?></td>
                        </tr>
                        <tr>
                            <td class="b-name"><?= $payments->getAttributeLabel('pay_smsCode') ?>:</td>
                            <td>
                                <div class="b-field"><?= Html::textInput('pay_smsCode', '',
                                        ['class' => 'b-input']) ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="b-name">Подсказка: <span id="smscode"><?= $payments->pay_smsCode; ?></span></td>
                        </tr>
                        <tr>
                            <td class="b-name">    <?php
                                echo Html::a(Yii::t('yii', 'Прислать код повторно'), '/payments/resend-sms', [
                                    'title' => Yii::t('yii', 'Прислать код повторно'),
                                    'onclick' => "$.ajax({
                                        type     :'POST',
                                        cache    : false,
                                        url  : '/payments/resend-sms',
                                        data: {id: " . $payments->pay_id . "},
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="b-submit" value="<?= Yii::t('payment', 'ПОДТВЕРДИТЬ') ?>" type="submit"
                                       name="agree">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>
<?= \frontend\widgets\Tabs\TabsWidget::widget() ?>
<?= \frontend\widgets\Logos\LogosWidget::widget() ?>
