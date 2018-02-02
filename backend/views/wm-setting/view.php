<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WmSetting */

$this->title = $model->wms_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('WmSetting', 'Wm Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wm-setting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('WmSetting', 'Update'), ['update', 'id' => $model->wms_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('WmSetting', 'Delete'), ['delete', 'id' => $model->wms_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('WmSetting', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'wms_id',
            'wms_name',
            'wms_purse',
            'wms_rate',
        ],
    ]) ?>

</div>
