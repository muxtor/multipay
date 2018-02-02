<?php

use yii\widgets\ActiveForm;
use frontend\widgets\Tabs\TabsWidget;
use common\models\Country;

/* @var $this yii\web\View */

$this->title = 'MultiPay';

?>

<?php if (\Yii::$app->user->isGuest) { ?>
    <div class="b-slider-wrapper">
        <div class="b-slider-block">
            <div class="b-register-block">
                <span><?=Yii::t('index','Зарегистрируйтесь, и получите свой первый бонус!');?></span>
                <div class="b-form">
                    <?php $formUser = ActiveForm::begin(['id' => 'registration-form']); ?>
                    <div class="flags-container" >

                    <?php echo $formUser->field($model_signup, 'phone', [
                        'template' => "{input}<img class='flag to-flag-img' style='cursor: pointer;' src=\"/images/az.png\" alt=\"Азербайджан\">",
                        'options' => [
                            'class' => 'b-field b-first'
                        ]
                    ])->textInput(['class' => 'to-flag b-input b-flag'])->label(FALSE);

                    $int = (int)(common\models\SliderInterval::find()->one()->interval_main * 1000);
                    $this->registerJs(
                        "jQuery(document).ready(function(){
                            thisis = jQuery('.flags.fr .in-flag.selected');
                            jQuery('.to-flag-img').attr('src',thisis.attr('data-img'));
                            jQuery('.to-flag-img').attr('alt',thisis.attr('data-name'));
                            jQuery('.to-flag-img').attr('title',thisis.attr('data-name'));
                            jQuery('.to-flag').attr('placeholder',thisis.attr('data-code'));
                            jQuery('#signupform-phone').mask(thisis.attr('data-mask'));

                            jQuery('img.flag').on('click',function(){
                                jQuery('.flags.fr').toggle();
                            });

                            jQuery('.flags.fr .in-flag').on('click',function(){
                                jQuery('.flags.fr .in-flag').removeClass('selected');
                                jQuery(this).addClass('selected');
                                jQuery('.to-flag-img').attr('src',jQuery(this).attr('data-img'));
                                jQuery('.to-flag-img').attr('alt',jQuery(this).attr('data-name'));
                                jQuery('.to-flag-img').attr('title',jQuery(this).attr('data-name'));
                                jQuery('.to-flag').attr('placeholder',jQuery(this).attr('data-code'));
                                jQuery('#signupform-phone').mask(jQuery(this).attr('data-mask'));
                                jQuery('.flags.fr').toggle();
                            });
                        });"
                        , yii\web\View::POS_END);
                    ?>
                        <?php
                        if(isset($_COOKIE['country_iso'])){
                            $iso = $_COOKIE['country_iso'];
                            $country = Country::find()->where(['iso'=>$iso])->one();
                            if($country!=null){
                                $this->registerJs(
                                "jQuery(document).ready(function(){
                                    jQuery('.to-flag-img').attr('src','/images/flags/16/".strtolower($country->iso).".png');
                                    jQuery('.to-flag-img').attr('alt','".$country->name."');
                                    jQuery('.to-flag-img').attr('title','".$country->name."');
                                    jQuery('.to-flag').attr('placeholder','".$country->tel_code."');
                                    jQuery('#signupform-phone').mask('".$country->tel_mask."');
                                });"
                                    , yii\web\View::POS_END
                                );
                            }
                        }
                        ?>
                    <div class="flags fr">
                        <ul>
                            <?php
                            $flags = common\models\Country::find()->where(['not',['tel_mask'=>null]])->all();
                            if ($flags != null) {
                                $selected = '';
                                foreach ($flags as $key => $flag) {
                                    reset($flags);
                                    if ($key === key($flags))
                                        $selected = 'selected';
                                    else
                                        $selected='';
                                    ?>
                                    <li class="in-flag <?=$selected;?>" data-name="<?=$flag->name;?>" data-img="<?=Yii::$app->params['flags'] . strtolower($flag->iso);?>.png" data-mask="<?=$flag->tel_mask;?>" data-code="<?=$flag->tel_code;?>">
                                        <img src="<?=Yii::$app->params['flags'].strtolower($flag->iso);?>.png"> <?=$flag->name;?>
                                        <span class="pull-right"><?=strtolower($flag->tel_code);?></span>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ul>
                    </div>

                        <div class="b-field"><input class="b-submit" type="submit" value="<?=Yii::t('index','Зарегистрироваться')?>"/></div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="b-slider">
                <ul>
                    <?php if (isset($main_sliders)) {
                            $int = (int)(common\models\SliderInterval::find()->one()->interval_main * 1000);
                            $this->registerJs(
                                '$(document).ready(function(){
                                        $(".b-slider").jcarouselAutoscroll({
                                        interval: '.($int ? $int : 10000).',
                                    });
                                });'
                            , yii\web\View::POS_END);
                         foreach ($main_sliders as $main_slider) { ?>
                            <li>
                                <div class="b-image">
                                    <img src="/uploads/main-slider/<?=$main_slider->slider_image_url;?>" alt="<?=$main_slider->slider_title;?>">
                                </div>
                                <div class="b-desc">
                                    <h2><?=$main_slider->slider_title;?></h2>
                                    <p><?=$main_slider->slider_text;?></p>
                                </div>
                            </li>
                         <?php
                         }
                    } ?>
                </ul>
            </div>              
            <p class="b-slider-pagination"></p>              
        </div>
                    <!--<a href="#" class="control-prev"></a>-->
                    <!--<a href="#" class="control-next"></a>-->
    </div>
