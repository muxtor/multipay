<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\search\ApiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="api-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'api_id') ?>

    <?= $form->field($model, 'api_title') ?>

    <?= $form->field($model, 'api_description') ?>

    <?= $form->field($model, 'api_key') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('api', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('api', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
