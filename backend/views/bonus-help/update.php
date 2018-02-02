<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BonusHelp */

$this->title = Yii::t('common.models.BonusHelp', 'Update {modelClass}: ', [
    'modelClass' => 'Bonus Help',
]) . ' ' . $model->bh_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common.models.BonusHelp', 'Bonus Helps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bh_id, 'url' => ['view', 'id' => $model->bh_id]];
$this->params['breadcrumbs'][] = Yii::t('common.models.BonusHelp', 'Update');
?>
<div class="bonus-help-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
