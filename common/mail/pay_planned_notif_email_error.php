<?php


/**
 * @var $model \common\models\Payments
 */
?>
<p><?= Yii::t('payments/mail', 'Здравствуйте, уважаемый клиент!', [], $lang)?></p>
<p><?= Yii::t('payments/mail', 'Ваш запланированный платеж не был оплачен.', [], $lang)?></p>
<p><?= Yii::t('payments/mail', 'Причина: недостаточно средств.', [], $lang)?></p>
<p><?= Yii::t('payments/mail', 'Детали платежа:', [], $lang)?></p>
<p><?= Yii::t('payments/mail', 'Аккаунт:', [], $lang). ' ' . $model->pay_pc_provider_account?> . '.'</p>
<p><?= Yii::t('payments/mail', 'Получатель:', [], $lang). ' ' . $model->provider->name . '.'?></p>
<p><?= Yii::t('payments/mail', 'Сумма:', [], $lang). ' ' . $model->pay_summ_from . $model->pay_currency . '.'?></p>