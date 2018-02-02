<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('SignupForm', 'Регистрация');
//$this->params['breadcrumbs'][] = $this->title;
if (!empty($model)):
?>

        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-register-wrapper">
                    <h1><?=$this->title; ?></h1>
    <div class="b-form-block">
                <?php $form = ActiveForm::begin([
                        'id' => 'form-signup', 
                        'options' => ['class' => 'b-form'],
                    ]); ?>
<div class="flags-container">
    <?php if(empty($model->phone)){ $readonly=['readonly'=>true]; }else{ $readonly=[]; } ?>
                <?= $form->field($model, 'phone', [
                        'template' => "{input}<img class='flag to-flag-img' style='cursor: pointer;' src=\"/images/az.png\" alt=\"Азербайджан\">",
                        'options' => [
                                'class' => 'b-field',
                            ],
                    ])->textInput(['class' => 'to-flag b-input b-flag'])//, $readonly
                        ->label(FALSE); ?>
        <?php $this->registerJs(
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
        <div class="flags fr">
            <ul>
                <?php
                $flags = common\models\Country::find()->where(['not',['tel_mask'=>null]])->all();
                if($flags!=null){
                    $selected = '';
                    foreach($flags as $key => $flag){
                        //reset($flags); //if ($key === key($flags)){ $selected = 'selected'; }else{ $selected='';}
                        $selected = '';
                        
                        if ($flag->tel_code == '+' . substr($model->phone, 0, strlen($flag->tel_code) - 1)) {
                            $selected = 'selected';
                        }
                        ?>
                        <li class="in-flag <?=$selected?>" data-name="<?=$flag->name?>" data-img="<?=Yii::$app->params['flags'].strtolower($flag->iso)?>.png" data-mask="<?=$flag->tel_mask?>" data-code="<?=$flag->tel_code?>"><img src="<?=Yii::$app->params['flags'].strtolower($flag->iso)?>.png"> <?=$flag->name?> <span class="pull-right"><?=strtolower($flag->tel_code)?></span></li>
                    <?php }
                }
                ?>
            </ul>
        </div>
</div><br>
                <div class="g-recaptcha" data-sitekey="<?=Yii::$app->params['recaptchaSitekey']?>"></div>
                <?php
//                   echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [
//                        'template' => "\n<div class=\"b-filed b-left\">{input}\n</div>\n<div class=\"b-filed b-left b-captcha\">{image}\n</div>"
//                    ])->label(FALSE)
                    ?>
                <br>
                <div class="b-rules" style="width: 130%;">
                    <?= $form->field($model, 'confirm')->checkbox()->label('<span>'.Yii::t('signup','Я согласен с условиями договора').'</span>') ?>
                </div>
                <div class="form-group" style="padding-top: 25px;">
                    <?= Html::submitButton(Yii::t('SignupForm', 'Зарегистрироваться'), ['class' => 'b-submit', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
    </div>
                    
                    
                </div>
            </div>
        </div>
<?php else: ?>
<div class="alert alert-danger">
    <?= Yii::t('user', 'Запись с таким номером телефона уже существует') ?>
</div>
<?php endif; ?>