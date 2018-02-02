<?php

use common\widgets\News\NewsMain;

?>

<div class="b-tabs-wrapper">
    <div class="b-tabs">
        <div class="tabs">
            <ul class="tabs__caption">
                <li class="active"><span class="b-news-icon"></span><span class="b-text"><?= Yii::t('app', 'НОВОСТИ')?></span></li>
                <li><span class="b-question-icon"></span><span class="b-text"><?= Yii::t('app', 'Как Пользоваться?')?></span></li>
                <li><span class="b-security-icon"></span><span class="b-text"><?= Yii::t('app', 'Безопасность Платежей')?></span></li>
                <li><span class="b-capabilities-icon"></span><span class="b-text"><?= Yii::t('app', 'ВОЗМОЖНОСТИ')?></span></li>
            </ul>
            <div class="b-clear"></div>
            <div class="tabs__content active">
                <?= NewsMain::widget();?>
                <div class="b-clear"></div>
            </div>
            <div class="tabs__content">
                Содержимое второго блока
            </div>
            <div class="tabs__content">
                Содержимое третьего блока
            </div>
            <div class="tabs__content">
                Содержимое четвертого блока
            </div>
        </div>
    </div>
</div>
