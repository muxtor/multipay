<?php

use yii\helpers\Html;
use common\modules\settings\models\Setting;

?>

<?= Html::beginForm('', 'post', ['id' => 'system-settings']); ?>
<p><?= Yii::t('settings', 'СКОЛЬКО_МАНАТ_ЗА_ЕДИНИЦУ_БАЛАНСА') ?></p>
<?= Html::textInput('currency' . '-' . 'AZN_to_balance' . '-' . Setting::TYPE_FLOAT,
    Yii::$app->settings->get('AZN_to_balance', 'currency')) ?>
<p><?= Yii::t('settings', 'СКОЛЬКО_РУБЛЕЙ_ЗА_ЕДИНИЦУ_БАЛАНСА') ?></p>
<?= Html::textInput('currency' . '-' . 'RUB_to_balance' . '-' . Setting::TYPE_FLOAT,
    Yii::$app->settings->get('RUB_to_balance', 'currency')) ?>
<p><?= Yii::t('settings', 'СКОЛЬКО_ДОЛЛАРОВ_ЗА_ЕДИНИЦУ_БАЛАНСА') ?></p>
<?= Html::textInput('currency' . '-' . 'USD_to_balance' . '-' . Setting::TYPE_FLOAT,
    Yii::$app->settings->get('USD_to_balance', 'currency')) ?>
<p><?= Yii::t('settings', 'МИНИМАЛЬНАЯ_СУММА_ОБМЕНА_БОНУСОВ') ?></p>
<?= Html::textInput('currency' . '-' . 'min_bonus_transfer' . '-' . Setting::TYPE_FLOAT,
    Yii::$app->settings->get('min_bonus_transfer', 'currency')) ?>
<p><?= Yii::t('settings', 'СТОИМОСТЬ_1_SMS_ДЛЯ_ЗАПЛАНИРОВАННЫХ ПЛАТЕЖЕЙ') ?></p>
<?= Html::textInput('currency' . '-' . 'sms_pay_planned' . '-' . Setting::TYPE_FLOAT,
    Yii::$app->settings->get('sms_pay_planned', 'currency')) ?>
<p><?= Yii::t('settings', 'СТОИМОСТЬ_ПОДПИСКИ_SMS_ОПОВЕЩЕНИЯ_О_ИЗМЕНЕНИИИ_БАЛЛАНСА') ?></p>
<?= Html::textInput('currency' . '-' . 'sms_change_balance' . '-' . Setting::TYPE_FLOAT,
    Yii::$app->settings->get('sms_change_balance', 'currency')) ?>
<br>
<?= Html::input('submit', '', Yii::t('settings', 'СОХРАНИТЬ'), ['class' => 'btn btn-primary']); ?>
<?= Html::endForm(); ?>
