<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Api */

$this->title = $model->api_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('api', 'Apis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('api', 'Update'), ['update', 'id' => $model->api_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('api', 'Delete'), ['delete', 'id' => $model->api_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('api', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'api_id',
            'api_title',
            'api_description',
            'api_key',
        ],
    ]) ?>

</div>
