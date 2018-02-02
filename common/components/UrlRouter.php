<?php namespace common\components;

use yii\web\UrlRule;
use yii\helpers\Url;
use common\models\StaticPage;
use Yii;
use common\models\Language;
class UrlRouter extends UrlRule
{

    public $connectionID = 'db'; //db

    public function init()
    {
        
    }

    public function createUrl($manager, $route, $params)
    {
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $alias = explode('/', Url::to(''));
        $model = StaticPage::find()->where('page_alias = :name AND page_language = :language AND page_show = "1"', [':name' => array_pop($alias), ':language' => Language::getCurrent()->lang_url])->one();
        if (isset($model) && $model->page_show == 1) {
            $params['alias'] = $model->page_alias;
            return ['/pages/index', $params];
        } elseif ($model = StaticPage::find()->where('page_alias = :name AND page_language <> :language AND page_show = "1"', [':name' => array_pop($alias), ':language' => Language::getCurrent()->lang_url])->one()) {
            if (isset($model) && $model->page_show == 1) {
                $params['alias'] = $model->page_alias;
                return ['/pages/index', $params];
            }
        }
        return false;
    }

    /**
     *  Set date of visited register user
     */
}
