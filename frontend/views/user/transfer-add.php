<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;


$this->registerJs(<<<JS
    $(window).load(function(){
        $("#transferform-sum_to").keyup(function(){
//            $(this).val(parseFloat($(this).val()*100)/100);
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

$this->title = Yii::t('view/user/reclame', 'Переводы - MultiPay');
?>
<?php $this->registerJs(
    "jQuery(document).ready(function(){
        $('[data-toggle=\"popover\"]').popover();
    });"
    , yii\web\View::POS_END);
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
                <h1><?= Yii::t('transfer', 'Перевести средства')?></h1>
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
                        <td>
                            <div class="b-field b-flag-field">
                                <?= $form->field($transfer, 'phone', [
                                    'template' => '{input}{error}'
                                ])->textInput(['class' => 'b-input b-flag'])->label(FALSE); ?>
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
                    <tr>
                        <td><?= $transfer->getAttributeLabel('sum_from')?>:</td>
                        <td><div class="b-field">
                                <?= $form->field($transfer, 'sum_from', [
                                    'template' => '{input}<span class="b-currency">AZN</span><span class="b-with-comis">'.Yii::t('transfer','с учетом комиссии 0,5%').'</span><div class="b-clear"></div>{error}'
                                ])->textInput(['class' => 'b-input b-sum b-readonly', 'readonly'=>TRUE])->label(FALSE); ?>
<!--                                <input type="text" class="b-input b-sum b-readonly" name="sum1" class="b-input" value="" readonly=""/>
                                <input type="text" name="sum2" class="b-input b-coins b-readonly" value="" readonly=""/>-->
                                    
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="b-field">
                                <?= $form->field($transfer, 'isProtected')->checkbox(['class' => 'b-checkbox'])->label(Yii::t('transfer','Защитить кодом протекции').' <a class="b-protection-link" data-trigger="focus" href="javascript:void(0);" data-container="body" data-toggle="popover" data-placement="right" data-content="'.Yii::t('transfer','Деньги получить тот, кто знает код - сообщите его отдельно; код сохранется в вашей истории').'"></a>') ?>
                                <!--<input class="b-checkbox" type="checkbox"/>-->
                                <!--<label class="b-protection-label">Защитить кодом протекции <a class="b-protection-link" href="#"></a></label>-->
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div class="b-field b-protection-code-block">
                                <label><?= $transfer->getAttributeLabel('protected_code')?></label>
                                <?= $form->field($transfer, 'protected_code')->textInput(['class' => 'b-protection-code-input'])->label(FALSE); ?>
                                <!--<input class="b-protection-code-input" type="text"/>-->
                                <!--<label>действует</label><input class="b-protection-code-input b-day-input" type="text"/><label>дней</label>-->
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?= $transfer->getAttributeLabel('comment')?>:</td>
                        <td><div class="b-field">
                                <?= $form->field($transfer, 'comment')->textarea(['class' => 'b-textarea'])->label(FALSE); ?>
                                <!--<textarea class="b-textarea"></textarea>-->
                            </div></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div class="b-field b-left"><input type="submit" class="b-submit b-green" value="<?= Yii::t('payment', 'ПРОДОЛЖИТЬ')?>"/></div></td>
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