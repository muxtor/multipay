<?php

use yii\helpers\Html;
use common\modules\settings\models\Setting;

?>

<?= Html::beginForm('', 'post', ['id' => 'system-settings']); ?>
<p><?= Yii::t('settings', 'ЧАСЫ_ЖИЗНИ_СЧЕТА') ?></p>
<?= Html::textInput('currency' . '-' . 'invoice_lifetime_hours' . '-' . Setting::TYPE_INTEGER,
    Yii::$app->settings->get('invoice_lifetime_hours', 'currency')) ?>
<p><?= Yii::t('settings', 'ДНИ_БЕСПЛАТНОЙ_СМС_ПОДПИСКИ_ОБ_ИЗМЕНЕНИИИ_БАЛЛАНСА') ?></p>
<?= Html::textInput('currency' . '-' . 'sms_notif_balance_free_period' . '-' . Setting::TYPE_INTEGER,
    Yii::$app->settings->get('sms_notif_balance_free_period', 'currency')) ?>
<br>
<?= Html::input('submit', '', Yii::t('settings', 'СОХРАНИТЬ'), ['class' => 'btn btn-primary']); ?>
<?= Html::endForm(); ?>
