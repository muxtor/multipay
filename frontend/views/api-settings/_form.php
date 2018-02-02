<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Api */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$form = ActiveForm::begin([
    'options' => ['class' => 'form_complete'],
]);
?>
<table class="b-settings-table">
    <tr>
        <td><?= $model->getAttributeLabel('api_title')?></td>
        <td>
            <div class="b-field">
                <?= Html::activeTextInput($model, 'api_title', ['class' => 'b-input']) ?>
            </div>
            <div class="b-field"><?= Html::error($model, 'api_title') ?></div>
        </td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel('api_description')?></td>
        <td>
            <div class="b-field">
                <?= Html::activeTextInput($model, 'api_description', ['class' => 'b-input']) ?>
            </div>
            <div class="b-field"><?= Html::error($model, 'api_description') ?></div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="b-field b-right">
                <input type="submit" class="b-submit b-green" value="<?=Yii::t('api','Сохранить');?>"/>
            </div>
        </td>
    </tr>
</table>
<div class="b-clear"></div>

<?php ActiveForm::end(); ?>