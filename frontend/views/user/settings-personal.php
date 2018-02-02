<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use common\models\Language;

//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->registerJs(<<<JS
     $(document).ready(function(){
	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: '&#x3c;Пред',
		nextText: 'След&#x3e;',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
		'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
		'Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		weekHeader: 'Нед',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['ru']);
        
            $('.b-select').styler();
            $('.b-checkbox').styler();
            $('.b-radio').styler();
            $(".b-table-filter .b-date").datepicker({
                showOn: "both",
                buttonImage: "/images/auth/calendar-icon.png",
                buttonImageOnly: true,
                buttonText: ""
            });
            $(".b-table-filter .b-date").datepicker($.datepicker.regional["ru"]);
            $(".b-table-filter .b-date").datepicker("option", "dateFormat", "yy-mm-dd");
            $(".b-table-filter .b-date").keyup(function () {
                var value = $(this).val();
                if (value.length == 2) {
                    $(this).val(value + '-');
                }
                if (value.length == 5) {
                    $(this).val(value + '-');
                }
            });
        });
JS
    , yii\web\View::POS_READY);
?>


<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-side-bar">
            <div class="b-side-bar-menu">
                <?=$this->render('_settings_menu', ['active' => 0])?>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-settings-wrapper">
                <h1><?=Yii::t('user', 'Личные данные')?></h1>
                <?php
                $form = ActiveForm::begin([
                    'options' => ['class' => 'form_complete'],
                ]);
                ?>
                <div class="b-personal-number"><?=Yii::t('user', 'Номер кошелька')?>&nbsp;&nbsp;&nbsp;<b><?php echo $user->phone ?></b></div>
                <div class="b-setting-personal-block b-left">
                    <table class="b-settings-table">
                        <tr>
                            <td><?=Yii::t('user', 'Фамилия')?></td>
                            <td>
                                <div class="b-field"><?= Html::activeTextInput($user, 'lastName',
                                        ['class' => 'b-input']) ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td><?=Yii::t('User', 'Имя')?></td>
                            <td>
                                <div class="b-field"><?= Html::activeTextInput($user, 'firstName',
                                        ['class' => 'b-input']) ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td><?=Yii::t('user', 'Страна')?></td>
                            <td>
                                <div class="b-field">
                                    <?= Html::activeDropDownList($user, 'country_id',
                                        \yii\helpers\ArrayHelper::map(\common\models\Country::find()->all(), 'id', function ($flag, $defaultValue) { return '<img src="'.Yii::$app->params['flags'].strtolower($flag->iso).'.png"> ' .$flag->name; } ), //\yii\helpers\ArrayHelper::map(common\models\User::getCountryes(), 'id', 'name'),
                                        [
                                            'class' => 'b-select',
                                            'prompt' => Yii::t('common.models.user', 'Выберите страну')
                                        ]) ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="b-setting-personal-block b-right">
                    <table class="b-settings-table">
                        <tr>
                            <td><?=Yii::t('user', 'Дата<br/>рождения')?></td>
                            <td>
                                <div class="b-field b-table-filter"><?= $form->field($user, 'date_bird')->textInput([
                                        'class' => 'b-input b-date',
                                        'placeholder' => '__ - __ - ____'
                                    ])->label(false); ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>
                                <div class="b-field"><?= Html::activeTextInput($user, 'email',
                                        ['class' => 'b-input']) ?></div>
                                <div class="b-field"><?= Html::error($user, 'email') ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('user', "Язык"); ?></td>
                            <td>
                                <div class="b-field"> <?= Html::activeDropDownList($user, 'user_language',
                                        \yii\helpers\ArrayHelper::map(Language::find()->all(), 'lang_url', 'lang_name'),
                                        [
                                            'class' => 'b-select',
                                            'prompt' => Yii::t('common.models.user', 'Выберите язык')
                                        ]) ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="b-field b-right"><input type="submit" class="b-submit b-green"
                                                                    value="<?= Yii::t('user', "Сохранить"); ?>"/></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="b-clear"></div>
                <div class="b-reminders-wrapper">
                    <h2><?= Yii::t('user', "Уведомления (подтвердите свой выбор нажав кнопку 'Сохранить')"); ?></h2>
                    <table class="b-reminders-table">
                        <tr>
                            <td></td>
                            <td class="b-text-center b-other"><span><?=Yii::t('user', 'Почта')?></span></td>
                            <td class="b-text-center b-other"><span><?=Yii::t('user', 'Телефон')?></span></td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= Yii::t('user', "Безопасность"); ?>            </td>
                            <td class="b-text-center b-other"><?= Html::activeCheckbox($user, 'notice_safety_isEmail',
                                    ['class' => 'b-checkbox', 'label' => false]) ?>         </td>
                            <td class="b-text-center b-other"><input name="1" type="checkbox" disabled="disabled"
                                                                     checked="checked" class="b-checkbox"/></td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= Yii::t('user', "Запланированные платежи"); ?> </td>
                            <td class="b-text-center b-other"><?php echo Html::activeCheckbox($user,
                                    'notice_plannedPayments_isEmail',
                                    ['class' => 'b-checkbox', 'label' => false]) ?></td>
                            <td class="b-text-center b-other"><?php // echo Html::activeCheckbox($user, 'notice_plannedPayments_isPhone', ['class' => 'b-checkbox', 'label'=>FALSE])?>  </td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= Yii::t('user', "Просроченные платежи"); ?>    </td>
                            <td class="b-text-center b-other"><?= Html::activeCheckbox($user,
                                    'notice_latePayments_isEmail',
                                    ['class' => 'b-checkbox', 'label' => false]) ?>   </td>
                            <td class="b-text-center b-other"><?= Html::activeCheckbox($user,
                                    'notice_latePayments_isPhone',
                                    ['class' => 'b-checkbox', 'label' => false]) ?>     </td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= Yii::t('user', "Новости и объявления"); ?>    </td>
                            <td class="b-text-center b-other"><?= Html::activeCheckbox($user, 'notice_news_isEmail',
                                    ['class' => 'b-checkbox', 'label' => false]) ?>           </td>
                            <td class="b-text-center b-other"><?= Html::activeCheckbox($user, 'notice_news_isPhone',
                                    ['class' => 'b-checkbox', 'label' => false]) ?>             </td>
                        </tr>
                        <tr>
                            <td class="b-first"><?= Yii::t('user', "Изменение баланса"); ?>       </td>
                            <td class="b-text-center b-other"><?= Html::activeCheckbox($user, 'notice_balannce_isEmail',
                                    ['class' => 'b-checkbox', 'label' => false]) ?>           </td>
                            <td class="b-text-center b-other"><?= Html::activeCheckbox($user, 'notice_balannce_isPhone',
                                    ['class' => 'b-checkbox', 'label' => false]) ?></td>
                        </tr>
                    </table>
                    <br/>
                    <p class="user_settings_hint"><?= Yii::t('user',
                            "Услуга отправки СМС-сообщений об изменении баланса платная. Стоимость (AZN за месяц): ") . Yii::$app->settings->get('sms_change_balance',
                            'currency') ?></p>
                    <?php if ((strtotime(date('Y-m-d H:i:s')) - $user->created_at)/(60*60*24) < (int)Yii::$app->settings->get('sms_notif_balance_free_period', 'currency')):?>
                    <p class="user_settings_hint"><?= Yii::t('user',
                            "Для вновь зарегестрированных пользователей предоставляется бесплатный пробный период (дней): ") .  Yii::$app->settings->get('sms_notif_balance_free_period',
                            'currency') ?></p>
                    <?php endif; ?>
                    <?php if ($user->notice_balannce_isPhone_activationDateEnd) :?>
                        <?php
                        $currentDateEnd = new \DateTime($user->notice_balannce_isPhone_activationDateEnd);
                        ?>
                        <?php if ((int)$currentDateEnd->format('mm') >= (int) date('m')) ://подписка закончится в этом месяце или в следующих?>
                            <p class="user_settings_hint"><?= Yii::t('user',
                                    "Услуга будет отключена ") . Yii::$app->formatter->asDate($user->notice_balannce_isPhone_activationDateEnd, 'php:d.m.Y'); ?></p>
                        <?php endif;?>
                    <?php endif;?>
                    <br/><br/>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>


<script type="text/javascript">
    //    (function ($) {
    //        $(function () {
    //            $('.b-select').styler();
    //            $('.b-checkbox').styler();
    //            $('.b-radio').styler();
    //            $(".b-table-filter .b-date").datepicker({
    //                showOn: "both",
    //                buttonImage: "/images/auth/calendar-icon.png",
    //                buttonImageOnly: true,
    //                buttonText: ""
    //            });
    //            $(".b-table-filter .b-date").datepicker($.datepicker.regional["ru"]);
    //            $(".b-table-filter .b-date").datepicker("option", "dateFormat", "dd-mm-yy");
    //            $(".b-table-filter .b-date").keyup(function () {
    //                var value = $(this).val();
    //                if (value.length == 2) {
    //                    $(this).val(value + '-');
    //                }
    //                if (value.length == 5) {
    //                    $(this).val(value + '-');
    //                }
    //            });
    //        });
    //    })(jQuery);
    function ShowTarif(id) {
        $('.b-bonus-tarif li a').removeClass('b-active');
        $('#b-tarif-btn-' + id).addClass('b-active');
        $('.b-tarifs-block').removeClass('b-active');
        $('#b-tarif-' + id).addClass('b-active');
    }
</script>