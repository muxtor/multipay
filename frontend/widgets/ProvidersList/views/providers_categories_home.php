<?php
use yii\helpers\Html;

?>

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

