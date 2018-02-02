<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MainSlider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="main-slider-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'form-horizontal, clearfix'
        ]
    ]); ?>

    <?= $form->field($model, 'slider_title')->textInput(['maxlength' => true]) ?>

    <?php if ($model->slider_image_url) { 
        echo Html::img('/uploads/main-slider/'.$model->slider_image_url, ['width' => '150px', 'height' => '100px']);
    } ?>
    <?= $form->field($model, 'slider_image_url')->fileInput(['multiple' => false]) ?>

    <?= $form->field($model, 'slider_text')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('MainSlider', 'Create') : Yii::t('MainSlider', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
