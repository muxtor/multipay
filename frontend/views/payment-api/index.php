<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\ApiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('api', 'Операции API');
$lang = \common\models\Language::getCurrent();
?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-content-full-width">
            <h1><?=Yii::t('payments/model', 'Операции API')?></h1>
            <div class="b-content-full-width-wrap" style="margin-top: -20px;">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'summary'=>'',
                    'columns' => [
                        'pay_id',
                        'pay_created',
                        [
                            'attribute' => 'api_id',
                            'value' => 'api.api_title',
                        ],
                        [
                            'attribute' => 'user_id',
                            'value' => 'user.phone',
                        ],
                        'pay_sum',
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
        <br />
    </div>
</div>
