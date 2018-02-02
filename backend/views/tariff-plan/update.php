<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TariffPlan */
if ($view == '_form') {
    $this->title = Yii::t('common.models.TariffPlan', 'Update {modelClass}: ', [
        'modelClass' => 'Tariff Plan',
    ]) . ' ' . $model->tp_id;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common.models.TariffPlan', 'Tariff Plans'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->tp_id, 'url' => ['view', 'id' => $model->tp_id]];
    $this->params['breadcrumbs'][] = Yii::t('common.models.TariffPlan', 'Update');
} else {
    $this->title = Yii::t('common.models.TariffPlan', 'Update {modelClass}: ', [
        'modelClass' => 'Tariff Plan Rules',
    ]) . ' ' . $model->tpr_id;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common.models.TariffPlan', 'Tariff Plans').$model->tpr_tp_id, 'url' => ['view', 'id' => $model->tpr_tp_id]];
    $this->params['breadcrumbs'][] = Yii::t('common.models.TariffPlan', 'Update Rules'.$model->tpr_id);
    
}
    
?>
<div class="tariff-plan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render($view, [
        'model' => $model,
        'languages' => $languages,
    ]) ?>

</div>
