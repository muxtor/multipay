<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Api */

$this->title = Yii::t('api', 'API - Редактирование');
?>
<div class="b-content-wrapper">
    <div class="b-content">
        <div class="b-categorys">
            <div class="b-settings-wrapper">

                <h1><?= Html::encode($this->title) ?></h1>

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
