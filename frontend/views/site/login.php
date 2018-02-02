<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('LoginForm', 'Войти');
?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-register-wrapper">
            <h1><?= Html::encode($this->title) ?></h1>

            <div class="b-form-block">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form', 
                    'options' => ['class' => 'b-form'],]); ?>

                <?= $form->field($model, 'phone')->textInput(['class' => 'b-input b-flag'])
                        ->label(FALSE); ?>

                <?= $form->field($model, 'password')->passwordInput(['class' => 'b-input']) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                
                <div class="g-recaptcha" data-sitekey="<?=Yii::$app->params['recaptchaSitekey']?>"></div>

                <div style="color:#999;margin:1em 0">
                    <?= Yii::t('LoginForm', 'Если Вы забыли свой пароль, Вы можете его ') ?> <?= Html::a(Yii::t('LoginForm', 'восстановить'), ['site/reset-password']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('LoginForm', 'Войти'), ['class' => 'b-submit', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>
