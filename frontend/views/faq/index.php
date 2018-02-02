<?php 
$this->title = Yii::t('faq', 'Помощь');
?>

<div class="b-content-wrapper">
    <div class="b-content">

        
                <div class="b-pages-nav">
                    <a class="b-pay-search-btn" href="/payments/payment-search">
                        <span><?=Yii::t('main', 'Поиск платежа');?></span>
                        <?=Yii::t('main', 'Проверка статуса<br />совершенного платежа');?>
                    </a>
                    <a class="b-terminal-maps-btn" href="#"><?=Yii::t('main', 'Карта/список<br />терминалов');?></a>
                </div>
        
        
                <div class="b-pages">
                    <h1><?=$this->title; ?></h1>
                    <?php if ($models) : ?>
                        <?php foreach ($models as $model) : ?>
                            <div class="b-spoiler">
                                <a class="b-spoiler-nav" href="javascript:void(0);"></a>
                                <h2><?=$model->faq_title?></h2>
                                <div class="b-spoiler-text">
                                    <p><?=$model->faq_text?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?= Yii::t('app', 'Нет данных'); ?>
                    <?php endif; ?>
                </div>
                <div class="b-clear"></div>        
        
        
        
        
        
        
        
    </div>
</div>