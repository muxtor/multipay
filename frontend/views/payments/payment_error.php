<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;
?>
<div class="b-content">
    <div class="b-content-full-width">
        <h3><?= Yii::t('payment', 'ОШИБКА_ПРИ_ВЫПОЛНЕНИИ_ОПЕРАЦИИ')?></h3>
    </div>
</div>
<div style="height: 0; width: 0; opacity: 0;">
    <?= Html::beginForm('', 'post', ['id' => 'provider-search-form'])?>
    <?= Html::textInput('search','',['placeholder' => Yii::t('app', 'Поиск Организации...'), 'class' => 'b-search', 'id' => 'provider-search-input'])?>
    <a class="b-search-icon" href="javascript:void(0)"></a>
    <?= Html::submitButton('Submit', ['style' => 'position: absolute; margin-left: -9999px;'])?>
    <?= Html::endForm();?>
</div>

