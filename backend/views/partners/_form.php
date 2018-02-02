<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Partners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partners-form">

    <?php $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data',
        'class' => 'form-horizontal, clearfix'
    ]]); ?>

    <?//= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site_link')->textInput(['maxlength' => true]) ?>

    <?php if ($model->image) {
        echo Html::img(Yii::$app->params['frontend.uploads'].'/partners/'.$model->image, ['width' => '150px', 'height' => '100px']);
    } ?>
    <?= $form->field($model, 'image')->fileInput() ?>

    <?//= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sortorder')->textInput() ?>

    <?= $form->field($model, 'css')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
