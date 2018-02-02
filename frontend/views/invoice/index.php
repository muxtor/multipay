<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Invoice */

use common\models\Invoice;
use yii\helpers\Html;

?>

<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-side-bar">
            <div class="b-side-bar-menu">
                <ul>
                    <li><a href="/invoice/index"><i class="fa-perevod-zapros"></i><?= Yii::t('invoice', 'ВХОДЯЩИЕ') ?>
                        </a></li>
                    <li><a href="/invoice/out-invoice"><i class="fa-refresh"></i><?= Yii::t('invoice',
                                'ВЫСТАВЛЕННЫЕ') ?></a></li>
                    <li><a href="/invoice/add-invoice"><i class="fa-file-text-o"></i><?= Yii::t('invoice',
                                'ВЫСТАВИТЬ_СЧЕТ') ?></a></li>
                    <li><a href="history-invoice"><i class="fa-tasks"></i><?= Yii::t('invoice', 'ИСТОРИЯ') ?></a></li>
                </ul>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-bonus-wrapper b-checks-wrap">
                <h1><?= Yii::t('invoice', 'ВХОДЯЩИЕ') ?></h1>
            </div>
            <div class="b-table-wrapper b-checks-wrapper">
                <table class="b-table">
                    <thead>
                    <tr>
                        <th><?= Yii::t('invoice', 'Дата Создания') ?></th>
                        <th><?= Yii::t('invoice', 'ПС') ?><br><?= Yii::t('invoice', 'НОМЕР') ?></th>
                        <th><?= Yii::t('invoice', 'СУММА') ?></th>
                        <th class="b-text-center"><?= Yii::t('invoice', 'КОММЕНТ.') ?></th>
                        <th class="b-text-center"><?= Yii::t('invoice', 'СТАТУС') ?></th>
                        <th><?= Yii::t('invoice', 'ДЕЙСТВИЕ') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($dataProvider->getModels()): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($dataProvider->getModels() as $model): ?>
                            <tr <?= ($i % 2) == 0 ? ' class="b-gray"' : ''; ?>>
                                <td>
                                    <?= \Yii::$app->formatter->asDatetime($model->payment->pay_created,
                                        'php:d M Y H:m'); ?>
                                </td>
                                <td>
                                    <?php if($model->from_user_id !== 0):?>
                                        <?= $model->fromUser->phone; ?>
                                    <?php else:?>
                                        <?= $model->payment->provider->name; ?>
                                        <br>
                                        <?= $model->payment->pay_pc_provider_account; ?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?= $model->payment->pay_summ_from; ?>
                                </td>
                                <td>
                                    <?= $model->comment; ?>
                                </td>
                                <td class="b-text-center">
                                    <?php if ($model->status == Invoice::STATUS_WAIT): ?>
                                        <span class="b-payment-status b-wait"></span>
                                    <?php elseif ($model->status == Invoice::STATUS_DONE): ?>
                                        <span class="b-payment-status b-success"></span>
                                    <?php elseif ($model->status == Invoice::STATUS_CANCEL): ?>
                                        <span class="b-payment-status b-fail"></span>
                                    <?php endif; ?>
                                </td>
                                <td class="b-text-center">
                                    <?php if ($model->status == Invoice::STATUS_WAIT): ?>
                                        <?= Html::a(Yii::t('invoice', 'ОПЛАТИТЬ'),
                                            ['/invoice/confirm-invoice', 'id' => $model->id],
                                            ['class' => 'b-table-pay-link']) ?>
                                        <br>
                                        <?= Html::a(Yii::t('invoice', 'ОТМЕНИТЬ'),
                                            ['/invoice/cancel-invoice', 'id' => $model->id],
                                            [
                                                'class' => 'b-table-discuss-link',
                                                'data' => [
                                                    'confirm' => Yii::t('invoice', 'ОТМЕНЯЕМ ПЛАТЕЖ?'),
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                    <?php endif; ?>

                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6"><?= Yii::t('main/menu', 'Нет данных'); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>
<?= \frontend\widgets\Tabs\TabsWidget::widget() ?>
<?= \frontend\widgets\Logos\LogosWidget::widget() ?>
