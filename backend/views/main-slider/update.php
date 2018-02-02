<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MainSlider */

$this->title = 'Update Main Slider: ' . ' ' . $model->slider_id;
$this->params['breadcrumbs'][] = ['label' => 'Main Sliders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->slider_id, 'url' => ['view', 'id' => $model->slider_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="main-slider-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
