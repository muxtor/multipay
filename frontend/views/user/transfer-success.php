<?php
/* @var $this yii\web\View */
use yii\helpers\Html;


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
                        <h1><?= Yii::t('transfer', 'Перевод выполнен успешно')?></h1>
                        <p><?= Yii::t('transfer', 'Адресат:')?> <?= $payments->pay_pc_provider_account; ?></p>
                        <p><?= Yii::t('transfer', 'Сумма перевода:')?> <?= $payments->pay_summ; ?></p>
                        <p><?= Yii::t('transfer', 'Сумма к списанию:')?> <?= $payments->pay_summ_from; ?></p>
                        <p><?= Yii::t('transfer', 'Перевод защищен:')?> <?= $payments->pay_isProtected ? Yii::t('transfer', 'да') : Yii::t('transfer', 'нет'); ?></p>
                        <?php if ($payments->pay_isProtected):?>
                        <p><?= Yii::t('transfer', 'Код протекции:')?> <?= $payments->pay_protected_code; ?></p>
                        <?php endif;?>
                        <p><?= Yii::t('transfer', 'Комментарий:')?> <?= $payments->pay_comment; ?></p>
                        <br />
                    </div>
                </div>
            </div>
        </div>
        <div class="b-clear"></div>