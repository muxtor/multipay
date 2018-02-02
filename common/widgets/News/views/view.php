<?php

use yii\helpers\Html;

?>

<?php if ($news) : ?>
    <?php foreach ($news as $model) : ?>
        <div class="b-news">
            <?$data = explode('-', $model->news_date);?>
            <span class="b-date"><?=$data[2] . '.' . $data[1] . '.' . $data[0]?> </span>
            <h3><?=$model->news_title?></h3>
            <p><?=$model->news_description?></p>
            <?= Html::a(Yii::t('widget.news', 'Подробнее'), ['/news/view', 'alias' => $model->news_alias], ['class' => 'b-read-more']); ?>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <?= Yii::t('widget.news', 'Нет ни одной новости'); ?>
<?php endif; ?>