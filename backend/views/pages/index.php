<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Static Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Static Page'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'export' => false,
        'hover' => true,
        'columns' => [
            'page_alias',
            'page_text:ntext',
            'page_language',
            'page_show',

            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>
