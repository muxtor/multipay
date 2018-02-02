<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use common\models\Payments;

/* @var $this yii\web\View */
/* @var $model common\models\PayTemplate */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="b-content">
    <div class="b-content-full-width">
        <h1><?= $model->provider->name?></h1>
        <div class="b-content-full-width-inner">
            <div class="b-payments-edit-logo">
                <?= Html::img('/uploads/providers-logo/'.$model->provider->logo, ['alt' => $model->provider->name]);?>
            </div>
            <div class="b-payments-edit-info">
                <span class="b-fav-icon-text"><?= Yii::t('pay/template', 'ИНФОРМАЦИЯ')?></span>
                <div class="b-field">
                    <label><?= Yii::t('pay/template', 'НАЗВАНИЕ')?></label><?= $model->provider->name?>
                </div>
                <?php $form = ActiveForm::begin(); ?>
                <div class="b-field">
                    <label class="b-label-fixed-width"><?= Yii::t('pay/template', 'ВВЕДИТЕ_ЛОГИН')?>:</label>
                    <?= $form->field($model, 'pt_accaunt', [])->textInput(['class' => 'b-input'])->label(false)?>
                </div>
                <div class="b-field">
                    <label class="b-label-fixed-width"><?= Yii::t('pay/template', 'СПОСОБ_ОПЛАТЫ')?>:</label>
                    <?= $form->field($model, 'pt_system', ['inputOptions' => ['class' => 'b-input']])
                        ->dropDownList(Payments::systemPayedLabelsUser(),
                            ['prompt' => Yii::t('payment', 'ВЫБЕРИТЕ_СПОСОБ_ОПЛАТЫ')])
                        ->label(false)?>
                </div>
                <div class="b-field">
                    <label class="b-label-fixed-width"><?= $model->getAttributeLabel('pt_summ')?>:</label>
                    <?= $form->field($model, 'pt_summ',[])
//                                                ->widget(MaskedInput::className(),[
//                                                    'mask' => '9{*}.9{2}',
//                                                    'options' => ['class' => 'b-input', 'style' => 'width: 100%;'],
//                                                    'type' => 'text'])
                        ->textInput(['class' => 'b-input'])->label(false)?>
                </div>
                <div class="b-field">
                    <label class="b-label-fixed-width"><?= $model->getAttributeLabel('pt_currency')?>:</label>
                    <span id="pay-template-currency"> <?= $model->pt_currency?></span>
                    <?= Html::activeHiddenInput($model, 'pt_currency', ['id' => 'payment-template-currency']);?>
                </div>
                <br><br>
                <div class="b-btn-navs">
                    <input class="b-submit b-green" value="<?= Yii::t('pay/template', 'СОХРАНИТЬ')?>" type="submit">
                    <a class="btn b-gray" href="/pay-templates/index"><?= Yii::t('pay/template', 'НАЗАД')?></a>
                    <?= Html::a('', ['/pay-templates/delete', 'id' => $model->pt_id], ['class' => 'b-btn-delete', 'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ]]);?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="b-clear"></div>
        </div>
    </div>
    <br>
</div>
<div class="b-clear"></div>
<?= \frontend\widgets\Tabs\TabsWidget::widget();?>
<?= \frontend\widgets\Logos\LogosWidget::widget();?>
