<?php

use yii\helpers\Html;
use common\modules\settings\models\Setting;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */

$this->title = Yii::t('settings', 'НАСТРОЙКИ');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="settings-default-index">

    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('settings', 'ТЕРМИНАЛЫ'),
                'content' => $this->render('_terminals', [], false, true),
                'active' => true
            ],
            [
                'label' => Yii::t('settings', 'ДАТЫ'),
                'content' => $this->render('_dates', [], false, true),
                'options' => ['id' => 'set_dates'],
            ],
            [
                'label' => Yii::t('settings', 'СТОИМОСТЬ'),
                'content' => $this->render('_moneys', [], false, true),
                'options' => ['id' => 'set_moneys'],
            ],
        ],
    ]);?>

</div>
