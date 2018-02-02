<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('faq/model', 'Помощь');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('faq/model', 'Создать'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'export' => false,
        'hover' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'faq_id',
            'faq_title',
            'faq_text:ntext',
            'faq_language',
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>
