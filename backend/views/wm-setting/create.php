<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WmSetting */

$this->title = Yii::t('WmSetting', 'Create Wm Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('WmSetting', 'Wm Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wm-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
