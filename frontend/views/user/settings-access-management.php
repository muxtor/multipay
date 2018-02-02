<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>




<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-side-bar">
            <div class="b-side-bar-menu">
                <?=$this->render('_settings_menu', ['active' => 2])?>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-settings-wrapper">
                <h1><?=Yii::t('user/access', 'Смена пароля')?></h1>
                <?php
                $form = ActiveForm::begin([
                            'options' => ['class' => 'form_complete'],
                ]);
                ?>
                <table class="b-settings-table">
                    <tr><td><?=Yii::t('user/access', 'Старый пароль')?></td>
                        <td>
                            <div class="b-field"><?= Html::activePasswordInput($user, 'old_password', ['class' => 'b-input']) ?></div>
                            <div class="b-field"><?= Html::error($user, 'old_password') ?></div>
                        </td>
                    </tr>
                    <tr><td><?=Yii::t('user/access', 'Новый пароль')?></td>
                        <td>
                            <div class="b-field"><?= Html::activePasswordInput($user, 'new_password', ['class' => 'b-input']) ?></div>
                            <div class="b-field"><?= Html::error($user, 'new_password') ?></div>
                        </td>
                    </tr>
                    <tr><td><?=Yii::t('user/access', 'Повторите пароль')?></td>
                        <td>
                            <div class="b-field"><?= Html::activePasswordInput($user, 'verifyPassword', ['class' => 'b-input']) ?></div>
                            <div class="b-field"><?= Html::error($user, 'verifyPassword') ?></div>
                        </td>
                    </tr>
                    <tr><td colspan="2"><div class="b-field b-right"><input type="submit" class="b-submit b-green" value="<?=Yii::t('user/access', 'Сохранить')?>"/></div></td></tr>
                </table>
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