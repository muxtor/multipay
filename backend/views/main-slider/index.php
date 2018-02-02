<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('MainSlider', 'Main Sliders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-slider-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('MainSlider', 'Create Main Slider'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('MainSlider', 'Изменить время сменя слайдов'), ['/slider-interval/update'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'slider_id',
            'slider_title',
//            'slider_image_url:url',
            [
                'attribute' => 'slider_image_url',
                'format' => ['image'],
                'value' => function ($model, $index, $widget) {
                        return Yii::$app->params['frontend.uploads'].'/main-slider/' . $model->slider_image_url;
                },
            ],
            'slider_text',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
