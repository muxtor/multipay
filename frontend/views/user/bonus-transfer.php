<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//$this->registerJs(<<<JS
//            function ShowTarif(id) {
//                $('.b-bonus-tarif li a').removeClass('b-active');
//                $('#b-tarif-btn-' + id).addClass('b-active');
//                $('.b-tarifs-block').removeClass('b-active');
//                $('#b-tarif-' + id).addClass('b-active');
//            }
//JS
//, yii\web\View::POS_END);

$this->title = Yii::t('view/user/reclame', 'Переводы - MultiPay');
?>
        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-payment-form">
            <?php $form = ActiveForm::begin([
                    'id' => 'form-transfer', 
//                    'enableClientValidation' => true,
                    'fieldConfig' => [
                        'errorOptions' => [
                            'tag' => 'div',
                            'class' => 'b-error b-true'
                        ],
                    ],
            ]); ?>
                <table class="b-table">
                    <tbody>
                        <tr>
                            <td class="b-name"><?= $model->getAttributeLabel('bonus')?>:</td>
                            <td>
                                <div class="b-field b-left">
                                    <?= $form->field($model, 'bonus')->textInput(['class' => 'b-input', 'onKeyup' => 'calculateBallanc()'])->label(FALSE); ?>
                                </div>
                            </td>
                        </tr>
                        <tr><td><?=Yii::t('paysystem','Получите на баланс');?> </td><td><div class="b-field"><span id="summ">0</span> AZN</div></td></tr>
                        <tr>
                            <td class="b-name"></td>
                            <td>
                                <div class="b-field b-coins">
                                    <?= Html::submitButton(Yii::t('payment', 'ПЕРЕВЕСТИ'), ['class' => 'b-submit']) ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <?php ActiveForm::end();?>
                    </div>
            </div>
        </div>
        <div class="b-clear"></div>
        
        
<script>
var rates = <?=json_encode($settings->get('AZN_to_balance', 'currency'));?>;
console.log(rates);
function calculateBallanc(){
    var v = $('#bonustransferform-bonus').val();
    var result = v/rates;
    if (result) {
        $('#summ').html(result);
    } else {
        $('#summ').html('0');
    }
}

</script>        