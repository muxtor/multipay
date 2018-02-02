<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common.models.TariffPlan', 'Tariff Plans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-plan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('common.models.TariffPlan', 'Create Tariff Plan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tp_id',
            'title',
            'tp_transfer_min',
            'tp_transfer_max',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
