<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Payments;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="payments-index">

<?php Pjax::begin(['id' => 'myPjax']);?>

    <?= GridView::widget([
        'id' => 'myGrid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'pay_id',
            'pay_pc_id',
            [
                'attribute' => 'pay_user_id',
//                'filter' => false,
            ],
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
            'pay_commission',
//             'pay_currency',
            'pay_rate',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<?php Pjax::end();?>

</div>
