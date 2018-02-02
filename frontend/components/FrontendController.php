<?php

namespace frontend\components;

use Yii;
use yii\web\Cookie;


/**
 * Class FrontendController.
 */
class FrontendController extends \yii\web\Controller {

    /**
     * @var array Options for body tag in view layout.
     */
    public $bodyOptions = [];
    public $supportedLanguages = [];
    
    public function init() {
        $app = \Yii::$app;
        $ip = Yii::$app->getRequest()->getUserIP();
        if (empty($_COOKIE['country_iso'])) {
            $country = json_decode(file_get_contents('http://api.sypexgeo.net/json/'.$ip));
            if ($country->country) {
                setcookie('country_iso',$country->country->iso, time() + 60*60*24*7, '/', '');
            }
        }
    }
}
