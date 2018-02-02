<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;


$this->registerJs(<<<JS
    $(window).load(function(){
        $("#transferform-sum_to").keyup(function(){
            var r = Math.ceil(1.005*parseFloat($(this).val())*100)/100;
            if (isNaN(r)) {
                $('#transferform-sum_from').val( 0 );
            } else {
                $('#transferform-sum_from').val( r );
            }
        });
    });
JS
, yii\web\View::POS_END);

$this->title = Yii::t('view/user/transfer', 'Переводы - MultiPay');
?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-side-bar">
            <div class="b-side-bar-menu">
                <?=$this->render('_transfer_menu')?>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-settings-wrapper">
                <h1><?= Yii::t('transfer', 'Выставить счет')?></h1>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-transfer', 
                    'fieldConfig' => [
                        'errorOptions' => [
                            'tag' => 'div',
                            'class' => 'b-error b-true'
                        ],
                    ],
                    ]); ?>
                <table class="b-settings-table">
                    <tr>
                        <td><?= $transfer->getAttributeLabel('phone')?>:</td>
                        <td><div class="b-field b-flag-field">
                                <?= $form->field($transfer, 'phone')->textInput(['class' => 'b-input b-flag'])->label(FALSE); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?= $transfer->getAttributeLabel('sum_to')?>:</td>
                        <td>
                            <div class="b-field">
                                <?= $form->field($transfer, 'sum_to', [
                                            'template' => '{input}<span class="b-currency">AZN</span><div class="b-clear"></div>{error}'
                                        ])
                                        ->textInput(['class' => 'b-input b-sum'])
                                        ->label(FALSE); ?>
<!--                                <input type="text" class="b-input b-sum" name="sum1" class="b-input" value=""/>
                                <input type="text" name="sum2" class="b-input b-coins" value=""/>-->
                            </div>
                        </td>
                    </tr>
                    <?= $form->field($transfer, 'sum_from')->hiddenInput(['class' => 'b-input b-sum b-readonly', 'readonly'=>TRUE])->label(FALSE); ?>
                    <tr>
                        <td><?= $transfer->getAttributeLabel('comment')?>:</td>
                        <td><div class="b-field">
                                <?= $form->field($transfer, 'comment')->textarea(['class' => 'b-textarea'])->label(FALSE); ?>
                                <!--<textarea class="b-textarea"></textarea>-->
                            </div></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div class="b-field b-left"><input type="submit" class="b-submit b-green" value="<?= Yii::t('transfer/button', 'Выставить счет')?>"/></div></td>
                    </tr>
                </table>
                <?php ActiveForm::end(); ?>
                <br />
            </div>
        </div>
    </div>
</div>
<div class="b-clear"></div>

<script>
//    $(window).load(function(){
//        $("#transferform-sum_to").keyup(function(){
//            $('#transferform-sum_from').val( 1.005*$(this).val() );
//        });
//    });
//function calculateBallanc(){
//    var v = $('#transferform-sum_to').val();
//    var r = 1.005;
//    var result = v*r;
//    if (result) {
//        $('#transferform-sum_from').val(result);
//    } else {
//        $('#transferform-sum_from').html('0');
//    }
//}

</script>