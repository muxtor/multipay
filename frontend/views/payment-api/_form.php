<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentInApi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-in-api-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pay_created')->textInput() ?>

    <?= $form->field($model, 'pay_updated')->textInput() ?>

    <?= $form->field($model, 'agent_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'pay_sum')->textInput() ?>

    <?= $form->field($model, 'api_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('api', 'Create') : Yii::t('api', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
