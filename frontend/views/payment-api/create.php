<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PaymentInApi */

$this->title = Yii::t('api', 'Create Payment In Api');
$this->params['breadcrumbs'][] = ['label' => Yii::t('api', 'Payment In Apis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-in-api-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
