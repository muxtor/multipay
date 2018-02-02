<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\News;
use frontend\components\FrontendController;
use common\models\Language;

class NewsController extends FrontendController
{
    public function actionView($alias = null)
    {
        $model = $this->loadModel($alias);
        return $this->render('view', [
            'model' => $model
        ]);
    }
    
    public function actionIndex()
    {
        $models = News::find()->where(['news_language'=>Language::getCurrent()->lang_url,'news_show'=>1])->orderBy('news_date DESC')->all();
        return $this->render('index', [
            'models' => $models
        ]);
    }

    public function loadModel($alias)
    {
        if (($model = News::find()->where(['news_language'=>Language::getCurrent()->lang_url, 'news_alias'=>$alias])->one()) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
    }
}
