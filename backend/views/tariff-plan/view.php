<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model common\models\TariffPlan */

$this->title = $model->tp_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common.models.TariffPlan', 'Tariff Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-plan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('common.models.TariffPlan', 'Update'), ['update', 'id' => $model->tp_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('common.models.TariffPlan', 'Delete'), ['delete', 'id' => $model->tp_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('common.models.TariffPlan', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tp_id',
            [
                'attribute'=>'title',
                'value'=> $model->title,
            ],
            [
                'attribute'=>'descr',
                'value'=> $model->descr,
            ],
            'tp_transfer_min',
            'tp_transfer_max',
        ],
    ]) ?>
    
    
    <p>
        <?= Html::a(Yii::t('common.models.TariffPlan', 'Create Tariff Rules'), ['create-rules', 'tp_id' =>$model->tp_id ], ['class' => 'btn btn-success']) ?>
    </p>
        <?= GridView::widget([
        'dataProvider' => $rules,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'tpr_id',
            [
                'attribute'=>'tpr_type',
                'value'=> function ($model, $index, $widget) {
                        return $model->getTypeLabel();
                },
            ],
            [
                'attribute'=>'tpr_period',
                'value'=> function ($model, $index, $widget) {
                        return $model->getPeriodLabel();
                },
            ],
//            'tpr_period',
            'tpr_bonus_value',
            [
                'attribute'=>'tpr_active',
                'value'=> function ($model, $index, $widget) {
                        return $model->getStatusLabel();
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['/tariff-plan/update-rules', 'tpr_id' => $model->tpr_id]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'update'
                        ]);
                    },
                    'delete' => function($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['/tariff-plan/delete-rules', 'tpr_id' => $model->tpr_id]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'delete',
                            'data' => [
                                'confirm' => Yii::t('common.models.TariffPlan', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    }
                ]
            ], 
        ],
    ]); ?>

</div>
