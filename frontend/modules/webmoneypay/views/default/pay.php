<!--<div class="webmoneypay-default-index">
    <form id="pay" name="pay" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp"> 
    <p>пример платежа через сервис Web Merchant Interface</p> <p>заплатить 101 WMR...</p> 
    <p>
      <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="101.0">
      <input type="hidden" name="LMI_PAYMENT_DESC" value="тестовый платеж">
      <input type="hidden" name="LMI_PAYMENT_NO" value="1">
      <input type="hidden" name="LMI_PAYEE_PURSE" value="R245224718562">
      <input type="hidden" name="LMI_SIM_MODE" value="2"> 
    </p> 
    <p><input type="submit" value="submit"></p> 
    </form>
</div>-->
<!--<script type="text/javascript">
    $("#pay").submit();
</script>-->

<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Оплата с помощью сервиса Web Money';

?>
<div class="b-content-wrapper">
    <div class="b-content">
        Подождите...перенаправление на WEB MONEY.

        <div>
            <?php
            $form = ActiveForm::begin([
                    'id' => 'payment',
                    'action' => 'https://merchant.webmoney.ru/lmi/payment.asp',
            ]);

            ?>
            <?= Html::hiddenInput('LMI_PAYEE_PURSE', $wmpurse->wms_purse) ?>
            <?= Html::hiddenInput('LMI_PAYMENT_AMOUNT', $model->pay_summ_from) ?>
            <?= Html::hiddenInput('LMI_PAYMENT_NO', $model->pay_id) ?>
            <?php // echo Html::hiddenInput('LMI_PAYMENT_DESC', $model->pay_comment) ?> 
            <?= Html::hiddenInput('LMI_PAYMENT_DESC_BASE64', base64_encode($model->pay_comment)) ?>
            <?php // echo Html::hiddenInput('LMI_RESULT_URL', Yii::$app->urlManager->createAbsoluteUrl('/webmoneypay/default/result')) ?>
            <?php // echo Html::hiddenInput('LMI_SUCCESS_URL', Yii::$app->urlManager->createAbsoluteUrl('/webmoneypay/default/success')) ?>
            <?= Html::hiddenInput('LMI_SUCCESS_METHOD', 1) ?>
            <?php // echo Html::hiddenInput('LMI_FAIL_URL', Yii::$app->urlManager->createAbsoluteUrl('/webmoneypay/default/fail')) ?>
            <?= Html::hiddenInput('LMI_FAIL_METHOD', 1) ?>
            <!--test method-->
            <?= Html::hiddenInput('LMI_SIM_MODE', 0) ?>
            <?php // Html::hiddenInput('LMI_SIM_MODE', 0) ?>
            <!--<input type="submit" value="submit">-->

            <?php ActiveForm::end(); ?>


        </div>
    </div>
</div>
<?php $this->registerJs('$("#payment").submit();', \yii\web\View::POS_END); ?>
