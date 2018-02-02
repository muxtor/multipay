<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Newsletter;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create News'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'export' => false,
        'hover' => true,
        'columns' => [
            'news_title',
            'news_alias',
            'news_date',
            'news_description',
            'news_language',
            'news_show',
            [
                'header' => Yii::t('news', 'Дата последней рассылки'),
                'value' => function ($model) {
                    return Newsletter::getLastDate($model->news_id);
                }
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete} {sms} {email}',
                'buttons' => [
                    'sms' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-earphone"></span>',
                            ['/news/send', 'id' => $model->news_id, 'type' => Newsletter::VIA_SMS],
                            [
                                'title' => Yii::t('news', 'СМС рассылка'),
                                'data' => [
                                    'confirm' => Yii::t('news', 'ОТПРАВЛЯЕМ СМС РАССЫЛКУ?'),
//                                    'method' => 'post',
                                ],
                            ]);
                    },
                    'email' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-envelope"></span>',
                            ['/news/send', 'id' => $model->news_id, 'type' => Newsletter::VIA_EMAIL],
                            [
                                'title' => Yii::t('news', 'Email рассылка'),
                                'data' => [
                                    'confirm' => Yii::t('news', 'ОТПРАВЛЯЕМ EMAIL РАССЫЛКУ?'),
//                                    'method' => 'post',
                                ],
                            ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>
