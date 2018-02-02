<?php

namespace backend\controllers;

use Yii;
use common\models\StaticPage;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * StaticPageController implements the CRUD actions for StaticPage model.
 */
class PagesController extends Controller
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
    
    public function actions()
    {
        parent::actions();
        /*
         * http://www.yiiframework.ru/forum/viewtopic.php?t=22866#p141006
         * "url" - это ссылка к директории с веб доступом в которой будет загружен 
         * сам файл. Подразумевается что в конкретно данной ситуации загрузка происходит
         * в временную папку, по этому и УРЛ будет временным.
         * "path" - это путь к временной папке куда будет загружен файл. Данный 
         * выджет имеет такую логику что он предварительно загружает файл в временную 
         * папку, именно в той что указано в "path". И только при сохранении он 
         * перемещает файл в постоянную папку.
         * надо указывать одну и туже папку
         */
        return [
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => Yii::$app->params['frontend.uploads'].'/static/', // Directory URL address, where files are stored.
                'path' => realpath(__DIR__ . '/../../frontend/web/uploads/static/') // Or absolute path to directory where files are stored.
            ],
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => Yii::$app->params['frontend.uploads'].'/static/', // Directory URL address, where files are stored.
                'path' => realpath(__DIR__ . '/../../frontend/web/uploads/static/'), // Or absolute path to directory where files are stored.
                'type' => \vova07\imperavi\actions\GetAction::TYPE_IMAGES,
                'options' => ['basePath' => Yii::getAlias('@web/uploads/static/')],
            ],
        ];
    }

    /**
     * Lists all StaticPage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => StaticPage::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaticPage model.
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
     * Creates a new StaticPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaticPage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Изменения успешно сохранены');
            return $this->redirect('index');
        }
        
        return $this->render('create', [
        'model' => $model
        ]);
    }

    /**
     * Updates an existing StaticPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Изменения успешно сохранены');
                    return $this->redirect('index');
        }
        
        return $this->render('update', [
                'model' => $model
        ]);
    }

    /**
     * Deletes an existing StaticPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->getSession()->setFlash('warning', 'Страница успешно удалена');
        return $this->redirect(['index']);
    }

    /**
     * Finds the StaticPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaticPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaticPage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
