<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\PayPlanned */

?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-content-full-width">
            <h1><?= Yii::t('payment', 'ЗАПЛАНИРОВАННЫЕ_ПЛАТЕЖИ')?></h1>
            <div class="b-content-full-width-templates">
                <div class="b-planed-nav-wrapper">
                    <ul class="b-planed-nav">
                        <li><?= Html::a(Yii::t('payment', 'ONCE'), 'javascript:void(0);', ['id' => 'planned_once', 'class' => 'b-active', 'data' => '1'])?></li>
                        <li><?= Html::a(Yii::t('payment', 'WEEK'), 'javascript:void(0);', ['id' => 'planned_week', 'data' => '7'])?></li>
                        <li><?= Html::a(Yii::t('payment', 'MONTH'), 'javascript:void(0);', ['id' => 'planned_month', 'data' => '31'])?></li>
                    </ul>
                </div>
                <div id="planned-payments-list">
                    <?= $this->render('index_once', ['models' => $models]);?>
                </div>
                <div class="b-clear"></div>
            </div>
        </div>
        <br>
    </div>
    <div class="b-clear"></div>
    <?= \frontend\widgets\Tabs\TabsWidget::widget();?>
    <?= \frontend\widgets\Logos\LogosWidget::widget();?>
</div>
