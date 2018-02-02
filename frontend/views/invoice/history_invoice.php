<?php
/* @var $this yii\web\View */
/* @var $model \common\models\Invoice */
use yii\widgets\ActiveForm;
use common\models\Invoice;
use yii\helpers\Url;

//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->registerJs(<<<JS

        $(".b-table-filter .b-date").datepicker({
            showOn: "both",
            buttonImage: "/images/auth/calendar-icon.png",
            buttonImageOnly: true,
            buttonText: ""
        });
        $(".b-table-filter .b-date").datepicker($.datepicker.regional["en"]);
        $(".b-table-filter .b-date").datepicker("option", "dateFormat", "dd-mm-yy");

JS
    , yii\web\View::POS_END);

$this->title = Yii::t('main/menu', 'ИСТОРИЯ_СЧЕТОВ');
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
                <div class="b-checks-filter b-table-filter">
                    <?php ActiveForm::begin([
                        'id' => 'filter',
                        'action' => Url::toRoute(['/invoice/history-invoice'])
                    ]); ?>
                    <div class="b-field">
                        <label class="ih_label <?=\common\models\Language::getCurrent()->lang_url?>"><?= Yii::t('invoice', 'ОТ') ?></label>
                        <input class="b-input b-date" id="invoice-ot"
                               value="<?= Yii::$app->request->post('date_start') ? Yii::$app->request->post('date_start') : '' ?>"
                               placeholder="<?= Yii::$app->request->post('date_start') ? Yii::$app->request->post('date_start') : '__ - __ - ____' ?>"
                               type="text" name="date_start">
                    </div>
                    <div class="b-field">
                        <label class="ih_label <?=\common\models\Language::getCurrent()->lang_url?>"><?= Yii::t('invoice', 'ДО') ?></label>
                        <input class="b-input b-date" id="invoice-do"
                               value="<?= Yii::$app->request->post('date_end') ? Yii::$app->request->post('date_end') : '' ?>"
                               placeholder="<?= Yii::$app->request->post('date_end') ? Yii::$app->request->post('date_end') : '__ - __ - ____' ?>"
                               type="text" name="date_end">
                    </div>
                    <div class="b-field b-field-check">
                        <?= Html::checkbox('in', false != Yii::$app->request->post('in') ? true : false, [
                            'class' => 'b-checkbox'
                        ]); ?>
                        <label class="b-checks-chebox-label"><?= Yii::t('invoice', 'ВХОДЯЩИЕ') ?></label>
                    </div>
                    <div class="b-field b-field-check">
                        <?php echo Html::checkbox('out', false != Yii::$app->request->post('out') ? true : false, [
                            'class' => 'b-checkbox'
                        ]); ?>
                        <label class="b-checks-chebox-label"><?= Yii::t('invoice', 'ИСХОДЯЩИЕ') ?></label>
                    </div>
                    <div class="b-field b-field-check"><input value="<?= Yii::t('invoice', 'ПОКАЗАТЬ') ?>"
                                                              class="b-submit b-bonus-submit"
                                                              type="submit"></div>
                    <div class="b-clear"></div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-bonus-wrapper b-checks-wrap">
                <h1><?= Yii::t('invoice', 'ИСТОРИЯ') ?></h1>
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
                                    <?php echo $model->toUser->phone; ?>
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
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5"><?= Yii::t('main/menu', 'Нет данных'); ?></td>
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
