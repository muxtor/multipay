<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Payments;


$this->title = Yii::t('view/user/reclame', 'Переводы - MultiPay');
?>
        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-side-bar">
                    <div class="b-side-bar-menu">
                        <?=$this->render('_transfer_menu')?>
                    </div>
                </div>
                <div class="b-categorys">
                    <div class="b-settings-wrapper">
                        <?php if ($payments->pay_status == Payments::PAY_STATUS_NEW):?>
                            <h1><?= Yii::t('transfer', 'Подтверждение получения средств')?></h1>
                        <?php elseif ($payments->pay_status == Payments::PAY_STATUS_DONE):?>
                            <h1><?= Yii::t('transfer', 'Перевод выполнен успешно')?></h1>
                        <?php elseif ($payments->pay_status == Payments::PAY_STATUS_ERROR):?>
                            <h1><?= Yii::t('transfer', 'Ошибка! Отмена транзакции.')?></h1>
                        <?php endif;?>
                        
                        <p><?= Yii::t('transfer', 'Адресат:')?> <?= $payments->pay_pc_provider_account; ?></p>
                        <p><?= Yii::t('transfer', 'Сумма перевода:')?> <?= $payments->pay_summ; ?></p>
                        <p><?= Yii::t('transfer', 'Перевод защищен:')?> <?= $payments->pay_isProtected ? 'да' : 'нет'; ?></p>
                        <?php if ($payments->pay_isProtected):?>
                        <p><?= Yii::t('transfer', 'Код протекции:')?> <?= $payments->pay_protected_code; ?></p>
                        <?php endif;?>
                        <p><?= Yii::t('transfer', 'Комментарий:')?> <?= $payments->pay_comment; ?></p>
                        <br />
                        
                        <?php if ($payments->pay_status == Payments::PAY_STATUS_NEW && $payments->pay_is_payed == Payments::PAY_NOT_PAYED):?>
                        <h1><?= Yii::t('transfer', 'Подтвердить перевод')?></h1>
                        <table class="b-settings-table">
                            <?php $form = ActiveForm::begin(['id' => 'form-transfer-confirm', 'options' => ['class' => 'b-form']]); ?>
                                <tr><td><?= Yii::t('transfer', 'Код подтверждения:')?></td><td><div class="b-field"><input type="text" class="b-input b-sum" name="code" class="b-input" value=""/><div class="b-clear"></div></div></td></tr>
                                <tr>
                                    <td></td>
                                    <td><div class="b-field b-left"><input type="submit" class="b-submit b-green" value="Подтвердить"/></div></td>
                                </tr>
                            <?php ActiveForm::end(); ?>
                                
                        </table>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="b-clear"></div>