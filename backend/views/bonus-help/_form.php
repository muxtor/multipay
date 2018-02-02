<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Language;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\BonusHelp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonus-help-form">

    <?php $form = ActiveForm::begin([
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
    ]); ?>

    <?= $form->field($model, 'bh_alias')->dropDownList($model->getAliasLabels()) ?>
    <?= $form->field($model, 'bh_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bh_text')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => Language::getCurrent()->lang_url,
            'minHeight' => 200,
            'paragraphize' => false,
            'cleanOnPaste' => false,
            'replaceDivs' => false,
            'linebreaks' => false,
            'allowedTags' => ['p','div','a','img', 'iframe', 'b', 'br', 'ul', 'li',
                'details', 'summary', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'label', 'ol', 'strong', 'italic', 'em', 'del', 'figure'],
            'plugins' => [
                'fullscreen',
//                'video',
//                'imagemanager',
            ],
//            'imageUpload' => Url::to(['/news/image-upload']),
//            'imageManagerJson' => Url::to(['/news/images-get']),
        ]
    ])->hint('Для втавки html-кода необходимо включить режим "Код". Разрешенные теги "p", "div", "a", "img", "iframe", "b", "br", "ul", "li",
                "details", "summary", "span", "h1", "h2", "h3", "h4", "h5", "h6","label", "ol", "strong", "figure"') ?>

    <?= $form->field($model, 'bh_language')->dropDownList(ArrayHelper::map(Language::find()->all(), 'lang_url', 'lang_name')) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
