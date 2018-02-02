<?php
use yii\helpers\Html;
use common\models\Language;

$alt_link = [
    'ru' => "Русский",
    'az' => "Azərbaycan",
    'en' => "English",
];
?>

<ul class="b-lang">
    <?php foreach ($langs as $lang):?>
    <li class="<?= $lang->lang_url == Language::getCurrent()->lang_url ? 'b-active' : ''?>">
              <?= Html::a(
                  Html::img(
                      '/images/'.$lang->lang_url.'.png',
                      ['alt' => array_key_exists($lang->lang_url, $alt_link) ? $alt_link[$lang->lang_url] : '']
                  ).$alt_link[$lang->lang_url], '/'.$lang->lang_url.Yii::$app->getRequest()->getLangUrl()) ?>
          </li>
      <?php endforeach;?>
</ul>