<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Country;
use yii\helpers\ArrayHelper;
use common\models\Providers;

/* @var $this yii\web\View */
/* @var $model common\models\Providers */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Provider',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Providers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="static-page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
$form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2 control-label',
                'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);

?>
<div class="form-group">
    
    <?= $form->field($model, 'description')->textInput() ?>
    <?= $form->field($model, 'pc_id')->textInput() ?>
    <?= $form->field($model, 'country_id')->dropDownList(ArrayHelper::map(Country::find()->all(), 'id', 'name'), ['prompt' => 'Choose country ...']) ?>
    <?= $form->field($model, 'regexp')->textInput() ?>
    <?= $form->field($model, 'logofile[]')->fileInput(['multiple' => false])->hint($model->logo ? 'Current: '.Html::img(Yii::$app->params['frontend.uploads'].'/providers-logo/'.$model->logo, ['alt' => $model->name]) : '') ?>
    <?= $form->field($model, 'sidebarlogofile[]')->fileInput(['multiple' => false])
        ->hint($model->logo_sidebar ? 'Для родительских категорий. Загруженное изображение: '.Html::img(Yii::$app->params['frontend.uploads'].'/providers-logo/'.$model->logo_sidebar, ['alt' => $model->name]) : 'Для родительских категорий.') ?>
    <?= $form->field($model, 'show_on_start')->checkbox([], false)->hint(Yii::t('providers/model', 'Для родительских категорий. Отображать в главном меню или нет.')); ?>
    <?= $form->field($model, 'comission_percent')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(Providers::getStatusLabels()) ?>
    <?= $form->field($model, 'currency')->dropDownList(Providers::getCurrencyLabels()) ?>
    <?= $form->field($model, 'pay_sum_min')->textInput() ?>
    <?= $form->field($model, 'pay_sum_max')->textInput() ?>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    
</div>

</div>
