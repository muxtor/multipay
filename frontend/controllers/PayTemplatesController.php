<?php

namespace frontend\controllers;

use frontend\components\FrontendController;
use yii\filters\AccessControl;
use common\models\PayTemplate;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PayTemplatesController extends FrontendController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
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
    
    public function actionIndex()
    {
        $model = PayTemplate::find()->where('pt_user_id = :id', [':id' => \Yii::$app->user->id])->all();
        return $this->render('index', ['model' => $model]);
    }
    
    /**
     * Updates an existing PayTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->pt_user_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing PayTemplate model.
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
     * Finds the PayTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PayTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PayTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddTemplate()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $post = \Yii::$app->request->post();
            $json = [];
            if (empty($post['id'])) {
                $json['message'] = \Yii::t('payment', 'НЕОБХОДИМО_ВЫБРАТЬ_ПРОВАЙДЕРА');
//                $json['result'] = 'no';
                return $json;
            }
            $post = \Yii::$app->request->post();
            $template = PayTemplate::find()->where('pt_user_id = :user', [':user' => \Yii::$app->user->id])
                ->andWhere('pt_provider_id = :provider', [':provider' => $post['id']])
                ->andWhere('pt_accaunt = :accaunt', [':accaunt' => $post['account']])
                ->andWhere('pt_currency = :currency', [':currency' => $post['currency']])
                ->andWhere('pt_summ = :summ', [':summ' => $post['amount']])
                ->andWhere('pt_system = :sys', [':sys' => $post['pay_system']])
                ->one();
            if (!$template) {
                $model = new PayTemplate();
                $model->pt_user_id = \Yii::$app->user->id;
                $model->pt_provider_id = $post['id'];
                $model->pt_accaunt = $post['account'];
                $model->pt_currency = $post['currency'];
                $model->pt_summ = $post['amount'];
                $model->pt_system = $post['pay_system'];
                if ($model->save()) {
    //                $json['result'] = 'yes';
                    $json['message'] = \Yii::t('payment', 'ШАБЛОН_ОПЛАТЫ_СОХРАНЕН');
                } else {
    //                $json['result'] = 'no';
                    $json['message'] = \Yii::t('payment', 'НЕ_УДАЛОСЬ_ДОБАВИТЬ_ШАБЛОН_ОПЛАТЫ');
                }
            } else {
                $json['message'] = \Yii::t('payment', 'ШАБЛОН_УЖЕ_СУЩЕСТВУЕТ');
            }
            return $json;
        }
    }

}
