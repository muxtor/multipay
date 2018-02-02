<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(User::getStatusLabels(), ['prompt' => 'Choose status...']) ?>
    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'country_id')->dropDownList(\yii\helpers\ArrayHelper::map(common\models\User::getCountryes(), 'id', 'name'), ['prompt' => Yii::t('common.models.user','Выберите страну')]) ?>
    <?=  $form->field($model, 'date_bird')->textInput()->widget(\kartik\date\DatePicker::classname(), [
                                    'name' => 'dp_1',
                                    'type' => \kartik\date\DatePicker::TYPE_INPUT,
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'format' => 'yyyy-mm-dd'
                                    ]
                                ]); ?>
    
    <?= $form->field($model, 'notice_safety_isEmail')->checkbox() ?>
    <?= $form->field($model, 'notice_safety_isPhone')->checkbox() ?>
    
    <?= $form->field($model, 'notice_plannedPayments_isEmail')->checkbox() ?>
    <?= $form->field($model, 'notice_plannedPayments_isPhone')->checkbox() ?>
    
    <?= $form->field($model, 'notice_latePayments_isEmail')->checkbox() ?>
    <?= $form->field($model, 'notice_latePayments_isPhone')->checkbox() ?>
    
    <?= $form->field($model, 'notice_news_isEmail')->checkbox() ?>
    <?= $form->field($model, 'notice_news_isPhone')->checkbox() ?>
    
    <?= $form->field($model, 'verification_code_send')->dropDownList(User::codeSendLabels()) ?>
    <?= $form->field($model, 'verification_code_method')->radioList(User::codeMathodLabels()) ?>
    
    <?php if(!$model->isNewRecord && isset($moneyBalance)): ?>
        <?= $form->field($moneyBalance, 'money_amount')->textInput(['maxlength' => true]) ?>
        <?= $form->field($moneyBalance, 'money_bonus_ballance')->textInput(['maxlength' => true]) ?>
    
    <?php endif; ?>


    <?= $form->field($model, 'user_agent')->checkbox() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
