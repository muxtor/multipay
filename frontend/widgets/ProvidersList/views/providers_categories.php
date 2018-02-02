<?php
use yii\helpers\Html;

?>

<div class="b-country-filter">
    <span>
        <a href="javascript:void(0);"><span id="country-filter-heder"><?=Yii::t('app', 'Все страны')?></span></a>
        <ul class="b-countrys">
            <li style="display: none;">
                <?//= Html::a('Все страны', 'javascript:void(0);', ['onClick' => 'ChooseProvidersListCountry(null, "'. Yii::t('app', 'Все страны') . '")']) ?>
            </li>
            <?php if ($countries): ?>
                <?php foreach ($countries as $k=>$v) : ?>
                    <li>
                        <?= Html::a('<img src="'.Yii::$app->params['flags'].strtolower($v->iso).'.png" alt="'.$v->name.'">'.$v->name, 'javascript:void(0);', ['onClick' => 'ChooseProvidersListCountry(' . $v->id . ',"'. $v->name . '")']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <!--<li><?/*=Yii::t('app', 'No data')*/?></li>-->
            <?php endif; ?>
        </ul>
    </span>
    <div class="b-clear"></div>
</div>
<div class="b-categorys b-services-page">
    <ul class="b-sevices">
        <?php if ($roots): ?>
            <?php foreach ($roots as $value) :
                $image = !empty($value->logo)?Html::img('/uploads/providers-logo/' . $value->logo, ['alt' => Yii::t('providers/provider',$value->name)]):'';
                ?>
                <li>
                    <?= Html::a('<div class="b-image">' . $image . '</div><div class="b-text">' . Yii::t('providers/provider',$value->name) . '</div>',
                        'javascript:void(0);', ['onClick' => 'ChooseProvidersList(' . $value->id . ')']) ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <!--<li><?/*=Yii::t('app', 'No data')*/?></li>-->
        <?php endif; ?>
    </ul>
</div>

