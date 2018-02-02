<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\widgets\ActiveForm;
use common\models\PayPlanned;
use common\models\PayTemplate;
use common\models\Language;
use common\models\Country;
AppAsset::register($this);
?>

<?php 
    if (!empty($_COOKIE['country_iso'])) {
        $country = common\models\Country::findOne(['iso' =>$_COOKIE['country_iso']]);
    }
    if (!empty($country) && $country->tel_mask) {
        $countryMask = $country->tel_mask;
    } else {
        $countryMask = '+7(###) ###-##-##';
    }
//        die();
    $this->registerJs(
        '
        $(document).ready(function(){
            var countryMask = "'.$countryMask.'";
                //$("#signupform-phone").mask(countryMask);
                //$("#loginform-phone").mask(countryMask);
                $("#resetpasswordform-phone").mask(countryMask);
        });'
, yii\web\View::POS_END);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src='https://www.google.com/recaptcha/api.js?hl=az'></script>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="b-header-wrapper">
        <div class="b-header">
            <div class="b-logo-wrapper">
                <a href="/"><img src="/images/logo.png" alt="logo"></a>
            </div>
            <div class="b-header-right-block">
                <div class="b-lang-wrapper">
                    <?php echo common\widgets\Language\LangFront::widget()?>
                </div>
        <?php if (\Yii::$app->user->isGuest) { ?>
                <div class="b-login-form">
                    <div class="b-login-form-nav">
                        <span class="b-login-text"><?= Yii::t('main/menu', 'ВОЙТИ_В_МОЙ_КАБИНЕТ')?></span>
                        <span class="b-reset-text">
                            <a href="/reset-password"><?= Yii::t('main/menu', 'ВОССТАНОВИТЬ_ПАРОЛЬ')?></a>
                        </span>
                    </div>
                    <div class="b-form">
                    <?php $formUser = ActiveForm::begin(['id' => 'login-form', 'action' => '/site/login']); 
                            $model_login = new frontend\models\LoginForm();
                    ?>
                        <div class="flags-container" >
                            <?=
                            $formUser->field($model_login, 'phone', [
                                'template' => "{input}<img style='cursor: pointer;' class='flag-login to-flag-img-login' src=\"/images/az.png\" alt=\"Азербайджан\">",
                                'options' => [
                                    'class' => 'b-field b-first'
                                ]
                            ])->textInput(['class' => 'to-flag-login b-input b-flag'])->label(FALSE);
                            ?>
                                <?php
                                $this->registerJs(
                                    "jQuery(document).ready(function(){
                                        $(document).click( function(event){
                                            if( $(event.target).closest('.flags,.to-flag-img-login,.to-flag-img').length ){ return;}
                                            $('.flags').hide(); event.stopPropagation();
                                        });
                                        thisis = jQuery('.flags.login .in-flag.selected');
                                        jQuery('.to-flag-img-login').attr('src',thisis.attr('data-img'));
                                        jQuery('.to-flag-img-login').attr('alt',thisis.attr('data-name'));
                                        jQuery('.to-flag-img-login').attr('title',thisis.attr('data-name'));
                                        jQuery('.to-flag-login').attr('placeholder',thisis.attr('data-code'));
                                        jQuery('#loginform-phone').mask(thisis.attr('data-mask'));

                                        jQuery('img.flag-login').on('click',function(){
                                            jQuery('.flags.login').toggle();
                                        });

                                        jQuery('.flags.login .in-flag').on('click',function(){
                                            jQuery('.flags.login .in-flag').removeClass('selected');
                                            jQuery(this).addClass('selected');
                                            jQuery('.to-flag-img-login').attr('src',jQuery(this).attr('data-img'));
                                            jQuery('.to-flag-img-login').attr('alt',jQuery(this).attr('data-name'));
                                            jQuery('.to-flag-img-login').attr('title',jQuery(this).attr('data-name'));
                                            jQuery('.to-flag-login').attr('placeholder',jQuery(this).attr('data-code'));
                                            jQuery('#loginform-phone').mask(jQuery(this).attr('data-mask'));
                                            jQuery('.flags.login').toggle();
                                        });
                                    });"
                                    , yii\web\View::POS_END);
                                ?>
                                <div class="flags login">
                                    <ul>
                                        <?php
                                        $flags = common\models\Country::find()->where(['not',['tel_mask'=>null]])->all();
                                        if($flags!=null){
                                            $selected = '';
                                            foreach($flags as $key => $flag){
                                                reset($flags);
                                                if ($key === key($flags))
                                                    $selected = 'selected';
                                                else
                                                    $selected='';
                                                ?>
                                                <li class="in-flag <?=$selected?>" data-name="<?=$flag->name?>" data-img="<?=Yii::$app->params['flags'].strtolower($flag->iso)?>.png" data-mask="<?=$flag->tel_mask?>" data-code="<?=$flag->tel_code?>"><img src="<?=Yii::$app->params['flags'].strtolower($flag->iso)?>.png"> <?=$flag->name?> <span class="pull-right"><?=strtolower($flag->tel_code)?></span></li>
                                            <?php }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <?=
                            $formUser->field($model_login, 'password', [
                                'template' => "{input}",
                                'options' => [
                                    'class' => 'b-field'
                                ]
                            ])->passwordInput(['class' => 'b-input', 'placeholder' => 'Пароль'])->label(FALSE);

                            ?>
                                <div class="b-field"><input class="b-submit" type="submit" value="<?= Yii::t('main/menu', 'ВОЙТИ')?>"/></div>
                        </div>
                        <?php
                        if(isset($_COOKIE['country_iso'])){
                            $iso = $_COOKIE['country_iso'];
                            $country = Country::find()->where(['iso'=>$iso])->one();
                            if($country!=null){
                                $this->registerJs(
                                "jQuery(document).ready(function(){
                                    jQuery('.to-flag-img-login').attr('src','/images/flags/16/".strtolower($country->iso).".png');
                                    jQuery('.to-flag-img-login').attr('alt','".$country->name."');
                                    jQuery('.to-flag-img-login').attr('title','".$country->name."');
                                    jQuery('.to-flag-login').attr('placeholder','".$country->tel_code."');
                                    jQuery('#loginform-phone').mask('".$country->tel_mask."');
                                });"
                                    , yii\web\View::POS_END
                                );
                            }
                        }
                        ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
        <?php } else { ?>
                <div class="b-exit-form">
                    <div class="b-form">
                            <div class="b-user-profile">
                                <span class="b-user-name"><?=\Yii::$app->user->identity->firstName; ?></span>
                                <span class="b-user-sname"><?=\Yii::$app->user->identity->lastName; ?></span></div>
                            <div class="b-field b-exit">
                                <?= Html::a('<input class="b-submit" type="submit" value="'. Yii::t('main/menu', 'ВЫХОД').'"/>', ['/site/logout'], ['data-method' => 'post', 'class' =>'b-submit']); ?>
                            </div>
                    </div>
                </div>
        <?php } ?>
            </div>
        </div>
    </div>    
    
    <div class="b-menu-wrapper">
        <div class="b-menu">
            <ul>
                <li class="b-drop-down"><a href="#"><?= Yii::t('main/menu', 'ПЛАТЕЛЬЩИКУ')?></a><i class="b-drop-down-icon"></i>
                    <ul>
                        <li><a href="/<?=Language::getCurrent()->lang_url?>/payments/payment-search"><?= Yii::t('main/menu', 'ПОИСК_ПЛАТЕЖА')?></a></li>
                        <li><a href="#"><?= Yii::t('main/menu', 'КАРТА_ТЕРМИНАЛОВ')?></a></li>
                        <li><a href="#"><?= Yii::t('main/menu', 'СПИСОК_ТЕРМИНАЛОВ')?></a></li>
                    </ul>
                </li>
                <li class="b-drop-down"><a href="#"><?= Yii::t('main/menu', 'УСЛУГИ')?></a><i class="b-drop-down-icon"></i>
                </li>
                <?php
                if (!Yii::$app->user->isGuest) {
                    $user = \common\models\User::findOne(Yii::$app->user->id);
                    if ($user !== null AND $user->user_agent == 1) { ?>
                        <li class="b-drop-down"><a href="#"><?= Yii::t('main/menu', 'API')?></a><i class="b-drop-down-icon"></i>
                            <ul>
                                <li><a href="/<?=Language::getCurrent()->lang_url?>/api-settings/"><?= Yii::t('main/menu', 'Настройки')?></a></li>
                                <li><a href="/<?=Language::getCurrent()->lang_url?>/payment-api/"><?= Yii::t('main/menu', 'Операции')?></a></li>
                            </ul>
                        </li>
                    <?php }
                }
                ?>
                <li><a href="/<?=Language::getCurrent()->lang_url?>/news/"><?= Yii::t('main/menu', 'НОВОСТИ')?></a></li>
                <?php if (\Yii::$app->user->isGuest):?>
                <li><a href="/<?=Language::getCurrent()->lang_url?>/user/bonus-plans"><?= Yii::t('main/menu', 'ТАРИФЫ')?></a></li>
                <?php endif;?>
                <li><a href="/<?=Language::getCurrent()->lang_url?>/faq/"><?= Yii::t('main/menu', 'ПОМОЩЬ')?></a></li>
                <li><a href="/<?=Language::getCurrent()->lang_url?>/about"><?= Yii::t('main/menu', 'О_КОМПАНИИ')?></a></li>
            </ul>
        </div>
    </div>
    
    <?php if (!\Yii::$app->user->isGuest):?>    
    <?php $user = \Yii::$app->user->identity;?>    
    <div class="b-slider-wrapper">
        <div class="b-info-blocks">
            <div class="b-auth-block b-first">
                <div class="b-auth-block-title">
                    <a class="b-message-icon b-active" href="#"><span class="b-auth-msg-count">1</span>
                        <div class="b-message-popup">
                            <span class="b-message-popup-triangle"></span>
                            <div class="b-message-popup-title">
                                Уведомления
                            </div>
                            <div class="b-message-popup-content">
                                <div class="b-message-popup-inner">
                                    <div class="b-message-popup-inner-title">Техничесике работы <span class="b-message-popup-inner-date">20 Ноября 13:48</span></div>
                                    <p>Напоминаем, что оплата долга является Вашей
                                        обязаннастью, в связи с чем просим Вас 
                                        оплатить задолженность в течении 2 дней после
                                        получения СМС</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="b-auth-user-icon"><img src="/images/auth/profile-icon.png"/></div>
                    <span class="b-auth-login"><?php echo \Yii::$app->user->identity->phone?><span class="b-triangle"></span>
                        <div class="b-auth-dropdown">
                            <ul class="b-auth-dropdown-menu">
                                <li><a href="/user/settings-personal"><?= Yii::t('main/menu', 'НАСТРОЙКИ')?></a></li>
                                <li><a href="/invoice/index"><?= Yii::t('main/menu', 'СЧЕТА')?></a></li>
                                <li><a href="#"><?= Yii::t('main/menu', 'СООБЩЕНИЯ')?></a></li>
                                <li><a href="/user/transfer"><?= Yii::t('main/menu', 'ПЕРЕВОД_НА_ДРУГОЙ_СЧЕТ')?></a></li>
                                <li><a href="/pay-templates/index"><?= Yii::t('main/menu', 'ИЗБРАННЫЕ_ПЛАТЕЖИ')?></a></li>
                                <li><a href="/pay-planned/index"><?= Yii::t('main/menu', 'ЗАПЛАНИРОВАННЫЕ_ПЛАТЕЖИ')?></a></li>
                                <li><a href="/payments/history"><?= Yii::t('main/menu', 'ИСТОРИЯ_ОПЕРАЦИЙ')?></a></li>
                                <li><a href="<?=Language::getCurrent()->lang_url?>/faq/"><?= Yii::t('main/menu', 'ПОМОЩЬ')?></a></li>
                            </ul>
                        </div>
                    </span>
                </div>
                <div class="b-auth-block-content">
                    <div class="b-auth-profile-info">
                        <div class="b-profile-info-raw">
                        <span class="b-profile-info-title"><?= Yii::t('main/menu', 'БАЛАНС')?>:</span><span class="b-profile-info-balans"><b><?= isset($user->ballance->money_amount) ? number_format($user->ballance->money_amount,2,'.',',') : '0.00'; ?></b> AZN</span>
                        </div>
                        <div class="b-profile-info-raw">
                            <span class="b-profile-info-title"><?= Yii::t('main/menu', 'БОНУСЫ') ?>:</span><span
                                class="b-profile-info-bonus"><b><?= isset($user->ballance->money_bonus_ballance) ? $user->ballance->money_bonus_ballance : '0'; ?></b> <a
                                    href="#" class="b-profile-info-bonus-about"></a></span>
                        </div>
                        <?php
                        $date = new DateTime(Yii::$app->user->identity->last_login);
                        ?>
                        <span class="b-profile-last-active"><?= Yii::t('main/menu', 'ПОСЛЕДНЯЯ_АКТИВНОСТЬ') . ': '.$date->format('d.m.Y H:i:s')?></span>
                    </div>
                </div>
            </div>
            <div class="b-auth-block">
                <div class="b-auth-block-title">
                    <div class="b-auth-user-icon"><img src="/images/auth/favorites.png"/></div>
                        <div class="b-auth-title"><?= Yii::t('main/menu', 'ИЗБРАННЫЕ_ПЛАТЕЖИ')?> <a href="/pay-templates/index"><?= Yii::t('main/menu', 'СМОТРЕТЬ_ВСЕ')?></a></div>
                </div>
                <div class="b-auth-block-content">
                    <div class="b-auth-profile-info b-table-inner">
                        <table class="b-auth-block-table">
                            <?php
                            $templates = PayTemplate::find()
                                ->where('pt_user_id = :id', [':id' => Yii::$app->user->id])
                                ->orderBy('pt_id DESC')
                                ->limit(5)->all();
                            ?>
                            <?php if ($templates):?>
                                <?php foreach ($templates as $t):?>
                                    <tr>
                                        <td class="b-more-info-cell">
                                            <?= Html::a('', ['/pay-templates/update', 'id' => $t->pt_id], ['class' => 'b-show-pay-icon'])?>
                                        </td>
                                        <td><?= $t->provider->name?></td>
                                        <td class="b-price-cell"><?= $t->pt_summ.' '. $t->pt_currency;?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td class="b-more-info-cell" colspan="3"><?= Yii::t('main/menu', 'НЕТ_ШАБЛОНОВ')?></td></tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="b-auth-block">
                <div class="b-auth-block-title">
                    <div class="b-auth-user-icon"><img src="/images/auth/planned.png"/></div>
                    <div class="b-auth-title"><?= Yii::t('main/menu', 'ЗАПЛАНИРОВАННЫЕ_ПЛАТЕЖИ')?> &nbsp;&nbsp;&nbsp;<a href="/pay-planned/index"><?= Yii::t('main/menu', 'СМОТРЕТЬ_ВСЕ')?></a></div>
                </div>
                <div class="b-auth-block-content">
                    <div class="b-auth-profile-info b-table-inner">
                        <table class="b-auth-block-table">
                            <?php
                            $planned_pays = PayPlanned::find()
                                ->where('pp_user_id = :id', [':id' => Yii::$app->user->id])
                                ->orderBy('pp_id DESC')
                                ->limit(5)->all();
                            ?>
                            <?php if ($planned_pays):?>
                                <?php foreach ($planned_pays as $t):?>
                                    <tr>
                                        <td class="b-more-info-cell">
                                            <?= Html::a('', ['/pay-planned/update', 'id' => $t->pp_id], ['class' => 'b-show-pay-icon'])?>
                                        </td>
                                        <td><?= $t->provider->name?></td>
                                        <td class="b-price-cell"><?= $t->pp_summ.' '. $t->pp_currency;?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td class="b-more-info-cell" colspan="3"><?= Yii::t('main/menu', 'НЕТ_ЗАПЛАНИРОВАННЫХ')?></td></tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="b-clear"></div>
    </div>
    
    <div class="b-auth-menu-wrapper">
        <ul class="b-auth-menu">
            <li><a href="/"><div class="b-icon-image"><img src="/images/auth/payment-icon.png"/></div><span class="b-auth-menu-title"><?= Yii::t('main/menu', 'ОПЛАТА')?></span></a></li>
            <li><a href="/webmoneypay/default/wm-balance"><div class="b-icon-image"><img src="/images/auth/refill-icon.png"/></div><span class="b-auth-menu-title"><?= Yii::t('main/menu', 'ПОПОЛНЕНИЕ')?></span></a></li>
            <li><a href="/user/transfer"><div class="b-icon-image"><img src="/images/auth/transfers-icon.png"/></div><span class="b-auth-menu-title"><?= Yii::t('main/menu', 'ПЕРЕВОДЫ')?></span></a></li>
            <li><a href="/payments/history"><div class="b-icon-image"><img src="/images/auth/operation-icon.png"/></div><span class="b-auth-menu-title"><?= Yii::t('main/menu', 'ОПЕРАЦИИ')?></span></a></li>
            <li><a href="#"><div class="b-icon-image"><img src="/images/auth/cards-cion.png"/></div><span class="b-auth-menu-title"><?= Yii::t('main/menu', 'БАНКОВСКИЕ_КАРТЫ')?></span></a></li>
            <li><a href="/user/bonus"><div class="b-icon-image"><img src="/images/auth/bomus-icon.png"/></div><span class="b-auth-menu-title"><?= Yii::t('main/menu', 'БОНУСЫ')?></span></a></li>
        </ul>
    </div>
    <?php endif;?>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div style="width: 960px; margin: 0px auto;"><?= Alert::widget() ?></div>

        <?= $content ?>

