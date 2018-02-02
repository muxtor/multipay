<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TariffPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tariff-plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tpr_type')->dropDownList($model->getTypeLabels()) ?>
    <?= $form->field($model, 'tpr_period')->dropDownList($model->getPeriodLabels()) ?>

    <?= $form->field($model, 'tpr_bonus_value')->textInput() ?>
    
    <?= $form->field($model, 'tpr_active')->dropDownList($model->getStatusLabels()) ?>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('common.models.TariffPlan', 'Create') : Yii::t('common.models.TariffPlan', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
