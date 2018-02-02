<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WmSetting */

$this->title = Yii::t('WmSetting', 'Update {modelClass}: ', [
    'modelClass' => 'Wm Setting',
]) . ' ' . $model->wms_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('WmSetting', 'Wm Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->wms_id, 'url' => ['view', 'id' => $model->wms_id]];
$this->params['breadcrumbs'][] = Yii::t('WmSetting', 'Update');
?>
<div class="wm-setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
