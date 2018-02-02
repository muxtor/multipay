<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */

$this->title = Yii::t('faq/model', 'Обновить запись') . ' ' . $model->faq_id;
$this->params['breadcrumbs'][] = ['label' => 'Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->faq_id, 'url' => ['view', 'id' => $model->faq_id]];
$this->params['breadcrumbs'][] = Yii::t('faq/model', 'Обновить');
?>
<div class="faq-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
