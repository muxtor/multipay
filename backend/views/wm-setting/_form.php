<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WmSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wm-setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'wms_name')->dropDownList($model->getTypeLabels()) ?>

    <?= $form->field($model, 'wms_purse')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wms_rate')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('WmSetting', 'Create') : Yii::t('WmSetting', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
