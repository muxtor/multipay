<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/site.css',
        'css/style.css',
        'css/main-slider.css',
        'css/jquery-ui.css',
        'css/jquery.formstyler.css',
        'css/front.css'
    ];
    public $js = [
//        'js/jquery-1.11.3.min.js',
        'js/common.js',
        'js/bootstrap.min.js',
        'js/jquery.placeholder.min.js',
        'js/jquery.jcarousel.min.js',
        'js/jquery.jcarousel-autoscroll.min.js',
        'js/jcarousel.basic.js',
        'js/jquery-tabs.js',
        'js/jquery.maskedinput.js',
        'js/datepicker-all.js',
        'js/jquery.formstyler.min.js',
        'js/jquery-ui.min.js',
        'js/front.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
