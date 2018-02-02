<?php
use yii\db\ActiveRecord;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2 control-label',
                'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);

?>
<div class="form-group" style='margin-top: 15px;'>
    
        <?= $form->field($model, 'interval_main')->textInput(['maxlength' => true]) ?>
    
        <?//= $form->field($model, 'interval_user')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'interval_partner')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    
</div>



