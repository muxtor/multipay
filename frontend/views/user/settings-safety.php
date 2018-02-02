<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->registerJs(<<<JS
     $(document).ready(function(){
            $('.b-select').styler();
            $('.b-checkbox').styler();
            $('.b-radio').styler();
        });
JS
        , yii\web\View::POS_READY);
?>




<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-side-bar">
            <div class="b-side-bar-menu">
                <?=$this->render('_settings_menu', ['active' => 1])?>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-settings-wrapper">
                <h1><?=Yii::t('user/safety', 'Авторизация')?></h1>
                <div class="b-settings-form">
                <?php
                $form = ActiveForm::begin([
                            'options' => ['class' => 'form_complete'],
                ]);
                ?>
                            <div class="b-field">
                                <label><?=Yii::t('user/safety', 'Выслать проверочный код')?></label>
                                <?=Html::activeDropDownList($user, 'verification_code_send', common\models\User::codeSendLabels(), ['class'=>'b-select'])?>
                            </div>
                            <div class="b-field-radio">
                                <span><?=Yii::t('user/safety', 'Способ подтверждения')?></span><br />
                                <?=Html::activeRadioList($user, 'verification_code_method', common\models\User::codeMathodLabels(), ['class'=>'b-radio'])?>
<!--                                <input class="b-radio" type="radio" name="method"/><label class="b-radio-label">SMS</label>
                                <input class="b-radio" type="radio" name="method"/><label class="b-radio-label">E-mail</label>-->
                            </div>
                            <div class="b-field-btn">
                                <input type="submit" class="b-submit" value="<?=Yii::t('user/safety', 'Сохранить')?>"/>
                            </div>
                <?php ActiveForm::end(); ?>        
                </div>
                <h2><?=Yii::t('user/safety', 'Статистика входов')?></h2>
            </div>
            <div class="b-table-wrapper">
                <table class="b-table">
                    <thead>
                        <tr>
                            <th><?=Yii::t('user/safety', 'Дата и время')?></th>
                            <th class="b-text-center"><?=Yii::t('user/safety', 'Приложение')?></th>
                            <th class="b-text-center"><?=Yii::t('user/safety', 'IP адресс')?></th>
                            <th class="b-text-center"><?=Yii::t('user/safety', 'Местонахождение')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($ulstats):?>
                            <?php $i = 1; ?>
                            <?php foreach ($ulstats as $uls):?>
                            <tr <?= $i%2==0 ? ' class="b-gray"' : '';?>>
                                <td><?=\Yii::$app->formatter->asDate($uls->uls_date_visit, 'dd MMMM y'); ?></td>
                                <td class="b-text-center"><?=$uls->uls_app; ?></td>
                                <td class="b-text-center"><?=$uls->uls_IP; ?></td>
                                <td class="b-text-center"><?=$uls->uls_location; ?></td>
                            </tr>
                            <?php $i++;?>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>


<script type="text/javascript">
    function ShowTarif(id) {
        $('.b-bonus-tarif li a').removeClass('b-active');
        $('#b-tarif-btn-' + id).addClass('b-active');
        $('.b-tarifs-block').removeClass('b-active');
        $('#b-tarif-' + id).addClass('b-active');
    }
</script>