<?php

use kartik\tree\TreeView;
use kartik\tree\TreeViewInput;
use common\models\Providers;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Providers');
$this->params['breadcrumbs'][] = $this->title;
?>
 
<?= TreeView::widget([
    // single query fetch to render the tree
    // use the Product model you have in the previous step
    'query' => Providers::find()->addOrderBy('root, lft'), 
    'headingOptions' => ['label' => 'Providers'],
    'rootOptions' => ['label'=>'<span class="text-primary">All categories</span>'],
    'fontAwesome' => false,     // optional
    'isAdmin' => false,         // optional (toggle to enable admin mode)
    'displayValue' => 1,        // initial display value
    'softDelete' => false,       // defaults to true
    'cacheSettings' => [        
        'enableCache' => true   // defaults to true
    ],
    'iconEditSettings'=> [
        'show' => 'list',
        'listData' => [
            'folder' => 'Folder',
            'file' => 'File',
//            'mobile' => 'Phone',
//            'bell' => 'Bell',
        ]
    ],
]);
?>
<div class="providers-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'name',
            'description',
            'pc_id',
            [
                'attribute' => 'show_on_start',
                'value' => function ($model) {
                    return $model->show_on_start ? 'Да' : 'Нет';
                },
            ],
            [
                'attribute' => 'country_id',
                'value' => function ($model) {
                    return $model->country->name;
                }
            ],
            'regexp',
            'comission_percent',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusLabel();
                },
                'filter' => Providers::getStatusLabels(),
            ],
            [
                'attribute' => 'currency',
                'value' => function ($model) {
                    return $model->currencyLabel();
                },
                'filter' => Providers::getCurrencyLabels(),
            ],
            [
                'attribute' => 'logo',
                'value' => function ($model) {
                    if ($model->logo) {
                        return Html::img(Yii::$app->params['frontend.uploads'].'/providers-logo/'.$model->logo, ['alt' => $model->name]);
                    } else {
                        return '-';
                    }
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'logo_sidebar',
                'value' => function ($model) {
                    if ($model->logo_sidebar) {
                        return Html::img(Yii::$app->params['frontend.uploads'].'/providers-logo/'.$model->logo_sidebar, ['alt' => $model->name]);
                    } else {
                        return '-';
                    }
                },
                'format' => 'raw'
            ],
            'pay_sum_min',
            'pay_sum_max',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]); ?>

</div>
