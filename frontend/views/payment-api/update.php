<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentInApi */

$this->title = Yii::t('api', 'Update {modelClass}: ', [
    'modelClass' => 'Payment In Api',
]) . $model->pay_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('api', 'Payment In Apis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pay_id, 'url' => ['view', 'id' => $model->pay_id]];
$this->params['breadcrumbs'][] = Yii::t('api', 'Update');
?>
<div class="payment-in-api-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