<?php } ?>


<div class="b-content-wrapper">
    <div class="b-content">
        <?= frontend\widgets\ProvidersList\ProvidersSidebarWidget::widget();?>
        <div class="b-categorys">
            <?= frontend\widgets\ProvidersList\ProvidersCategoriesHomeWidget::widget();?>
        </div>
    </div>
</div>
<div class="b-clear"></div>
<div class="b-clear"></div>
<?= TabsWidget::widget();?>
<div class="b-logos-wrapper">
    <div class="b-logos">
        <div class="b-logos-corousel-wrapper">
            <style> .p_item_c img{ transition: opacity 0.3s ease-in; opacity: 0.3; max-width: 100px;}
                .p_item_c:hover img {opacity:1;}
                .p_item_c{ display: table-cell; width: 80px; height: 85px; vertical-align: middle; overflow: hidden; }
            </style>
            <div class="b-logos-corousel">
                <?php if (isset($partners)) {
                    $int = (int)(common\models\SliderInterval::find()->one()->interval_partner * 10000);
                    $this->registerJs(
                        '$(document).ready(function(){
                            $(\'.b-logos-corousel\').jcarouselAutoscroll({
                                interval: '.($int ? $int : 10000).',
                                target: \'+=1\',
                            });
                        });'
                        , yii\web\View::POS_END);
                ?>
                <ul>
                    <?php foreach ($partners as $partner) { ?>
                        <li>
                            <style>
                                .partner_<?=$partner->id;?>{ background: url('/uploads/partners/<?=$partner->image;?>') no-repeat; <?=$partner->css;?> }
                                .partner_<?=$partner->id;?>:after{ background: url('/uploads/partners/<?=$partner->image;?>') no-repeat; content: ""; background-position: 0 0; opacity: 0; position: absolute; top: 0; left: 0; bottom: 0; right: 0; transition: opacity 0.3s ease-in;}
                                .partner_<?=$partner->id;?>:hover:after {opacity:1;}
                            </style>
                             <a target="_blank" class="p_item_c" href="<?=$partner->site_link;?>">
                                 <img src="/uploads/partners/<?=$partner->image;?>" alt="">
                             </a>
                            <!--<span class="partner_<?=$partner->id;?>"></span>-->
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
            <a href="#" class="control-prev" data-jcarouselcontrol="true"></a>
            <a href="#" class="control-next" data-jcarouselcontrol="true"></a>
        </div>
    </div>
</div>