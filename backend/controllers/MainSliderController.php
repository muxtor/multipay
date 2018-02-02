<?php

namespace backend\controllers;

use Yii;
use common\models\MainSlider;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * MainSliderController implements the CRUD actions for MainSlider model.
 */
class MainSliderController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MainSlider models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MainSlider::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MainSlider model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MainSlider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MainSlider();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'slider_image_url');
            if ($file) {
                $patch = Yii::$app->params['main-slider.uploads.path'];
                $model->slider_image_url = time() . '_' . md5($file->baseName) . '.' . $file->extension;
                $file->saveAs($patch . '/' . $model->slider_image_url);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->slider_id]);
            }
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MainSlider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $filename = $model->slider_image_url;
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'slider_image_url');
            if ($file) {
                $patch = Yii::$app->params['main-slider.uploads.path'];
                    
                if ($filename) {
                    $this->deleteFile($patch . '/' . $filename);
                }
                $model->slider_image_url = time() . '_' . md5($file->baseName) . '.' . $file->extension;
                $file->saveAs($patch . '/' . $model->slider_image_url);
            } elseif ($filename) {
               $model->slider_image_url = $filename;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->slider_id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MainSlider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MainSlider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MainSlider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MainSlider::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function deleteFile($path)
    {
        if (is_file($path) && file_exists($path)){
            unlink($path);
            return TRUE;
        }
    }
}
