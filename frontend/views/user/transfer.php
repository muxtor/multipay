<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use common\models\BonusHelp;

//$this->registerJs(<<<JS
//            function ShowTarif(id) {
//                $('.b-bonus-tarif li a').removeClass('b-active');
//                $('#b-tarif-btn-' + id).addClass('b-active');
//                $('.b-tarifs-block').removeClass('b-active');
//                $('#b-tarif-' + id).addClass('b-active');
//            }
//JS
//, yii\web\View::POS_END);

$this->title = Yii::t('view/user/reclame', 'Переводы - MultiPay');
?>
        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-side-bar">
                    <div class="b-side-bar-menu">
                        <?=$this->render('_transfer_menu')?>
                    </div>
                </div>
                <div class="b-categorys">
                    <div class="b-settings-wrapper">
                        <h1><?= Yii::t('transfer', 'Переводы')?></h1>
                        <div class="b-transfers-wrapper">
                            <ul class="b-transfers">
                                <li>
                                    <a href="/user/transfer-add">
                                        <div class="b-transfers-logo">
                                            <img src="/images/auth/wallet.png"/>
                                        </div>
                                        <div class="b-transfers-text">
                                            <?= Yii::t('transfer', 'На другой<br />кошелек')?>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/user/transfer-request">
                                        <div class="b-transfers-logo">
                                            <img src="/images/auth/zapros-icon.png"/>
                                        </div>
                                        <div class="b-transfers-text">
                                            <?= Yii::t('transfer', 'Запросить<br />перевод')?>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="b-clear"></div><br />
                        </div>
                        <br />
                    </div>
                </div>
            </div>
        </div>
        <div class="b-clear"></div>