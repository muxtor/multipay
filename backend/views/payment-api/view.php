<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentInApi */

$this->title = $model->pay_id;
$this->params['breadcrumbs'][] = ['label' => 'Payment In Apis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-in-api-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pay_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pay_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pay_id',
            'pay_created',
            'pay_updated',
            'agent_id',
            'user_id',
            'pay_sum',
            'api_id',
        ],
    ]) ?>

</div>
