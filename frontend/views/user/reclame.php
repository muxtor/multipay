<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use common\models\BonusHelp;

$this->registerJs(<<<JS
            function ShowTarif(id) {
                $('.b-bonus-tarif li a').removeClass('b-active');
                $('#b-tarif-btn-' + id).addClass('b-active');
                $('.b-tarifs-block').removeClass('b-active');
                $('#b-tarif-' + id).addClass('b-active');
            }
JS
, yii\web\View::POS_END);

$this->title = Yii::t('view/user/reclame', 'Бонусы - MultiPay');
?>
        <div class="b-content-wrapper">
            <div class="b-content">
                <div class="b-content-full-width">
                    <h1><?=Yii::t('user', 'Получение бонусов')?></h1>
                    <div class="b-content-full-width-inner">
                        <div class="b-get-bonus-wrapper">
                            <?php echo common\widgets\BonusHelp\HelpBlock::widget(['alias'=>BonusHelp::ALIAS_RECKLAM])?>
                        </div>
                        <div class="b-clear"></div>
                        <ul class="b-get-bonus-nav">
                            <li>
                                <?php echo
                                     Html::a(Yii::t('yii', 'Пригласить по ссылке'),'/user/get-referral-link', [
                                        'title' => Yii::t('yii', 'Прислать код повторно'),
                                            'onclick'=>"$.ajax({
                                                        type     :'POST',
                                                        cache    : false,
                                                        url  : '/user/get-referral-link',
                                                        success  : function(data) {
                                                            $('#link').val(data.link);
                                                        }
                                                        });return false;",
                                                        ]);
                                    ?>
                            </li>
                            <li class="b-gray"><a href="#"><?=Yii::t('user', 'Разместить баннер')?></a></li>
                        </ul>
                        <div class="b-clear"></div>
                        <div class="b-filed b-bonus-link-wrapper">
                            <label><?=Yii::t('user', 'Ссылка для приглашения:')?></label>
                            <input id="link" class="b-input" value="<?=$user->referralLink?>" type="text"/>
                            <p><?=Yii::t('user', 'По этой ссылке может перейти любое количество пользователей.<br />
                                Бонусы зачисляются только за регистрации.')?></p>
                        </div>
                    </div>
                </div>
                <br />
            </div>
        </div>
        <div class="b-clear"></div>