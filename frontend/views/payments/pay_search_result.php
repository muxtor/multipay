<?php 

use common\models\Language;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\helpers\VarDumper;
use common\models\Payments;
use kartik\widgets\DatePicker;

?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-register-wrapper b-search-pay">
            <h1><?= Yii::t('payment', 'РЕЗУЛЬТАТ_ПОИСКА_ПЛАТЕЖА')?></h1>
            <b><?= Yii::t('payment', 'ВВЕДЕННЫЕ_ДАННЫЕ')?>:</b>
            <table class="b-table">
                <tbody>
                    <tr>
                        <td class="b-name"><?= $model->getAttributeLabel('check_date')?></td>
                        <td>
                            <?= $model->check_date?>
                        </td>
                    </tr>
                    <tr>
                        <td class="b-name"><?= $model->getAttributeLabel('terminal_id')?></td>
                        <td>
                            <?php $terminal = \common\models\Terminal::find()->where(['number'=>$model->terminal_id])->one();
                            if($terminal!=null){
                                echo $terminal->name;
                            }else{
                                echo $model->terminal_id;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="b-name"><?= $model->getAttributeLabel('check_number')?></td>
                        <td>
                            <?= $model->check_number?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <b><?= Yii::t('payment', 'РЕЗУЛЬТАТ')?>:</b>
            <?php if (isset($result['errorMessage'])):?>
                <p><?php
                    if (strpos($result['errorMessage'], 'Найдено несколько платежей') !== false) {
                        echo Yii::t('payment', 'Найдено несколько платежей');
                    }elseif (strpos($result['errorMessage'], 'Платеж успешный') !== false) {
                        echo '<p style="color: green;">'.Yii::t('payment', 'Платеж успешный').'</p>';
                    }else{
                        echo Yii::t('payment', $result['errorMessage']);
                    }
                   ?></p>
            <?php elseif (isset($result['id'])) :?>
                <p style="color: <?php if($result['status']==1){ echo 'green';}elseif($result['status']==2){ echo 'red';}else{ echo 'black'; } ?>"><?= Yii::t('payment', 'СТАТУС_ПЛАТЕЖА').': '.array_key_exists($result['status'], Payments::payStatusLabels()) ? Payments::payStatusLabels()[$result['status']] : Yii::t('payment', 'НЕИЗВЕСТНЫЙ_СТАТУС_ПЛАТЕЖА') ?></p>
                <p style="color: <?php if($result['status']==1){ echo 'green';}elseif($result['status']==2){ echo 'red';}else{ echo 'black'; } ?>"><?= Yii::t('payment', 'СУММА_ОПЛАТЫ').': '.isset($result['summ']) ? $result['summ'] .' AZN' : Yii::t('payment', 'НЕИЗВЕСТНАЯ_СУММА')?></p>
            <?php else:?>
                <p><?= Yii::t('payment', 'НЕИЗВЕСТНЫЙ_ОТВЕТ_СЕРВЕРА')?></p>
            <?php endif; Yii::t('payment', 'Найдено несколько платежей');?>
        </div>
    </div>
</div>

<div style="opacity: 0; width: 0; height: 0;">
<?php $form = ActiveForm::begin([
    'id' => 'payment-search',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'b-form'],
    'fieldConfig' => [
        'errorOptions' => [
            'tag' => 'div',
            'class' => 'b-error b-true',
            'style' => 'position: unset;'
        ]
    ]
]); ?>


<!--<form action="" method="post" class="b-form">-->
<table class="b-table">
    <tbody>
    <tr>
        <td class="b-name"><?= $model->getAttributeLabel('check_date')?> <sup class="b-red">*</sup></td>
        <td>
            <div class="b-filed">
                <?= $form->field($model, 'check_date',['options' => []])
//                                                ->widget(MaskedInput::className(),[
//                                                    'mask' => '9{2}.9{2}.9{4}',
//                                                    'options' => ['class' => 'b-input'],
////                                                    'options' => ['class' => 'b-input', 'style' => 'width: 100%; height: 40px;'],
//                                                    'type' => 'text'])
                    ->widget(DatePicker::className(), [
                        'options' => [
                            'placeholder' => '__ - __ - ____',
                            'class' => 'b-input',
                            'style' => 'width: 120px !important;',
                            'id' => 'search-date'
                        ],
                        'removeButton' => false,
                        'pickerButton' => [
                            'title' => Yii::t('payment', 'ВЫБРАТЬ_ДАТУ')
                        ],
                        'buttonOptions' => [
                            'label' => '<img title="" alt="" src="/images/auth/calendar-icon.png" class="ui-datepicker-trigger">'
                        ],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ]
                    ])
                    ->label(false)?>
            </div>
        </td>
    </tr>
    <tr>
        <td class="b-name"><?= $model->getAttributeLabel('terminal_id')?> <sup class="b-red">*</sup></td>
        <td>

            <div class="b-field">
                <?= $form->field($model, 'terminal_id')->dropDownList(
                    \common\models\Terminal::getList(),
                    ['class' => 'b-select','prompt' => Yii::t('payment', 'ВЫБИРИТЕ_ТЕРМИНАЛ')]//
                )->label(false) ?>
                <?/*= $form->field($model, 'terminal_id',['options' => []])
                                        ->textInput(['class' => 'b-input'])
//                                        ->textInput(['class' => 'b-input', 'style' => 'width: 100%; height: 40px;'])
                                        ->label(false)*/ ?>
            </div>
            <?php
            $this->registerJs(
                '$(document).ready(function(){
                        $("#paymentsearchform-terminal_id").styler();
            });'
                , yii\web\View::POS_END);
            ?>
            <script></script>
        </td>
    </tr>
    <tr>
        <td class="b-name"><?= $model->getAttributeLabel('check_number')?> <sup class="b-red">*</sup></td>
        <td>
            <div class="b-filed">
                <?= $form->field($model, 'check_number',['options' => []])
                    ->textInput(['class' => 'b-input'])
//                                        ->textInput(['class' => 'b-input', 'style' => 'width: 100%; height: 40px;'])
                    ->label(false) ?>
            </div>
        </td>
    </tr>
    <tr><td class="b-name"></td><td><div class="b-payment-search-btn"><input class="b-submit b-payment-search" value="<?= Yii::t('payment', 'НАЙТИ')?>" type="submit"></div></td></tr>
    <tr><td class="b-name"></td><td><sup class="b-red">*</sup><span class="b-text-requre"> - <?= Yii::t('payment', 'ОБЯЗАТЕЛЬНО_ДЛЯ_ЗАПОЛНЕНИЯ')?></span></td></tr>
    </tbody>
</table>
<!--</form>-->
</div>