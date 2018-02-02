<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MainSlider */

$this->title = $model->slider_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('MainSlider', 'Main Sliders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-slider-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('MainSlider', 'Update'), ['update', 'id' => $model->slider_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('MainSlider', 'Delete'), ['delete', 'id' => $model->slider_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('MainSlider', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'slider_id',
            'slider_title',
//            'slider_image_url:url',
            [
                'attribute'=>'slider_image_url',
                'value'=> Yii::$app->params['frontend.uploads'].'/main-slider/' . $model->slider_image_url,
                'format' => ['image'],
            ],
            'slider_text',
        ],
    ]) ?>

</div>
