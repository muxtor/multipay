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
?>

        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-register-wrapper">
                    <h1><?=$this->title; ?></h1>
    <div class="b-form-block">
                <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['class' => 'b-form'],]); ?>

                <?= $form->field($model, 'phone')
                        ->hiddenInput()
                        ->label(FALSE); ?>

                <?= $form->field($model, 'password', [
                        'template' => "\n<div class=\"b-field b-bottom\">{input}{hint}\n</div><div class=\"b-error b-true\">{error}</div>"
                    ])->passwordInput(['class' => 'b-input'])
                            ->hint('Пароль должен содержать:<br />Не менее 8 знаков<br />Цифры и буквы латинского алфавита', ['class' => 'b-hint b-active']) ?>

                <?= $form->field($model, 'verifyPassword', [
                        'template' => "\n<div class=\"b-field b-bottom\">{input}{hint}\n</div>\n{error}"
                    ])->passwordInput(['class' => 'b-input']) ?>
            <p>Мы отправили вам SMS с кодом 
                                для завершения регистрации</p>
                <?= $form->field($model, 'sms_code')->textInput(['class' => 'b-input'])->label(FALSE) ?>

            <!--<div class="b-recode"><a href="#" class="disabled">Прислать код повторно</a></div>-->
            <div class="b-recode">
                    <?php echo
                     Html::a(Yii::t('yii', 'Прислать код повторно'),'site/resend-sms', [
                        'title' => Yii::t('yii', 'Прислать код повторно'),
                            'onclick'=>"$.ajax({
                                        type     :'POST',
                                        cache    : false,
                                        url  : 'site/resend-sms',
                                        success  : function(data) {
                                            $('#smsMessage').html(data.mes);
                                            $('#smscode').html(data.code);
                                        }
                                        });return false;",
                                        ]);
                    ?>
                <span id="smsMessage"></span>
            
            </div>

                <div class="form-group">
                    <?= Html::submitButton('Подтвердить', ['class' => 'b-submit', 'name' => 'signup-button']) ?>
                </div>


            <?php ActiveForm::end(); ?>
    </div>
                    
<!--                    <form action="" method="post" class="b-form">
                        <div class="b-form-block">
                            <div class="b-field b-bottom">
                                <input class="b-input" name="password" type="password" value=""/>
                                <div class="b-hint b-active">Пароль должен содержать:<br />Не менее 8 знаков<br />Цифры и буквы латинского алфавита</div>
                            </div>
                            <div class="b-field  b-bottom">
                                <input class="b-input" name="password" type="password" value=""/>
                                <div class="b-error b-true">Пароли не совпадают</div>
                            </div>
                            <p>Мы отправили вам SMS с кодом 
                                для завершения регистрации</p>
                            <div class="b-field  b-bottom">
                                <input class="b-input" name="security" type="text" value=""/>
                                <div class="b-recode"><a href="#">Прислать код повторно</a></div>
                            </div>
                            <input class="b-submit" type="submit" value="Подтвердить"/>
                        </div>
                    </form>-->
                </div>
            </div>
        </div>
