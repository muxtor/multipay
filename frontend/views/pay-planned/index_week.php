<?php

use yii\helpers\Html;
use common\models\PayPlanned;
use common\models\Language;

/* @var $this yii\web\View */
/* @var $model common\models\PayPlanned */

?>

<?php if ($models):?>
    <?php for($i = 1; $i<=7; $i++):?>
        <?php $planned = $models[$i];?>

        <div class="b-planed-payments-block">
            <div class="b-date-nav">
                <span class="b-day-name b-left"><?= PayPlanned::getWeekDays()[$i]?></span>
                <span class="b-date-name b-right"><?= Yii::$app->formatter->asDate(date('Y-m-d', strtotime('monday this week') + 60*60*24*($i-1)), 'php:d').' '.PayPlanned::getMonthNames()[date('n')]?></span>
            </div>
            <div class="b-clear"></div>
            <ul class="b-payments-templates b-planed-payments">
                <?php if ($planned):?>
                    <?php foreach($planned as $model):?>
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
                    <?php endforeach;?>
                <?php else:?>
                    <li>
                        <div class="b-none-payments"><?= Yii::t('payment', 'NO_PAYMENTS_PLANNED')?></div>
                    </li>
                <?php endif;?>
            </ul>
        </div>
        <?php if ($i%4 === 0):?>
            <div class="b-clear"></div>
        <?php endif;?>
    <?php endfor;?>
<?php else:?>
    <p><?= Yii::t('payment', 'NO_PAYMENTS_PLANNED')?></p>
<?php endif;?>
