<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common.models.BonusHelp', 'Bonus Helps');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-help-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('common.models.BonusHelp', 'Create Bonus Help'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bh_id',
            'bh_alias',
            'bh_title',
            'bh_text:ntext',
            'bh_language',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
