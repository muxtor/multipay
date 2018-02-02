<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = $model->page_title;
?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-pages-nav">
            <a class="b-pay-search-btn" href="/payments/payment-search">
                <span><?= Yii::t('main', 'Поиск платежа'); ?></span>
                <?= Yii::t('main', 'Проверка статуса<br />совершенного платежа'); ?>
            </a>
            <a class="b-terminal-maps-btn" href="#"><?= Yii::t('main', 'Карта/список<br />терминалов'); ?></a>
        </div>
        
        <div class="b-pages">
            <h1><?= $this->title ?></h1>
            <div><?= $model->page_text ?></div>
        </div>
        <div class="b-clear"></div>
    </div>
</div>
