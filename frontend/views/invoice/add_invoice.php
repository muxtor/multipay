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
                <ul>
                    <li><a href="/invoice/index"><i class="fa-perevod-zapros"></i><?= Yii::t('invoice', 'ВХОДЯЩИЕ') ?>
                        </a></li>
                    <li><a href="/invoice/out-invoice"><i class="fa-refresh"></i><?= Yii::t('invoice',
                                'ВЫСТАВЛЕННЫЕ') ?></a></li>
                    <li><a href="/invoice/add-invoice"><i class="fa-file-text-o"></i><?= Yii::t('invoice',
                                'ВЫСТАВИТЬ_СЧЕТ') ?></a></li>
                    <li><a href="history-invoice"><i class="fa-tasks"></i><?= Yii::t('invoice', 'ИСТОРИЯ') ?></a></li>
                </ul>
            </div>
        </div>
        <div class="b-categorys">
            <div class="b-settings-wrapper">
                <h1><?= Yii::t('invoice', 'ВЫСТАВИТЬ_СЧЕТ') ?></h1>
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
                    <tbody>
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
                        <td><div class="b-field b-left"><input type="submit" class="b-submit b-green" value="Выставить счет"/></div></td>
                    </tr>
                    </tbody>
                </table>
                <?php ActiveForm::end(); ?>
                <br />
            </div>

        </div>
    </div>
</div>
<div class="b-clear"></div>
<?= \frontend\widgets\Tabs\TabsWidget::widget() ?>
<?= \frontend\widgets\Logos\LogosWidget::widget() ?>
