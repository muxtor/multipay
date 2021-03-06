<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\search\PaymentInApiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-in-api-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pay_id') ?>

    <?= $form->field($model, 'pay_created') ?>

    <?= $form->field($model, 'pay_updated') ?>

    <?= $form->field($model, 'agent_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'pay_sum') ?>

    <?php // echo $form->field($model, 'api_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('api', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('api', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
