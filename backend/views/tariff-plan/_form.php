<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TariffPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tariff-plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tp_transfer_min')->textInput() ?>

    <?= $form->field($model, 'tp_transfer_max')->textInput() ?>
    
    <?php 
    if (!empty($languages)) {
        foreach ($languages as $language) {
            echo $language->lang_local;
            echo $form->field($model->translate($language->lang_local), "[$language->lang_local]title")->textInput();
            echo $form->field($model->translate($language->lang_local), "[$language->lang_local]descr")->textarea();
        }
    }
    ?>
        

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('common.models.TariffPlan', 'Create') : Yii::t('common.models.TariffPlan', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
