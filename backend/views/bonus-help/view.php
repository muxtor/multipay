<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BonusHelp */

$this->title = $model->bh_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common.models.BonusHelp', 'Bonus Helps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-help-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('common.models.BonusHelp', 'Update'), ['update', 'id' => $model->bh_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('common.models.BonusHelp', 'Delete'), ['delete', 'id' => $model->bh_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('common.models.BonusHelp', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bh_id',
            'bh_alias',
            'bh_title',
            'bh_text:ntext',
            'bh_language',
        ],
    ]) ?>

</div>
