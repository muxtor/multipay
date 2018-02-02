<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\ApiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('api', 'Apis');
$lang = \common\models\Language::getCurrent();
?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-content-full-width">
            <h1><?=Yii::t('payments/model', 'API')?></h1>
            <div class="b-content-full-width-wrap" style="margin-top: -20px;">
                <div class="b-history-filter b-table-filter" style="height: 40px;">
                    <div class="b-history-form">
                        <div class="b-right">
                            <input style="margin-bottom: 10px;" type="submit" value="<?=Yii::t('api', 'Create Api')?>" class="b-submit b-bonus-submit" onclick="location.href='/api-settings/create'"/>
                        </div>
                    </div>
                </div>
                    <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'summary'=>'',
                        'columns' => [
                            'api_id',
                            'api_title',
                            'api_description',
                            'api_key',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>

                <p style="margin-left: 10px;">
                    Для подключения апи пополнения баланса необходимо выполнить<br> POST запрос на URL:
                    <?=Url::base(true);?>/api/balance <br>
                    и передать параметры:<br><br>
                    <b>sum</b> - Сумма пополнения баланса (пример: 5, 45, 5.23, 12.78)<br>
                    <b>login</b> - Логин пользователя(телефон), которому пополняем баланс (пример: +994-333-333-3333, 9943333333333)<br>
                    <b>key</b> - Key берется из таблицы выше<br>
                    <b>id</b> - Id берется из таблицы выше<br>
                    <br><br>
                    При успехе ответ будет 1, при ошибке 0.
                </p>
            </div>
        </div>
        <br />
    </div>
</div>

