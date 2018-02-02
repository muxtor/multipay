<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\StaticPage;
use frontend\components\FrontendController;

class PagesController extends FrontendController
{
    public function actionIndex($alias = null)
    {
        $model = $this->loadModel($alias);
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function loadModel($alias)
    {
        if (($model = StaticPage::find()->where('page_alias = :name', [':name' => $alias])->one()) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
    }
}
