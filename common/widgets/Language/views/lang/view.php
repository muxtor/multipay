<?php
use yii\helpers\Html;
?>

<div class="dropdown" style='float: right; margin-top: 8px;' id="languageMenuSelect">
  <button class="btn btn-default dropdown-toggle" type="button" id="languageMenu" data-toggle="dropdown" aria-expanded="true">
    <?= $current->lang_name;?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu" aria-labelledby="languageMenu">
	  <?php foreach ($langs as $lang):?>
            <li role="presentation">
                <?= Html::a($lang->lang_name, '/'.$lang->lang_url.Yii::$app->getRequest()->getLangUrl(), ['role' => 'menuitem', 'tabindex' => '-1']) ?>
            </li>
        <?php endforeach;?>
  </ul>
</div>