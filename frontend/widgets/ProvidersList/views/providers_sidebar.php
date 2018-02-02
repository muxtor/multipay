<?php

use yii\helpers\Html;
use common\models\Providers;

?>

<div class="b-side-bar">
    <div class="b-filter">
        <?= Html::a(Yii::t('app', 'ПО Популярности'), 'javascript:void(0);', ['onClick' => 'ChooseProvidersListAll("pay_count DESC")']) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?= Html::a(Yii::t('app', 'ПО Алфавиту'), 'javascript:void(0);', ['onClick' => 'ChooseProvidersListAll("CONVERT(`name` USING cp1251)")']) ?>
        <a class="b-home-back" onclick="<?=isset($_GET['id'])?'history.go(-1);':'HomeProviders();' ?>" href="javascript:void(0);"></a>
        <a class="b-home" onclick="<?=isset($_GET['id'])?'':'HomeProviders();' ?>" href="<?=isset($_GET['id'])?'/':'javascript:void(0);' ?>"></a>
    </div>
    <div class="b-search-wrapper">
        <?= Html::beginForm('', 'post', ['id' => 'provider-search-form'])?>
            <?= Html::textInput('search','',['placeholder' => Yii::t('app', 'Поиск Организации...'), 'class' => 'b-search', 'id' => 'provider-search-input'])?>
            <a class="b-search-icon" href="javascript:void(0)"></a>
            <?= Html::submitButton('Submit', ['style' => 'position: absolute; margin-left: -9999px;'])?>
        <?= Html::endForm();?>
    </div>
    <div class="b-side-bar-menu">
        <ul>
            <?php if ($roots): ?>
                <?php foreach ($roots as $value) : ?>
                    <?php
                        $parent = Providers::findOne(['name' => $value->name]);
                        $children = $parent->leaves()->andWhere('status=1')->all();
                        $count = $children ? count($children) : 0;
                    $image = !empty($value->logo_sidebar)?Html::img('/uploads/providers-logo/'.$value->logo_sidebar, []):'';
                    ?>
                    <li>
                        <?= Html::a('<i style="top: 0px; left: 7px;">'. $image .'</i>'. Yii::t('providers/provider',$value->name) . '<span class="b-col">'.$count.'</span>',
                            'javascript:void(0);', ['onClick' => 'ChooseProviders('.$value->id.', this);ChangeHome();']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <!--<li>No data</li>-->
            <?php endif; ?>
        </ul>
    </div>
</div>


