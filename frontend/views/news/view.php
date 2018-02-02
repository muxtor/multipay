<?php 
$this->title = Yii::t('news', $model->news_title);
?>

<div class="b-content-wrapper">
    <div class="b-content">

        
                <div class="b-pages-nav">
                    <a class="b-pay-search-btn" href="#">
                        <span>Поиск платежа</span>
                        Проверка статуса<br />совершенного платежа
                    </a>
                    <a class="b-terminal-maps-btn" href="#">Карта/список<br />терминалов</a>
                </div>
        
        
                <div class="b-pages">
                    <h1><?=$this->title; ?></h1>
                    <p><?=$model->news_text?></p>
                </div>
                <div class="b-clear"></div>        
        
        
        
        
        
        
        
    </div>
</div>
