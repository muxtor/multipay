<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PayPlanned */

?>

<?php if ($models):?>
    <?php foreach($models as $model):?>
        <div class="b-planed-payments-block">
            <ul class="b-payments-templates b-planed-payments">
                <li>
                    <div class="b-payments-templates-nav">
                        <?= Html::a('', ['/pay-planned/update', 'id' => $model->pp_id], ['class' => 'b-payments-template-edit']);?>
                        <?= Html::a('', ['/pay-planned/delete', 'id' => $model->pp_id], ['class' => 'b-payments-template-delete', 'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ]]);?>
                    </div>
                    <div class="b-payment-logo">
                        <?= Html::img('/uploads/providers-logo/'.$model->provider->logo, ['alt' => $model->provider->name]);?>
                    </div>
                    <div class="b-payment-info">
                        <span class="b-payment-price"><?= $model->pp_summ .' '.$model->pp_currency; ?></span>
                        <span class="b-payment-desc"><?= $model->pp_name; ?></span>
                    </div>
                </li>
            </ul>
        </div>
    <?php endforeach;?>
<?php else:?>
    <p><?= Yii::t('payment', 'NO_PAYMENTS_PLANNED')?></p>
<?php endif;?>
