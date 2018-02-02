<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\PaymentsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pay_id') ?>

    <?= $form->field($model, 'pay_pc_id') ?>

    <?= $form->field($model, 'pay_user_id') ?>

    <?= $form->field($model, 'pay_status') ?>

    <?= $form->field($model, 'pay_created') ?>

    <?php // echo $form->field($model, 'pay_payed') ?>

    <?php // echo $form->field($model, 'pay_pc_provider_id') ?>

    <?php // echo $form->field($model, 'pay_summ') ?>

    <?php // echo $form->field($model, 'pay_commission') ?>

    <?php // echo $form->field($model, 'pay_currency') ?>

    <?php // echo $form->field($model, 'pay_rate') ?>

    <?php // echo $form->field($model, 'pay_result') ?>

    <?php // echo $form->field($model, 'pay_result_desc') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
