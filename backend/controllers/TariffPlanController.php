<?php

namespace backend\controllers;

use Yii;
use common\models\TariffPlan;
use common\models\TariffPlanRules;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * TariffPlanController implements the CRUD actions for TariffPlan model.
 */
class TariffPlanController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TariffPlan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TariffPlan::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TariffPlan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model =  $this->findModel($id);
        $rules = new ActiveDataProvider([
            'query' => TariffPlanRules::find()->where(['tpr_tp_id' => $id]),
        ]);
//        $rules = \common\models\TariffPlanRules::find()->with('translations')->where(['tpr_tp_id' => $id])->all();
        
        return $this->render('view', [
            'model' => $model,
            'rules' => $rules,
        ]);
    }

    /**
     * Creates a new TariffPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TariffPlan();
        $languages = \common\models\Language::find()->all();

        if ($model->load(Yii::$app->request->post())) {
//            \yii\helpers\VarDumper::dump(Yii::$app->request->post('TariffPlanTranslation', []), 10,1);
//            die();
            if (Yii::$app->request->post('TariffPlanTranslation', [])) {
                foreach (Yii::$app->request->post('TariffPlanTranslation', []) as $language => $data) {
                    foreach ($data as $attribute => $translation) {
                        $model->translate($language)->$attribute = $translation;
                    }
                }
            }
//                \yii\helpers\VarDumper::dump($model, 10,1);
//            die();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->tp_id]);
            }
            
        } 
        return $this->render('create', [
            'view' => '_form',
            'model' => $model,
            'languages' => $languages,
        ]);
    }
    
    public function actionCreateRules($tp_id)
    {
        $model = new TariffPlanRules();
        $model->tpr_tp_id = $tp_id;
        $languages = \common\models\Language::find()->all();

        if ($model->load(Yii::$app->request->post())) {
//                \yii\helpers\VarDumper::dump($model, 10,1);
//            die();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->tpr_tp_id]);
            }
            
        } 
        return $this->render('create', [
            'view' => '_form_rules',
            'model' => $model,
            'languages' => $languages,
        ]);
    }

    /**
     * Updates an existing TariffPlan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $languages = \common\models\Language::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('TariffPlanTranslation', [])) {
                foreach (Yii::$app->request->post('TariffPlanTranslation', []) as $language => $data) {
                    foreach ($data as $attribute => $translation) {
                        $model->translate($language)->$attribute = $translation;
                    }
                }
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->tp_id]);
            }
        }
        
        return $this->render('update', [
            'view' => '_form',
            'model' => $model,
            'languages' => $languages,
        ]);
    }
    
    
    public function actionUpdateRules($tpr_id)
    {
        $model = $this->findModelRules($tpr_id);
        $languages = \common\models\Language::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->tpr_tp_id]);
            }
        }
        
        return $this->render('update', [
            'view' => '_form_rules',
            'model' => $model,
            'languages' => $languages,
        ]);
    }

    /**
     * Deletes an existing TariffPlan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteRules($tpr_id)
    {
        $model = $this->findModelRules($tpr_id);
        $tpr_tp_id = $model->tpr_tp_id;
        $model->delete();

        return $this->redirect(['view', 'id' => $tpr_tp_id]);
    }

    /**
     * Finds the TariffPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TariffPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TariffPlan::find()->with('translations')->where(['tp_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findModelRules($id)
    {
        if (($model = TariffPlanRules::find()->where(['tpr_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
