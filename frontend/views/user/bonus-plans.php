<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->registerJs(<<<JS
            function ShowTarif(id) {
                $('.b-bonus-tarif li a').removeClass('b-active');
                $('#b-tarif-btn-' + id).addClass('b-active');
                $('.b-tarifs-block').removeClass('b-active');
                $('#b-tarif-' + id).addClass('b-active');
            }
JS
, yii\web\View::POS_END);

$this->title = Yii::t('view/user/bonus-plans', 'Тарифные планы');
?>
        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-content-full-width">
                    <h1><?=$this->title?></h1>
                    <?php if ($model):?>
                    <?php $i=1; $k=1;?>
                    <div class="b-bonus-tarif-wrapper">
                        <ul class="b-bonus-tarif">
                            <?php foreach ($model as $tafif):?>
                            <?php 
                                if (!empty($tp_id)) {
                                    $activ = $tp_id==$tafif->tp_id ? ' class="b-active"' : '';
                                } elseif ($i==1) {
                                    $activ = ' class="b-active"';
                                } else {
                                    $activ = '';
                                }
                            ?>
                                <li><a id="b-tarif-btn-<?=$tafif->tp_id?>" <?=$activ?> href="javascript:void(0);" onclick="ShowTarif('<?=$tafif->tp_id?>');"><?=$tafif->title?></a></li>
                            <?php $i++;?>
                            <?php endforeach;?>
                            <?php unset($i);?>
                        </ul>
                    </div>
                    <div class="b-table-wrapper">
                        <?php foreach ($model as $tafif):?>
                        <?php 
                                if (!empty($tp_id)) {
                                    $activ = $tp_id==$tafif->tp_id ? ' b-active' : '';
                                } elseif ($k==1) {
                                    $activ = ' b-active';
                                } else {
                                    $activ = '';
                                }
                            ?>
                        <div class="b-tarifs-block <?=$activ?>" id="b-tarif-<?=$tafif->tp_id?>">
                            <table class="b-table">
                                <thead>
                                    <tr>
                                        <th><?=Yii::t('view/user/bonus-plans', 'Правило')?></th>
                                        <th class="b-text-center"><?=Yii::t('view/user/bonus-plans', 'Период начисления')?></th>
                                        <th class="b-text-center"><?=Yii::t('view/user/bonus-plans', 'Бонус')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php $j=1;?>
                            <?php foreach ($tafif->rules as $rule):?>
                                    <tr <?= ($j%2) == 0 ? 'class="b-gray"' : '';?>>
                                        <td><?=$rule->getTypeLabel()?></td>
                                        <td class="b-text-center"><?=$rule->getPeriodLabel()?></td>
                                        <td class="b-text-center"><?=$rule->tpr_bonus_value?></td>
                                    </tr>
                                    <?php $j++;?>
                            <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <?php $k++;?>
                        <?php endforeach;?>
                        <?php unset($k);?>
                    </div>
                    <?php endif;?>
                </div>
                <br />
            </div>
        </div>
        <div class="b-clear"></div>