<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use common\models\Payments; 

?>
<div class="b-content-wrapper">
    <div class="b-content">
        <?= frontend\widgets\ProvidersList\ProvidersSidebarWidget::widget();?>
        <div class="b-inner-page">
            <h1><?= Yii::t('payment', 'ОПЛАТА')?></h1>
            <div class="b-payment-wrapper">
                <div class="b-payment-steps b-step-1">
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
                    <a class="b-other-operator" href="javascript:void(0);" onclick="ChooseProviders(<?= $provider->root?>, this)"><?= Yii::t('payment', 'ДРУГОЙ_ОПЕРАТОР')?></a>
                </div>
                <div class="b-payment-form">
                    <div class="steps-1 b-recipient-text"><?= Yii::t('payment', 'ПОЛУЧАТЕЛЬ_ПЛАТЕЖА')?>: <?= $provider->name?></div>
                    <?php
                    $this->registerJs(
                        '
                        $(document).ready(function(){
                           $(".b-select").styler();
                       });'
                        , yii\web\View::POS_END);

                        $form = ActiveForm::begin([
                            'id' => 'pay-step-one',
                            'enableClientValidation' => true,
                            'enableAjaxValidation' => false,
                            'fieldConfig' => [
                                'errorOptions' => [
                                    'tag' => 'div',
                                    'class' => 'b-error b-true'
                                ]
                            ]
                        ]);
                    ?>
                    <!--<form method="post" action="/payments/check">-->
                        <?php // echo Html::hiddenInput('pay_id', $model->pay_id)?>
                        <table class="b-table">
                            <tbody>
                                <tr>
                                    <td class="b-name"><?= $model->getAttributeLabel('pay_pc_provider_account')?></td>
                                    <td>
                                        <div class="b-field b-left">
                                            <?= $form->field($model, 'pay_pc_provider_account', [])->textInput(['class' => 'b-input'])->label(false)?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="b-name"><?= $model->getAttributeLabel('pay_system')?></td>
                                    <td>
                                        <div class="b-field">
                                        <?php if (!\Yii::$app->user->isGuest):?>
                                            <?= $form->field($model, 'pay_system', ['inputOptions' => ['class' => 'b-select']])
                                                ->dropDownList(common\models\Payments::systemPayedLabelsUser())
                                                ->label(false)?>
                                        <?php else:?>
                                            <?= $form->field($model, 'pay_system', ['inputOptions' => ['class' => 'b-select']])
                                                ->dropDownList(common\models\Payments::systemPayedLabels())
                                                ->label(false)?>
                                        <?php endif;?>
                                        <p style="text-align: right; margin-bottom: 0; margin-top: -10px;"><?= Yii::t('providers/descriptions','Комиссия:')?> <?= $provider->pay_sum_min .'%'?></p>
                                        </div>
                                            <?= Html::activeHiddenInput($model, 'pay_currency', ['id' => 'payment-currency']);?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="b-name"><?= $model->getAttributeLabel('pay_summ')?></td>
                                    <td>
                                        <div class="b-field">
                                            <?=
                                            $form->field($model, 'pay_summ',['template' => "{label}\n\n{input}<span stlye=\"float:right;height:30px;\" class=\"b-currency\"></span>\n{hint}\n{error}",'options' => ['style' => 'width: 214px;']])
                                                ->widget(MaskedInput::className(),[
                                                    'mask' => '9{*}.9{2}',
                                                    'options' => ['class' => 'b-input', 'style' => 'width: 100%;'],
                                                    'type' => 'text',
                                                    'clientOptions' => [
                                                        'alias' => 'decimal',
                                                    ]])
                                                ->textInput(['class' => 'b-input', 'style' => 'float: left; width: 75%; height: 30px;', 'placeholder' => '0.00'])->label(false)?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="b-name"></td>
                                    <td>
                                        <div class="b-field b-coins">
                                            <?= Html::submitButton(Yii::t('payment', 'ОПЛАТИТЬ'), ['class' => 'b-submit']) ?>
                                        </div>
                                        <?php if (!\Yii::$app->user->isGuest):?>
                                        <div class="b-payment-fav-nav">
                                            <?= Html::a(Yii::t('payment', 'СОХРАНИТЬ'), 'javascript:void(0)',
                                                ['onclick' => 'AddPaymentTemplate('.$provider->id.', "template")', 'class' => "b-save-icon" ])?>
                                            <?= Html::a(Yii::t('payment', 'ЗАПЛАНИРОВАТЬ'), 'javascript:void(0)',
                                                ['onclick' => 'AddPaymentTemplate('.$provider->id.', "planned")', 'class' => "b-planed-icon", "style"=>"display:none;" ])?>
                                        </div>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php ActiveForm::end();?>
                    <!--</form>-->
                </div>
                <p><?= $provider->getAttributeLabel('pay_sum_min')?> - <?= $provider->pay_sum_min .' '. $provider->currency; ?><br>
                    <?= $provider->getAttributeLabel('pay_sum_max')?> - <?= $provider->pay_sum_max .' '. $provider->currency; ?><br><br>
                    <?= Yii::t('providers/descriptions',$provider->description)?></p>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>

