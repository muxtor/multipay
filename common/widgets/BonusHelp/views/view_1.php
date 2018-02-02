<?php
use yii\helpers\Html;
?>

<?php if ($model) : ?>
<?php // \yii\helpers\VarDumper::dump($news, 10, 10); die(); ?>
        <div class="b-information">
            <h2><?=$model->bh_title?></h2>
            <p><?=$model->bh_text?></p>
        </div>
<?php else : ?>
    <?= Yii::t('widget.BonusHelp', 'Упс!'); ?>
<?php endif; ?>