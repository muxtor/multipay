<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BonusHelp */

$this->title = Yii::t('common.models.BonusHelp', 'Create Bonus Help');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common.models.BonusHelp', 'Bonus Helps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-help-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
