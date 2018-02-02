<?php
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use common\models\BonusHelp;
use yii\helpers\Url;

//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$lang = \common\models\Language::getCurrent();

$this->registerJs('
        
        $(".b-table-filter .b-date").datepicker({
            showOn: "both",
            buttonImage: "/images/auth/calendar-icon.png",
            buttonImageOnly: true,
            buttonText: ""
        });
        $(".b-table-filter .b-date").datepicker($.datepicker.regional["'.$lang->lang_url.'"]);
        $(".b-table-filter .b-date").datepicker("option", "dateFormat", "yy-mm-dd");
        //$(".b-table-filter .b-date").keyup(function () {
        //    var value = $(this).val();
        //    if (value.length == 2) {
        //        $(this).val(value + \'-\');
        //    }
        //    if (value.length == 5) {
        //        $(this).val(value + \'-\');
        //    }
        //});
        
'
, yii\web\View::POS_END);

$this->title = Yii::t('main/menu', 'Бонусы');
?>




<div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-side-bar">
                    <div class="b-information-wrapper">
                        <div class="b-information">
                        <?php echo common\widgets\BonusHelp\HelpBlock::widget(['alias'=>BonusHelp::ALIAS_HELP])?>
                        </div>
                    </div>
                </div>
                <div class="b-categorys">
                    <div class="b-bonus-wrapper">
                        <h1><?=$this->title?></h1>
                        <div class="b-bonuses-btns">
                            <div class="b-your-bonuses">
                                <div class="b-bonuses-name">
                                    <?=Yii::t('main/menu', 'Ваши бонусы')?>
                                </div>
                                <div class="b-your-bonuses-inner">
                                    <div class="b-your-bonus b-left">
                                        <span class="b-your-bonus-title"><?=Yii::t('main/menu', 'Балланс')?>:</span>
                                        <span class="b-your-bonus-sum" style="width: 90px!important;"><b><?= isset($user->ballance) ? $user->ballance->money_bonus_ballance : '0'; ?></b> <?=Yii::t('main/menu', 'Бонусов')?></span>
                                    </div>
                                    <div class="b-your-bonus b-right">
                                        <span class="b-your-bonus-title"><?=Yii::t('main/menu', 'Это')?>:</span>
                                        <span class="b-your-bonus-sum" style="width: 60px!important; text-align: right;"><b>
                                        <?php
                                        //$curr = round($user->ballance->money_bonus_ballance/$settings->get('AZN_to_balance', 'currency'),2);
                                        //echo Yii::$app->formatter->asDecimal($curr);
                                        echo number_format($user->ballance->money_bonus_ballance/$settings->get('AZN_to_balance', 'currency'),2,'.',',');
                                        ?>

                                            </b> AZN</span>
                                    </div>
                                    <div class="b-clear"></div>
                                    <a class="b-bonus-on-money-btn" href="/user/bonus-transfer"><?=Yii::t('main/menu', 'Перевести в деньги')?></a>
                                    <p><span><?=Yii::t('main/menu', 'Курс')?>:</span>1 AZN = <?=$settings->get('AZN_to_balance', 'currency');?> <?=Yii::t('main/menu', 'бонусов')?><br />
                                        <span><?=Yii::t('main/menu', 'Минимальный обмен')?>:</span><?=$settings->get('min_bonus_transfer', 'currency');?>
                                    </p>
                                </div>
                            </div>
                            <div class="b-your-tarif">
                                <div class="b-your-tarif-header">
                                    <span class="b-tarif-sum"><?=Yii::t('main/menu', 'Сумма платежей')?></span></br>
                                    <span class="b-tarif-sum-value"><b><?= isset($user->ballance) ? $user->ballance->money_transaction_amount : '0';?></b> AZN</span>
                                </div>
                                <div class="b-your-tarif-btn-wrap">
                                    <?php if (!empty($user->tariffPlan)):?>
                                    <?=  Html::a(Yii::t('main/menu', 'Тарифный план ').'</br><b>'.Yii::t('main/menu',$user->tariffPlan->title).'</b>', ['/user/bonus-plans', 'tp_id' => $user->tariffPlan->tp_id], ['class' => 'b-your-tarif-btn'])?>
                                    <?php else:?>
                                    <a class="b-your-tarif-btn" href="/user/bonus-plans"><?=Yii::t('main/menu', 'Тарифный план ')?> <b><?=Yii::t('main/menu', 'No data')?></b></a>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="b-your-reclame-materials">
                                <a href="/user/reclame">
                                    <div class="b-your-reclame-materials-head"></div>
                                    <div class="b-your-reclame-materials-body">
                                        <?=Yii::t('main/menu', 'Рекламные <b>Материалы</b>')?>
                                    </div>
                                </a>
                            </div>
                            <div class="b-clear"></div>
                        </div>
                        <div class="b-refer-filed-wrapper">
                            <label><?=Yii::t('main/menu', 'Поделитесь ссылкой с друзьями и получите бонус')?></label>
                            <div class="b-field b-right"><input class="b-input" type="text" value="<?=$user->referralLink?>"/></div>
                        </div>
                        <div class="b-clear"></div>
                        <div class="b-bonus-filter b-table-filter">
                                <?php ActiveForm::begin(['id' => 'filter', 'action' => Url::toRoute(['/user/bonus'])]); ?>
                                <div class="b-field">
                                    <label><?=Yii::t('payments/model', 'От')?></label>
                                    <input class="b-input b-date" id="bonus-ot" type="text" value="<?= isset($_POST['date_start']) ? $_POST['date_start'] : '';?>" placeholder="__ - __ - ____" name="date_start"/>
                                </div>
                                <div class="b-field">
                                    <label><?=Yii::t('payments/model', 'До')?></label>
                                    <input class="b-input b-date" type="text" id="bonus-do" value="<?= isset($_POST['date_end']) ? $_POST['date_end'] : '';?>" placeholder="__ - __ - ____" name="date_end"/>
                                </div>
                                <div class="b-field b-right"><input type="submit" value="<?=Yii::t('payments/model', 'Показать')?>" class="b-submit b-bonus-submit"/></div>
                                <div class="b-clear"></div>
                            <?php ActiveForm::end();?>
                        </div>
                    </div>
                    <div class="b-table-wrapper">
                        <table class="b-table">
                            <thead>
                                <tr><th><?=Yii::t('main/menu', 'Последние события')?></th><th>&nbsp;</th><th>&nbsp;</th></tr>
                            </thead>
                            <tbody>
                                <?php if ($history):?>
                                        <?php $i=1;?>
                                    <?php foreach ($history as $model):?>
                                        <tr <?= ($i%2) == 0 ? ' class="b-gray"' : '';?>>
                                            <td>
                                                <?=\Yii::$app->formatter->asDatetime($model->bh_create, 'dd MMMM Y H:m'); ?>
                                            </td>
                                            <td>
                                                <?=$model->getTypeLabel();?>
                                            </td>
                                            <td>
                                                <?=$model->bh_bonus.' '. Yii::t('main/menu', ' бонусов');?>
                                            </td>
                                        </tr>
                                        <?php $i++;?>
                                    <?php endforeach;?>
                                <?php else:?>
                                        <tr> <td><?=Yii::t('main/menu', 'Нет данных');?></td></tr>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="b-clear"></div>