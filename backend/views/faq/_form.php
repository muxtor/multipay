<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\models\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Faq;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'faq_title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'faq_text')->textarea();
    ?>

    <?= $form->field($model, 'faq_language')->dropDownList(ArrayHelper::map(Language::find()->all(), 'lang_url', 'lang_name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
