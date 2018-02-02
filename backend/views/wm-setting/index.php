<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('WmSetting', 'Wm Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wm-setting-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('WmSetting', 'Create Wm Setting'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'wms_id',
            'wms_name',
            'wms_purse',
            'wms_rate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
