<?php

namespace backend\controllers;

use common\models\Newsletter;
use common\models\User;
use Yii;
use common\models\News;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * StaticPageController implements the CRUD actions for StaticPage model.
 */
class NewsController extends Controller
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
                'url' => Yii::$app->params['frontend.uploads'].'/news/', // Directory URL address, where files are stored.
                'path' => realpath(__DIR__ . '/../../frontend/web/uploads/news/') // Or absolute path to directory where files are stored.
            ],
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => Yii::$app->params['frontend.uploads'].'/news/', // Directory URL address, where files are stored.
                'path' => realpath(__DIR__ . '/../../frontend/web/uploads/news/'), // Or absolute path to directory where files are stored.
                'type' => \vova07\imperavi\actions\GetAction::TYPE_IMAGES,
                'options' => ['basePath' => Yii::getAlias('@web/uploads/news/')],
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
            'query' => News::find(),
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
        $model = new News();

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
//            \yii\helpers\VarDumper::dump(Yii::$app->request->post(), 10, 10);
//            \yii\helpers\VarDumper::dump($model, 10, 10);
//            die();
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
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSend($id, $type)
    {
        if (!$id || !$type) {
            throw new InvalidParamException('Incorrect data');
        }

        $model = $this->findModel($id);
        if ($type == Newsletter::VIA_EMAIL) {
            $users = User::find()->where(['notice_news_isEmail' => 1])
                ->andWhere(['and', 'email IS NOT NULL', "email <> ''"])
                ->andWhere(['user_language' => $model->news_language])
                ->andWhere(['status' => User::STATUS_ACTIVE])->all();
            if (!$users) {
                Yii::$app->session->setFlash('warning', Yii::t('news', 'Нет пользователей для рассылки'));
                return $this->redirect(['index']);
            }
            $messages = [];
            foreach ($users as $user) {
                $messages[] = Yii::$app->mailer->compose('send_news', ['text' => $model->news_text])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($user->email)
                    ->setSubject('News from ' . \Yii::$app->name);
            }
            Yii::$app->mailer->sendMultiple($messages);
            $newsLetter = new Newsletter();
            $newsLetter->news_id = $model->news_id;
            $newsLetter->type = Newsletter::VIA_EMAIL;
            $newsLetter->send_by = Yii::$app->user->identity->getId();
            $newsLetter->save();
            Yii::$app->session->setFlash('success', Yii::t('news', 'Email рассылка произведена'));
        } elseif ($type == Newsletter::VIA_SMS) {
            $users = User::find()->where(['notice_news_isPhone' => 1])
                ->andWhere(['user_language' => $model->news_language])
                ->andWhere(['status' => User::STATUS_ACTIVE])->all();
            if (!$users) {
                Yii::$app->session->setFlash('warning', Yii::t('news', 'Нет пользователей для рассылки'));
                return $this->redirect(['index']);
            }
            foreach ($users as $user) {
                Yii::$app->mainsms->api->sendSMS($user->phone, $model->news_text);
            }
            $newsLetter = new Newsletter();
            $newsLetter->news_id = $model->news_id;
            $newsLetter->type = Newsletter::VIA_SMS;
            $newsLetter->send_by = Yii::$app->user->identity->getId();
            $newsLetter->save();
            Yii::$app->session->setFlash('success', Yii::t('news', 'SMS рассылка произведена'));
        }
        return $this->redirect(['index']);
    }
}
