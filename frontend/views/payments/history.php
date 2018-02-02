<?php 
use yii\helpers\Url;

use common\models\Language;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

$lang = \common\models\Language::getCurrent();
$show = Yii::t('payments/model','Скрыть фильтр');
$hide = Yii::t('payments/model','Показать фильтр');
$this->registerJs('
        $(".b-select").styler();
        $(".b-checkbox").styler();
        $(".b-radio").styler();
        
        $(".b-table-filter .b-date").datepicker({
            showOn: "both",
            buttonImage: "/images/auth/calendar-icon.png",
            buttonImageOnly: true,
            buttonText: ""
        });
        $.datepicker.setDefaults($.datepicker.regional["'.$lang->lang_url.'"]);
        $(".b-table-filter .b-date").datepicker($.datepicker.regional["'.$lang->lang_url.'"]);
        $(".b-table-filter .b-date").datepicker("option", "dateFormat", "yy-mm-dd");
        $(".b-table-filter .b-date").keyup(function () {
            var value = $(this).val();
            if (value.length == 2) {
                $(this).val(value + \'-\');
            }
            if (value.length == 5) {
                $(this).val(value + \'-\');
            }
        });
        
        //Переключение фильтра
        $(".b-show-history-filter").click(function (e) {
            e.preventDefault();
            $(".b-filter-checkboxes-wrapper").toggleClass("b-active");
            if ($(".b-filter-checkboxes-wrapper").hasClass("b-active")) {
                $(".b-show-history-filter").html("'.$show.'");
            } else {
                $(".b-show-history-filter").html("'.$hide.'");
            }
        });
'
);

$this->title = Yii::t('payments/model', 'История платежей - MultiPay');
?>
<div class="b-content-wrapper">
    <div class="b-content">
                <div class="b-content-full-width">
                    <h1><?=Yii::t('payments/model', 'История платежей')?></h1>
                    <div class="b-content-full-width-wrap">
                        <div class="b-history-filter b-table-filter">
                            <a href="/payments/pay-file"><?=Yii::t('payments/model', 'Экспорт в CSV')?></a>
                                <?php ActiveForm::begin(['id' => 'filter', 'action' => Url::toRoute(['/payments/history'])]); ?>
                            <div class="b-history-form">
                                <div class="b-field"><input class="b-input" type="text" placeholder="<?=Yii::t('payments/model', 'ID операции')?>" name="ID" value="<?= isset($_POST['ID']) ? $_POST['ID'] : '';?>"/></div>
                                <div class="b-field">
                                    <label><?=Yii::t('payments/model', 'От')?></label>
                                    <input class="b-input b-date" id="bonus-ot" type="text" value="<?= isset($_POST['date_start']) ? $_POST['date_start'] : '';?>" placeholder="__ - __ - ____" name="date_start"/>
                                </div>
                                <div class="b-field">
                                    <label><?=Yii::t('payments/model', 'До')?></label>
                                    <input class="b-input b-date" type="text" id="bonus-do" value="<?= isset($_POST['date_end']) ? $_POST['date_end'] : '';?>" placeholder="__ - __ - ____" name="date_end"/>
                                </div>
                                <a class="b-show-history-filter" href="#"><?=Yii::t('payments/model', 'Показать фильтр')?></a>
                                <div class="b-field b-right"><input type="submit" value="<?=Yii::t('payments/model', 'Показать')?>" class="b-submit b-bonus-submit"/></div>
                                <div class="b-clear"></div>
                            </div>
                            <div class="b-filter-checkboxes-wrapper">
                                <div class="b-checkbox-field"><?= Html::checkboxList('pay_status', isset($_POST['pay_status']) ? $_POST['pay_status'] : null,  common\models\Payments::payStatusLabels()); ?></div>
                                <div class="b-checkbox-field"><?= Html::checkboxList('pay_type', isset($_POST['pay_type']) ? $_POST['pay_type'] : null,  common\models\Payments::payTypeLabels()); ?></div>
                            </div>
                            <?php ActiveForm::end();?>
                        </div>
                    </div>
                    <div class="b-table-wrapper">
                        <table class="b-table">
                            <thead>
                                <tr>
                                    <th><?=Yii::t('payments/model', 'Дата')?><br /><?=Yii::t('payments/model', 'ID транзакции')?></th>
                                    <th><?=Yii::t('payments/model', 'Доход')?></th>
                                    <th><?=Yii::t('payments/model', 'Расход')?></th>
                                    <th><?=Yii::t('payments/model', 'ПС')?><br /><?=Yii::t('payments/model', 'Номер')?></th>
                                    <th class="b-text-center"><?=Yii::t('payments/model', 'Комментарий')?></th>
                                    <th class="b-text-center"><?=Yii::t('payments/model', 'Статус')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            <?php foreach ($models as $model):?>
                                <tr <?= ($i%2) == 0 ? ' class="b-gray"' : '';?>>
                                    <td>
                                        <?=\Yii::$app->formatter->asDate($model->pay_created, 'dd MMMM y'); ?>&nbsp;&nbsp;
                                        <span class="b-history-date"><?=\Yii::$app->formatter->asTime($model->pay_created, 'HH:mm'); ?></span><br />
                                        <span class="b-payment-nubmer"><?=$model->pay_id;?></span>
                                    </td>
                                    <?php $pay_summ_from = $model->pay_summ_from; settype($pay_summ_from, "integer");?>
                                    <td><span class="b-balans-plus"><?=$model->pay_type < 2 ? '+'.$model->pay_summ . ' ' . $model->pay_currency : '';?></span></td>
                                    <td><span class="b-balans-minus"><?=$model->pay_type > 1 ? '-'.$model->pay_summ_from . ' ' . $model->pay_currency : '';?></span></td>
                                    <td><?=$model->getProviderName();?><br /><?=$model->pay_pc_provider_account;?></td>
                                    <td class="b-text-center"><?=$model->pay_comment;?><br /></td>
                                    <td class="b-text-center"><span class="b-payment-status <?=$model->payStatusCss();?>"></span></td>
                                </tr>
                                <?php $i++;?>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br />
            </div>
</div>
