<?php

use yii\helpers\Html;

?>
<div class="b-content">
    <div class="b-content-full-width">
        <h1><?= Yii::t('pay/template', 'ШАБЛОНЫ_ПЛАТЕЖЕЙ')?></h1>
        <div class="b-content-full-width-templates">
            <?php if ($model):?>
                <ul class="b-payments-templates">
                    <?php foreach ($model as $value) :?>
                        <li>
                            <div class="b-payments-templates-nav">
                                <?= Html::a('', ['/pay-templates/update', 'id' => $value->pt_id], ['class' => 'b-payments-template-edit']);?>
                                <?= Html::a('', ['/pay-templates/delete', 'id' => $value->pt_id], ['class' => 'b-payments-template-delete', 'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ]]);?>
                            </div>
                            <div class="b-payment-logo">
                                <?= Html::img('/uploads/providers-logo/'.$value->provider->logo, ['alt' => $value->provider->name]);?>
                            </div>
                            <div class="b-payment-info">
                                <span class="b-payment-price"><?= $value->pt_summ.' '. $value->pt_currency;?></span>
                                <span class="b-payment-desc"><?= $value->pt_accaunt?></span>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            <?php else :?>
                <p><?= Yii::t('app', 'NO_PAYMENTS_TEMPLATES')?></p>
            <?php endif;?>
            <div class="b-clear"></div>
        </div>
        <br>
    </div>
</div>
<div class="b-clear"></div>
<?= \frontend\widgets\Tabs\TabsWidget::widget();?>
<?= \frontend\widgets\Logos\LogosWidget::widget();?>