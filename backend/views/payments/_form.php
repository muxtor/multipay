<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Payments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pay_pc_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_user_id')->textInput() ?>

    <?= $form->field($model, 'pay_status')->textInput() ?>

    <?= $form->field($model, 'pay_created')->textInput() ?>

    <?= $form->field($model, 'pay_payed')->textInput() ?>

    <?= $form->field($model, 'pay_pc_provider_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_summ')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_commission')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_result')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_result_desc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
