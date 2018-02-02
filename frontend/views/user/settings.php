<?php
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$lang = \common\models\Language::getCurrent();

$this->registerJs('
    
(function($){
$(function(){ 
$(".b-select").styler();
$(".b-checkbox").styler();
$(".b-radio").styler();
$(".b-table-filter .b-date").datepicker({
showOn:"both", 
buttonImage:"/images/auth/calendar-icon.png",
buttonImageOnly:true,
buttonText: ""
});
$(".b-table-filter .b-date").datepicker($.datepicker.regional["'.$lang->lang_url.'"]);
$(".b-table-filter .b-date").datepicker("option", "dateFormat", "dd-mm-yy");
$(".b-table-filter .b-date").keyup(function(){
var value = $(this).val();
if(value.length==2){
$(this).val(value+\'-\');
}  
if(value.length==5){
$(this).val(value+\'-\');
}  
});
});  
})(jQuery); 
function ShowTarif(id){
$(".b-bonus-tarif li a").removeClass("b-active");
$("#b-tarif-btn-"+id).addClass("b-active");
$(".b-tarifs-block").removeClass("b-active");
$("#b-tarif-"+id).addClass("b-active");
};
'
, yii\web\View::POS_READY);


?>




<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-side-bar">
            <div class="b-side-bar-menu">
                <?=$this->render('_settings_menu')?>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-settings-wrapper">
                <h1><?=Yii::t('user', 'Личные данные')?></h1>
                <div class="b-personal-number"><?=Yii::t('app', 'Номер кошелька')?>&nbsp;&nbsp;&nbsp;<b><?php echo $user->phone?></b></div>
                <div class="b-setting-personal-block b-left">
                    <table class="b-settings-table">
                        <tr><td><?=Yii::t('user', 'Фамилия')?></td><td><div class="b-field"><input type="text" class="b-input" value=""/></div></td></tr>
                        <tr><td><?=Yii::t('user', 'Имя')?></td><td><div class="b-field"><input type="text" class="b-input" value=""/></div></td></tr>
                        <tr><td><?=Yii::t('user', 'Страна')?></td><td><div class="b-field">
                                    <select class="b-select">
                                        <option value="1">Азербайджан</option>
                                        <option value="2">Латвия</option>
                                        <option value="3">Эстония</option>
                                        <option value="4">Россия</option>
                                    </select></div></td></tr>
                    </table>
                </div>
                <div class="b-setting-personal-block b-right">
                    <table class="b-settings-table">
                        <tr><td><?=Yii::t('user', 'Дата<br />рождения')?></td><td><div class="b-field b-table-filter"><input type="text" class="b-input b-date" value="" placeholder="__ - __ - ____"/></div></td></tr>
                        <tr><td><?=Yii::t('user', 'E-mail')?></td><td><div class="b-field"><input type="text" class="b-input" value=""/></div></td></tr>
                        <tr><td colspan="2"><div class="b-field b-right"><input type="submit" class="b-submit b-green" value="<?=Yii::t('user', 'Сохранить')?>"/></div></td></tr>
                    </table>
                </div>
                <div class="b-clear"></div>
                <div class="b-reminders-wrapper">
                    <h2><?=Yii::t('user', 'Уведомления')?></h2>
                    <table class="b-reminders-table">
                        <tr><td></td><td class="b-text-center b-other"><span><?=Yii::t('app', 'Почта')?></span></td><td class="b-text-center b-other"><span><?=Yii::t('user', 'Телефон')?></span></td></tr>
                        <tr><td class="b-first"><?=Yii::t('user', 'Безопасность')?></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td></tr>
                        <tr><td class="b-first"><?=Yii::t('user', 'Запланированные платежи')?></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td></tr>
                        <tr><td class="b-first"><?=Yii::t('user', 'Просроченные платежи')?></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td></tr>
                        <tr><td class="b-first"><?=Yii::t('user', 'Новости и объявления')?></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td><td class="b-text-center b-other"><input class="b-checkbox" type="checkbox"/></td></tr>
                    </table>
                    <br /><br />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>
<p>
    <?=Yii::t('user', 'Вы можете отредактировать ваши личные данные')?>
</p>

<?php
$form = ActiveForm::begin([
//    'layout' => 'horizontal',
    'options' => ['class' => 'form_complete'],
//    'fieldConfig' => [
//        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
//        'horizontalCssClasses' => [
//            'label' => 'form_complete label',
//            'input' => 'form_complete label input',
////            'offset' => 'col-sm-offset-4',
////            'wrapper' => 'col-sm-8',
////            'error' => '',
////            'hint' => '',
//        ],
//    ],
]); ?>

<div class="form-group" >
    <label>
        <h4><?= $user->getAttributeLabel('username');?></h4>
        <?= Html::activeTextInput($user, 'username') ?>
    </label>
    
    <label>
        <h4><?= $user->getAttributeLabel('email');?></h4>
        <?= Html::activeTextInput($user, 'email') ?>
    </label>
    
    <label>
        <h4><?= $user->getAttributeLabel('phone');?></h4>
        <?= Html::activeTextInput($user, 'phone') ?>
    </label>
    
    
    
    
    <label>
        <?= Html::input('submit', Yii::t('user', 'Изменить')) ?>
    </label>
        
    
    
    <?php // $form->field($user, 'user_username') ?>

    <?php // $form->field($user, 'user_email') ?>
    
    <?php // $form->field($user, 'user_phone') ?>
    
    <?php // $form->field($user, 'password')->passwordInput() ?>

    <!--<div class="form-group">-->
        <!--<div class="col-sm-offset-2 col-sm-10">-->
            <?php // Html::submitButton('Изменить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        <!--</div>-->
    <!--</div>-->
</div>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
(function($){
$(function(){ 
$('.b-select').styler();  
$('.b-checkbox').styler();
$('.b-radio').styler();
$(".b-table-filter .b-date").datepicker({
showOn:"both", 
buttonImage:"/images/auth/calendar-icon.png",
buttonImageOnly:true,
buttonText: ""
});
$(".b-table-filter .b-date").datepicker($.datepicker.regional["ru"]);
$(".b-table-filter .b-date").datepicker("option", "dateFormat", "dd-mm-yy");
$(".b-table-filter .b-date").keyup(function(){
var value = $(this).val();
if(value.length==2){
$(this).val(value+'-');
}  
if(value.length==5){
$(this).val(value+'-');
}  
});
});  
})(jQuery); 
function ShowTarif(id){
$('.b-bonus-tarif li a').removeClass('b-active');
$('#b-tarif-btn-'+id).addClass('b-active');
$('.b-tarifs-block').removeClass('b-active');
$('#b-tarif-'+id).addClass('b-active');
}
</script>