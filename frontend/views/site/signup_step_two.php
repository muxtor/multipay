<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//use Yii;
//$this->title = Yii::t('SignupForm', 'Регистрация');
//$this->params['breadcrumbs'][] = $this->title;
$session = \Yii::$app->session;
Yii::t('signup', 'Необходимо заполнить password');
Yii::t('signup', 'Необходимо заполнить sms код');
Yii::t('signup', 'Необходимо заполнить {attribute}');
?>

<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-register-wrapper">
            <h1><?=$this->title; ?></h1>
                <div class="b-form-block">
                <?php $form = ActiveForm::begin([
                        'id' => 'form-signup', 
                        'options' => ['class' => 'b-form'],
//                        'fieldConfig' => [
//                            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
//                            'horizontalCssClasses' => [
//                                'label' => '',
//                                'input' => 'b-input',
//                                'offset' => 'col-sm-offset-4',
//                                'wrapper' => 'col-sm-8',
//                                'error' => 'b-error b-true',
//                                'hint' => '',
//                            ],
//                        ],
                    ]); ?>

                <?= $form->field($model, 'phone')
                        ->hiddenInput()
                        ->label(FALSE); ?>
                    <style>
                        .has-error .b-error,.has-error .b-hint{ display: block!important;}
                        .b-hint .help-block.help-block-error{ margin: 0!important;text-align: left; white-space: pre-wrap;}
                        .b-error .help-block.help-block-error{ margin: 0!important; color: #cd3030;}
                    </style>
                <?= $form->field($model, 'password', [
                        'template' => "\n<div class=\"b-field b-bottom\">{input}{hint}\n<div class=\"b-hint\">{error}</div></div>"//
                    ])->passwordInput(['class' => 'b-input'])//->error(['message'=>'asdasd','class' => 'b-hint']) ?>

                <?= $form->field($model, 'verifyPassword', [
                        'template' => "\n<div class=\"b-field b-bottom\">{input}{hint}<div class=\"b-error\">{error}</div>\n</div>\n"
                    ])->passwordInput(['class' => 'b-input']) ?>

                    <?php $sendsmsform = Html::a(Yii::t('yii', 'Прислать код повторно'),'site/resend-sms', [
                        'title' => Yii::t('yii', 'Прислать код повторно'),
                        'onclick'=>"$.ajax({
                                type     :'POST',
                                cache    : false,
                                url  : '/site/resend-sms',
                                success  : function(data) {
                                    $('#smsMessage').html(data.mes);
                                    $('#smscode').html(data.code);
                                    function timer(time){ if(time>0){ setTimeout (function (){ time = time - 1; timer(time); $('#timer').html(time); }, 1000); }else{ $('#timer').html(time); $('#smsMessage').html(''); } }
                                    if(data.time) timer(data.time);
                                }
                                });return false;",
                    ]);
                    ?>
                <p><?=Yii::t('signup','Мы отправили вам SMS с кодом
                                    для завершения регистрации')?></p>
                    <?= $form->field($model, 'sms_code',[
                    'template' => "\n<div class=\"b-field b-bottom\">{input}{hint}<div class=\"b-error\">{error}</div>\n
                        <div class=\"b-recode\">
                            ".$sendsmsform."
                            <div></div>\n
                            <span id=\"smsMessage\" style=\"width: 250px; display: block; white-space: normal;\"></span>
                            <p></p>\n
                        </div>\n
                    </div>\n
                    "
                    ])->textInput(['class' => 'b-input'])->label(FALSE) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('signup','Подтвердить'), ['class' => 'b-submit', 'name' => 'signup-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