<footer>
        <div class="b-footer-wrapper">
            <div class="b-footer">
                <div class="footer-block">
                    <h3><?= Yii::t('footer/menu', 'МОЙ_PAYEER')?></h3>
                    <ul class="footer-menu">
                        <li><a href="#"><?= Yii::t('footer/menu', 'СОЗДАТЬ_КОШЕЛЕК')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'ПЕРЕВОД_СРЕДСТВ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'ПОПОЛНИТЬ_СЧЕТ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'ВЫВЕСТИ_СРЕДСТВА')?></a></li>
                    </ul>
                </div>
                <div class="footer-block">
                    <h3><?= Yii::t('footer/menu', 'ПОМОЩЬ')?></h3>
                    <ul class="footer-menu">
                        <li><a href="#"><?= Yii::t('footer/menu', 'СЛУЖБА_ПОДДЕРЖКИ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'ВОССТАНОВЛЕНИЕ_ПАРОЛЯ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'ВОПРОС_ОТВЕТ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'CMS_МОДУЛИ')?></a></li>
                    </ul>
                </div>
                <div class="footer-block">
                    <h3><?= Yii::t('footer/menu', 'ИНФОРМАЦИЯ')?></h3>
                    <ul class="footer-menu">
                        <li><a href="#"><?= Yii::t('footer/menu', 'НАШИ_ОТЛИЧИЯ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'КОМИССИИ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'ЮР_ИНФОРМАЦИЯ')?></a></li>
                        <li><a href="#"><?= Yii::t('footer/menu', 'КОНТАКТЫ')?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="b-copyright-wrapper">
            <div class="b-copyright">
                <a class="b-footer-logo" href="/"></a>
                <span class="b-copyright-text"><?= Yii::t('footer/menu', 'ЭЛЕКТРОННАЯ_ПЛАТЕЖНАЯ_СИСТЕМА_№1_В_АЗЕРБАЙДЖАНЕ')?><br />
                    © 2015 All rights reserved</span>
                <div class="b-footer-social">
                    <span class="b-social-text"><?= Yii::t('footer/menu', 'ОСТАВАЙТЕСЬ_НА_СВЯЗИ')?>:</span>
                    <a target="_blank" class="b-tw-icon" href="#"></a>
                    <a target="_blank" class="b-ok-icon" href="#"></a>
                    <a target="_blank" class="b-vk-icon" href="#"></a>
                    <a target="_blank" class="b-fb-icon" href="#"></a>
                </div>
            </div>
        </div>
</footer>

    
    
    
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
