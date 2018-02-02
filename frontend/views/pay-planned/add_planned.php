<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\widgets\DateTimePicker;
use common\models\PayPlanned;
use common\models\Payments;

/* @var $model common\models\PayPlanned*/

?>
<div class="b-content-wrapper">
    <div class="b-content">
        <?= frontend\widgets\ProvidersList\ProvidersSidebarWidget::widget();?>
        <div class="b-inner-page">
            <h1><?= $model->provider->name?></h1>
            <div class="b-payment-wrapper">
                <span class="b-planed-icon-text"><?= Yii::t('payment', 'ЗАПЛАНИРОВАННЫЕ_ПЛАТЕЖИ')?></span>
                <div class="b-operator-block b-add-to-fav">
                    <a class="b-operator" href="#">
                        <div class="b-image"><?= Html::img("/uploads/providers-logo/".$model->provider->logo, ['alt' => $model->provider->name])?></div>
                        <div class="b-text"><?= $model->provider->name?></div>
                    </a>
                    <a class="b-other-operator" href="#"><?= Yii::t('payment', 'ДРУГОЙ_ОПЕРАТОР')?></a>
                </div>
                <div class="b-payment-form planned-payments">


                <?php
                $form = ActiveForm::begin([
                    'id' => 'pay-plan-add',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'fieldConfig' => [
                        'errorOptions' => [
                            'tag' => 'div',
                            'class' => 'b-error b-true'
                        ],
                        'inlineRadioListTemplate' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}"
                    ]
                ]);
                ?>
                <table class="b-table-add-to-fav">
                    <tbody>
                        <tr>
                            <td class="b-first"><?= $model->getAttributeLabel('pp_name')?></td>
                            <td><div class="b-field"><?= $form->field($model, 'pp_name', [])->textInput(['class' => 'b-input'])->label(false)?></div></td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= $model->getAttributeLabel('pp_account')?>:</td>
                            <td><div class="b-field"><?= $form->field($model, 'pp_account', [])->textInput(['class' => 'b-input'])->label(false)?></div></td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= $model->getAttributeLabel('pp_system')?>:</td>
                            <td><div class="b-field"><?= Yii::t('payment', 'C_КОШЕЛЬКА_MULTIPAY')?></div></td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= $model->getAttributeLabel('pp_summ')?>:</td>
                            <td><div class="b-field"><?= $form->field($model, 'pp_summ',['options' => ['style' => 'width: 170px; display: inline-block; float: left;']])
