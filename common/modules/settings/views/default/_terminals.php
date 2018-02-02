<?php

use yii\helpers\Html;
use common\modules\settings\models\Setting;

?>

<?= Html::beginForm('', 'post', ['id' => 'system-settings']); ?>
<p><?= Yii::t('settings', 'ID_АГЕНТА') ?></p>
<?= Html::textInput('currency' . '-' . 'dealer_pc' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('dealer_pc', 'currency')) ?>
<p><?= Yii::t('settings', 'ЛОГИН_ПОЛЬЗОВАТЕЛЯ') ?></p>
<?= Html::textInput('currency' . '-' . 'login_pc' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('login_pc', 'currency')) ?>
<p><?= Yii::t('settings', 'ПАРОЛЬ_ПОЛЬЗОВАТЕЛЯ') ?></p>
<?= Html::textInput('currency' . '-' . 'password_pc' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('password_pc', 'currency')) ?>
<p><?= Yii::t('settings', 'НОМЕР_ТЕРМИНАЛА_AZN') ?></p>
<?= Html::textInput('currency' . '-' . 'AZN_terminal' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('AZN_terminal', 'currency')) ?>
<p><?= Yii::t('settings', 'КОД_ВАЛЮТЫ_AZN') ?></p>
<?= Html::textInput('currency' . '-' . 'AZN_currency_ISO' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('AZN_currency_ISO', 'currency')) ?>
<p><?= Yii::t('settings', 'НОМЕР_ТЕРМИНАЛА_RUB') ?></p>
<?= Html::textInput('currency' . '-' . 'RUB_terminal' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('RUB_terminal', 'currency')) ?>
<p><?= Yii::t('settings', 'КОД_ВАЛЮТЫ_RUB') ?></p>
<?= Html::textInput('currency' . '-' . 'RUB_currency_ISO' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('RUB_currency_ISO', 'currency')) ?>
<p><?= Yii::t('settings', 'НОМЕР_ТЕРМИНАЛА_USD') ?></p>
<?= Html::textInput('currency' . '-' . 'USD_terminal' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('USD_terminal', 'currency')) ?>
<p><?= Yii::t('settings', 'КОД_ВАЛЮТЫ_USD') ?></p>
<?= Html::textInput('currency' . '-' . 'USD_currency_ISO' . '-' . Setting::TYPE_STRING,
    Yii::$app->settings->get('USD_currency_ISO', 'currency')) ?>
<br>
<?= Html::input('submit', '', Yii::t('settings', 'СОХРАНИТЬ'), ['class' => 'btn btn-primary']); ?>
<?= Html::endForm(); ?>
