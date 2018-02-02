<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('paysystem','Пополнение кошелька');


//$this->registerJs(<<<JS
//     $(document).ready(function(){
//            $('.b-select').styler();
//        });
//JS
//        , yii\web\View::POS_READY);
?>



<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-categorys">
            <div class="b-settings-wrapper">
                <h1><?=$this->title;?></h1>
                <?php
                $form = ActiveForm::begin([
                            'options' => ['class' => 'form_complete'],
                ]);
                ?>
                    <table class="b-settings-table">
                        <tr>
                            <td><?= $model->getAttributeLabel('amount')?></td>
                            <td>
                                <div class="b-field">
                                    <?= Html::activeTextInput($model, 'amount', ['class' => 'b-input', 'onChange' => 'calculateBallanc()']) ?>
                                </div>
                                <div class="b-field"><?= Html::error($model, 'amount') ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('type')?></td>
                            <td>
                                <div class="b-field">
                                    <?=Html::activeDropDownList($model, 'type', $model->getTypeLabels() , ['class'=>'b-input', 'onChange' => 'calculateBallanc()'])?>
                                    <?=Html::error($model, 'type')?>
                                </div>
                            </td>
                        </tr>
                        <tr><td><?=Yii::t('paysystem','Сумма');?> </td><td><div class="b-field"><span id="summ">0</span> <span id="paymentSystem">WMR</span></div></td></tr>
                        <tr><td colspan="2"><div class="b-field b-right"><input type="submit" class="b-submit b-green" value="<?=Yii::t('paysystem','Продолжить');?>"/></div></td></tr>
                    </table>
                <div class="b-clear"></div>

                <?php ActiveForm::end(); ?>        
            </div>
        </div>
    </div>
</div>

<script>
var rates = <?=json_encode($wmpurses);?>;
console.log(rates);
function calculateBallanc(){
    var v = $('#paysystem-amount').val();
    var r = rates[$('#paysystem-type').val()];
    $('#paymentSystem').html($('#paysystem-type').val());
    var result = v/r;
    if (result) {
        $('#summ').html(result);
    } else {
        $('#summ').html('0');
    }
}

</script>
