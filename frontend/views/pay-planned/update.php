<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use common\models\Payments;
use common\models\PayPlanned;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\PayPlanned */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="b-content">
    <div class="b-content-full-width">
        <h1><?= $model->provider->name?></h1>
        <div class="b-content-full-width-inner">
            <div class="b-payments-edit-logo">
                <?= Html::img('/uploads/providers-logo/'.$model->provider->logo, ['alt' => $model->provider->name]);?>
            </div>
            <div class="b-payments-edit-info">
                <span class="b-planed-icon-text"><?= Yii::t('payment', 'ЗАПЛАНИРОВАННЫЕ_ПЛАТЕЖИ')?></span>
                <?php
                $form = ActiveForm::begin([
                    'id' => 'pay-plan-update',
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
                <div class="b-field">
                    <label><?= $model->getAttributeLabel('pp_name')?></label>
                    <?= $form->field($model, 'pp_name', [])->textInput(['class' => 'b-input', 'maxlength' => true])->label(false)?>
                </div>
                <div class="b-field">
                    <label class="b-label-fixed-width"><?= $model->getAttributeLabel('pp_account')?>:</label>
                    <?= $form->field($model, 'pp_account', [])->textInput(['class' => 'b-input'])->label(false)?>
                </div>
                <div class="b-field">
                    <label class="b-label-fixed-width"><?= $model->getAttributeLabel('pp_system')?>:</label><span><?= Yii::t('payment', 'C_КОШЕЛЬКА_MULTIPAY')?></span>
                </div>
                <div class="b-field">
                    <label class="b-label-fixed-width"><?= $model->getAttributeLabel('pp_summ')?>:</label><br>
                    <?= $form->field($model, 'pp_summ',['options' => ['style' => 'width: 170px; display: inline-block; float: left;']])
                        ->textInput(['class' => 'b-input', 'style' => 'width: 100%;'])->label(false)?>
                    <span class="b-currency" id="plan-pay-currency"><?= Yii::t('payment', 'AZN')?></span>
                    <?= Html::activeHiddenInput($model, 'pp_currency', ['value' => Payments::CURRENCY_AZN]);?>
                </div>
                <div class="b-clear"></div>
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
                <div class="b-btn-navs">
                    <input class="b-submit b-green" value="<?= Yii::t('payment', 'СОХРАНИТЬ')?>" type="submit">
                    <a class="btn b-gray" href="/pay-planned/index"><?= Yii::t('payment', 'НАЗАД')?></a>
                    <?= Html::a('', ['/pay-planned/delete', 'id' => $model->pp_id], ['class' => 'b-btn-delete', 'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ]]);?>
                </div>

            </div>
            <div class="b-clear"></div>
        </div>
    </div>
    <br>
</div>
<div class="b-clear"></div>
<?= \frontend\widgets\Tabs\TabsWidget::widget();?>
<?= \frontend\widgets\Logos\LogosWidget::widget();?>
