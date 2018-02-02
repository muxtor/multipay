<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Terminal */

$this->title = Yii::t('terminal/views', 'Update {modelClass}: ', [
    'modelClass' => 'Terminal',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('terminal/views', 'Terminals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('terminal/views', 'Update');
?>
<div class="terminal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
