<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Providers;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ProvidersController extends Controller {
    
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
            ]
        ];
    }
    
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Providers::find()->where('active = :a', [':a' => 1]),
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $files = UploadedFile::getInstances($model, 'logofile');
            $files_sidebar = UploadedFile::getInstances($model, 'sidebarlogofile');
            if ($files) {
                $file = $files[0];
                if ($file) {
                    $patch = Yii::$app->params['files.uploads.path'] . '/providers-logo';
                    if (!Yii::$app->params['files.uploads.path']){
                        mkdir(realpath(__DIR__ . '/../../frontend/web/uploads'),0777, true);
                    }
                    if (!file_exists($patch)){
                        mkdir($patch);
                    }
                    $time = time();
                    $file->saveAs($patch . '/' . $time . '_'. md5($file->baseName) . '.' . $file->extension);
                    $model->logo = $time . '_' . md5($file->baseName) . '.' . $file->extension;
                }
            }
            if ($files_sidebar) {
                $file_sidebar = $files_sidebar[0];
                if ($file_sidebar) {
                    $patch_sidebar = Yii::$app->params['files.uploads.path'] . '/providers-logo';
                    if (!Yii::$app->params['files.uploads.path']){
                        mkdir(realpath(__DIR__ . '/../../frontend/web/uploads'),0777, true);
                    }
                    if (!file_exists($patch_sidebar)){
                        mkdir($patch_sidebar);
                    }
                    $time = time();
                    $file_sidebar->saveAs($patch_sidebar . '/' . $time . '_'. md5($file_sidebar->baseName) . '.' . $file_sidebar->extension);
                    $model->logo_sidebar = $time . '_' . md5($file_sidebar->baseName) . '.' . $file_sidebar->extension;
                }
            }
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Изменения успешно сохранены');
                return $this->redirect('index');
            }
        }
        
        return $this->render('update', [
                'model' => $model
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = Providers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
