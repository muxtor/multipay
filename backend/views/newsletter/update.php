<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Newsletter */

$this->title = Yii::t('newsletter/model', 'Update {modelClass}: ', [
    'modelClass' => 'Newsletter',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('newsletter/model', 'Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('newsletter/model', 'Update');
?>
<div class="newsletter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
