<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Terminal */

$this->title = Yii::t('terminal/views', 'Create Terminal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('terminal/views', 'Terminals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="terminal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
