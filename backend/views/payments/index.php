<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Payments;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Payments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // echo Html::a(Yii::t('app', 'Create Payments'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'pay_id',
            'pay_pc_id',
            'pay_user_id',
            'pay_provider_id',
            'pay_pc_provider_account',
            [
                'attribute' => 'pay_check_status',
                'value' => function ($model) {
                    return $model->checkStatusLabel();
                },
                'filter' => Payments::checkStatusLabels(),
            ],
            [
                'attribute' => 'pay_check_result',
                'value' => function ($model) {
                    return $model->checkResultLabel();
                },
                'filter' => Payments::checkResultLabels(),
            ],
            'pay_check_result_desc',
            [
                'attribute' => 'pay_is_checked',
                'value' => function ($model) {
                    return $model->isChecked();
                },
                'filter' => Payments::isCheckedLabels(),
            ],
            [
                'attribute' => 'pay_status',
                'value' => function ($model) {
                    return $model->payStatusLabel();
                },
                'filter' => Payments::payStatusLabels(),
            ],
            [
                'attribute' => 'pay_result',
                'value' => function ($model) {
                    return $model->payResultLabel();
                },
                'filter' => Payments::payResultLabels(),
            ],
            'pay_result_desc',
            [
                'attribute' => 'pay_is_payed',
                'value' => function ($model) {
                    return $model->isPayed();
                },
                'filter' => Payments::isPayedLabels(),
            ],
            'pay_created',
            'pay_payed',
            // 'pay_pc_provider_id',
            'pay_summ',
            'pay_summ_from',
            'pay_commission',
            'pay_currency',
            'pay_system',
            'pay_rate',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
