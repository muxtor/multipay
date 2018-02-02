<?php
namespace frontend\controllers;

use frontend\components\FrontendController;
use yii\filters\VerbFilter;
use common\models\Providers;
use common\components\Helper;

class ProvidersController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionChooseProviders()
    {
        if (\Yii::$app->request->isAjax) {
            $post = \Yii::$app->request->post();
            if (!empty($post['p_id'])) {
                $parent = Providers::findOne(\Yii::$app->request->post('p_id'));
                
                \Yii::$app->getSession()->set('root', \Yii::$app->request->post('p_id'));
                
                if ($parent) {
                    $leaves = $parent->children(1)->andWhere('status=1')->orderBy('rgt ASC')->all();
//                    \yii\helpers\VarDumper::dump($leaves, 10, 1);
                } else {
                    $leaves = null;
                }
                $counries = \common\models\Country::find()->where(['<>', 'tel_mask', ''])->all();
                $map = \yii\helpers\ArrayHelper::map($counries, 'id', 'name');
                return $this->renderAjax('choose_providers', ['roots' => $leaves, 'map' => $map,'countries'=>$counries,'onclick'=>null]);
            }
        }
    }
    
    public function actionChooseAllProviders()
    {
        if (\Yii::$app->request->isAjax) {
            $post = \Yii::$app->request->post();
            $parent = Providers::find();
            $leaves = null;
            $root = \Yii::$app->getSession()->get('root');
            \Yii::$app->getSession()->remove('root');

            if (!empty($post['sort'])) {
                $parent->orderBy($post['sort']);
                $parent->andWhere('lvl > :lvl', ['lvl' => 0]);
            }
            if (!empty($post['country_id'])) {
                $parent->andWhere(['country_id'=>$post['country_id']]);
                
                if (!empty($root)) {
                    $parent->andWhere(['root' => $root]);
                }
            }
            if (!empty($post['queryString'])) {
                $parent->andWhere(['like', 'LOWER(name)', Helper::prepareString($post['queryString']).'%',false]);

                if (!empty($root)) {
                    $parent->andWhere(['root' => $root]);
                }
            }
            if (!empty($post['cfrom'])) {
                $onclick = $post['cfrom'];
            }else{
                $onclick = null;
            }

            if ($parent) {
                $leaves = $parent->andWhere('status = 1')->leaves()->all();
            } 
            $counries = \common\models\Country::find()->where(['<>', 'tel_mask', ''])->all();
            $map = \yii\helpers\ArrayHelper::map($counries, 'id', 'name');
            return $this->renderAjax('choose_providers', ['roots' => $leaves, 'map' => $map,'countries'=>$counries,'onclick'=>$onclick]);
        }
        var_dump(\Yii::$app->getSession()->get('root'));
    }
    public function actionHomeProviders()
    {
        return $this->renderAjax('home_providers');

    }
    
}