//                                        ->widget(MaskedInput::className(),[
//                                            'mask' => '9{*}.9{2}',
//                                            'options' => ['class' => 'b-input', 'style' => 'width: 100%;'],
//                                            'type' => 'text'])
                                        ->textInput(['class' => 'b-input', 'style' => 'width: 100%;'])->label(false)?>
                                    <span class="b-currency" id="plan-pay-currency"><?= Yii::t('payment', 'AZN')?></span>
                                    <?= Html::activeHiddenInput($model, 'pp_currency', ['value' => Payments::CURRENCY_AZN]);?>
                                </div></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="b-payments-edit-info">
                                    <div class="b-planed-form">
                                        <h2><?= Yii::t('payment', 'ЗАПЛАНИРОВАТЬ')?></h2>
                                        <div class="b-radio-fieldset">
                                            <?= $form->field($model, 'pp_type', [
                                            ])->inline()->radioList(PayPlanned::getTypeLabels(), ['item' => function($index, $label, $name, $checked, $value){
                                                return '<label>' . Html::radio($name, $checked, ['value' => $value]) . $label . '</label>';
                                            }])->label(false)?>
                                        </div>
                                        <div class="b-clear"></div>
                                        <div class="b-planned-date b-table-filter">
                                            <div class="b-field" id="pay_planned_datetime">
                                                <label><?= $model->getAttributeLabel('pp_pay_date')?></label>
                                                <?= $form->field($model, 'pp_pay_date',['options' => ['style' => 'width: 120px;']])
                                                    ->widget(DateTimePicker::classname(), [
                                                        'options' => ['placeholder' => '__ - __ - ____ __:__', 'class' => 'b-input', 'style' => 'width: 120px !important;', 'id' => 'plan-datetime'],
                                                        'removeButton' => false,
                                                        'pickerButton' => [
//                                                            'icon' => 'time',
                                                            'title' => Yii::t('payment', 'ВЫБРАТЬ_ДАТУ_И_ВРЕМЯ')
                                                        ],
                                                        'buttonOptions' => [
                                                            'label' => '<img title="" alt="" src="/images/auth/calendar-icon.png" class="ui-datepicker-trigger">'
                                                        ],
                                                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                                        'pluginOptions' => [
                                                            'startDate' => date('d-m-Y h:i'),
                                                            'autoclose' => true,
                                                            'format' => 'dd-mm-yyyy hh:ii',
                                                            'minuteStep' => 1,
                                                            'todayHighlight' => true,
                                                        ]
                                                    ])->label(false)?>
                                            </div>
                                            <div class="b-field" id="pay_planned_time" style="display: none;">
                                                <label><?= $model->getAttributeLabel('pay_time')?></label>
                                                <?= $form->field($model, 'pay_time',['options' => ['style' => 'width: 120px;']])
                                                    ->widget(DateTimePicker::classname(), [
                                                        'options' => ['placeholder' => '__:__', 'class' => 'b-input', 'style' => 'width: 120px !important;', 'id' => 'plan-time'],
                                                        'removeButton' => false,
                                                        'pickerButton' => [
//                                                            'icon' => 'time',
                                                            'title' => Yii::t('payment', 'ВЫБРАТЬ_ВРЕМЯ')
                                                        ],
                                                        'buttonOptions' => [
                                                            'label' => '<img title="" alt="" src="/images/auth/calendar-icon.png" class="ui-datepicker-trigger">'
                                                        ],
                                                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                                        'pluginOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'hh:ii',
                                                            'minuteStep' => 1,
                                                            'todayHighlight' => true,
                                                            'startView' => 1,
                                                            'startDate' => date('dd-mm-yyy')
                                                        ]
                                                    ])->label(false)?>
                                            </div>
                                        </div>
                                        <div class="b-clear"></div>
                                        <div class="b-planed-days-wrapper" style="display: none;">
                                            <?= $form->field($model, 'days_of_week', [])->inline()->checkboxList(PayPlanned::getWeekDays(),
                                                ['item' => function($index, $label, $name, $checked, $value){
                                                        return '<label>' . Html::checkbox($name, $checked, ['value' => $value]) . '<span>' .$label . '</span></label>';
                                                }])->label(false)?>
                                            <?= $form->field($model, 'days_of_month', [])->inline()->checkboxList(PayPlanned::getMonthDays(),
                                                ['item' => function($index, $label, $name, $checked, $value){
                                                        return '<label>' . Html::checkbox($name, $checked, ['value' => $value]) . '<span>' .$label . '</span></label>';
                                                }])->label(false)?>
                                        </div>
                                        <div class="b-clear"></div>
                                        <div class="b-checkbox-fields">
                                            <div class="b-field">
<!--                                                <input class="b-checkbox" value="" type="checkbox"><label>Платить автоматически</label>-->
                                                <?= Html::activeCheckbox($model, 'pp_is_auto', [])?>
                                            </div>
                                            <div class="b-field">
                                                <?= Html::activeCheckbox($model, 'pp_is_notif', [])?>
                                                <?= Html::activeInput('', $model, 'pay_notif_day_amount', ['class' => 'b-input', 'disabled' => true]) ?>
                                                <label><?= Yii::t('payment', 'ДНЕЙ_ДО_ПЛАТЕЖА')?></label>
                                            </div>
                                            <span class="b-planned-price"><?= Yii::t('payment', 'СТОИМОСТЬ_1_5_Р')?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="b-field">
                                    <input class="b-submit" value="<?= Yii::t('payment', 'СОХРАНИТЬ')?>" type="submit">
                                    <?= Html::a(Yii::t('payment', 'НАЗАД'), ['/payments/step-one', ['id' => $model->pp_provider_id]],
                                        ['class' => 'btn b-gray'])?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>
<?= \frontend\widgets\Tabs\TabsWidget::widget();?>
<?= \frontend\widgets\Logos\LogosWidget::widget();?>