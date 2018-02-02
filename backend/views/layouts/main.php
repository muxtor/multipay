<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use backend\components\NavBarComponents;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBarComponents::begin([
        'brandLabel' => 'Multipay',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        $menuItems = [
            ['label' => Yii::t('back/menu', 'ГЛАВНАЯ'), 'url' => ['/site/index']],
            ['label' => Yii::t('back/menu', 'ВХОД'), 'url' => ['/site/login']]
        ];
    } else {
        $menuItems = [
            ['label' => Yii::t('back/menu', 'ГЛАВНАЯ'), 'url' => ['/site/index']],
            ['label' => Yii::t('back/menu', 'ОПЛАТЫ'), 'url' => ['/payments/index']],
            ['label' => Yii::t('back/menu', 'API'), 'url' => ['/payment-api/index']],
            ['label' => Yii::t('back/menu', 'НАСТРОЙКИ'), 'items' => [
                ['label' => Yii::t('back/menu', 'ПЕРЕВОДЫ'), 'url' => ['/translations']],
                ['label' => Yii::t('back/menu', 'СТАТИЧЕСКИЕ_СТРАНИЦЫ'), 'url' => ['/pages/index']],
                ['label' => Yii::t('back/menu', 'НОВОСТИ'), 'url' => ['/news/index']],
                ['label' => Yii::t('back/menu', 'ПОМОЩЬ'), 'url' => ['/faq/index']],
                ['label' => Yii::t('back/menu', 'ГЛАВНЫЙ_СЛАЙДЕР'), 'url' => ['/main-slider/index']],
                ['label' => Yii::t('back/menu', 'ПОЛЬЗОВАТЕЛИ'), 'url' => ['/user/index']],
                ['label' => Yii::t('back/menu', 'ПРОВАЙДЕРЫ'), 'url' => ['/providers/index']],
                ['label' => Yii::t('back/menu', 'СТРАНЫ'), 'url' => ['/country/index']],
                ['label' => Yii::t('back/menu', 'НАСТРОЙКИ_ВЕБМАНИ'), 'url' => ['/wm-setting/index']],
                ['label' => Yii::t('back/menu', 'НАСТРОЙКИ_СИСТЕМЫ'), 'url' => ['/settings/default/index']],
                ['label' => Yii::t('back/menu', 'ПАРТНЕРЫ'), 'url' => ['/partners/index']],
                ['label' => Yii::t('back/menu', 'ТЕРМИНАЛ'), 'url' => ['/terminal/index']],
            ]],
            ['label' => Yii::t('back/menu', 'БОНУСЫ'), 'items' => [
                ['label' => Yii::t('back/menu', 'БОНУСЫ_ПОМОЩЬ'), 'url' => ['/bonus-help/index']],
                ['label' => Yii::t('back/menu', 'БОНУСЫ'), 'url' => ['/tariff-plan/index']],
            ]],
            [
                'label' => Yii::t('back/menu', 'ВЫХОД').'(' . Yii::$app->user->identity->username . ')',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ]
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBarComponents::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; MultiPay <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
