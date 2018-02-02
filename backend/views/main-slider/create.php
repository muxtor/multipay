<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MainSlider */

$this->title = Yii::t('MainSlider', 'Create Main Slider');
$this->params['breadcrumbs'][] = ['label' => 'Main Sliders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-slider-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
