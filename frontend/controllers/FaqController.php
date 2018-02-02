<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Faq;
use frontend\components\FrontendController;
use common\models\Language;

class FaqController extends FrontendController
{
    
    public function actionIndex()
    {
        $models = Faq::find()->where(['faq_language'=>Language::getCurrent()->lang_url])->all();
        return $this->render('index', [
            'models' => $models
        ]);
    }

    public function loadModel()
    {
        if (($model = Faq::find()->where(['faq_language'=>Language::getCurrent()->lang_url])->one()) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
    }
}
