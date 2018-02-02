<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TariffPlan */

$this->title = Yii::t('common.models.TariffPlan', 'Create Tariff Plan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common.models.TariffPlan', 'Tariff Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render($view, [
        'model' => $model,
        'languages' => $languages,
    ]) ?>

</div>
