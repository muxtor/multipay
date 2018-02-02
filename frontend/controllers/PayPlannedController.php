<?php

namespace frontend\controllers;

use common\models\Payments;
use frontend\components\FrontendController;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\models\PayPlanned;

class PayPlannedController extends FrontendController
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
        $model = PayPlanned::find()
            ->where('pp_user_id = :id', [':id' => \Yii::$app->user->id])
            ->andWhere('pp_type = :type', [':type' => PayPlanned::TYPE_ONCE])->all();
        return $this->render('index', ['models' => $model]);
    }
    
    /**
     * Updates an existing PayPlanned model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->pp_user_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        if ($model->pp_system != Payments::PAY_SYSTEM_WALLET) {
            \Yii::$app->session->setFlash('error',  \Yii::t('payment', 'МОЖНО_ПЛАНИРОВАТЬ_ТОЛЬКО_С_БАЛАНСА'));
            return $this->redirect(['/pay-planned/index']);
        }

        if ($model->load(\Yii::$app->request->post())) {
            $model->pp_system = Payments::PAY_SYSTEM_WALLET;
            $model->pp_currency = Payments::CURRENCY_AZN;
            if ($model->validate()) {
                if ($model->oldAttributes['pp_type'] == PayPlanned::TYPE_WEEK) {
                    \Yii::$app->db->createCommand()->delete('pay_planned_week', 'ppw_pay_plan_id = :id', [':id' => $model->pp_id])->execute();
                }
                if ($model->oldAttributes['pp_type'] == PayPlanned::TYPE_MONTH) {
                    \Yii::$app->db->createCommand()->delete('pay_planned_month', 'ppm_pay_plan_id = :id', [':id' => $model->pp_id])->execute();
                }

                $post = \Yii::$app->request->post('PayPlanned');
                if (in_array($model->pp_type, [PayPlanned::TYPE_WEEK, PayPlanned::TYPE_MONTH])) {
                    $model->pp_pay_date = date('Y-m-d').' '.$post['pay_time'].':00';
                }
                if ($model->save(false)) {
                    if ($model->pp_type == PayPlanned::TYPE_WEEK) {
                        foreach ($post['days_of_week'] as $day) {
                            \Yii::$app->db->createCommand()->insert('pay_planned_week', [
                                'ppw_pay_plan_id' => $model->pp_id,
                                'ppw_day' => $day,
                            ])->execute();
                        }
                    }
                    if ($model->pp_type == PayPlanned::TYPE_MONTH) {
                        foreach ($post['days_of_month'] as $day) {
                            \Yii::$app->db->createCommand()->insert('pay_planned_month', [
                                'ppm_pay_plan_id' => $model->pp_id,
                                'ppm_day' => $day,
                            ])->execute();
                        }
                    }
                    return $this->redirect(['/pay-planned/index']);
                }
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing PayPlanned model.
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
     * Finds the PayPlanned model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PayPlanned the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PayPlanned::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddPlannedData()
    {
        if (\Yii::$app->request->isAjax) {
            $post = \Yii::$app->request->post();
            if (empty($post['id'])) {
                $json['message'] = \Yii::t('payment', 'НЕОБХОДИМО_ВЫБРАТЬ_ПРОВАЙДЕРА');
                return $json;
            }
            if ($post['pay_system'] != Payments::PAY_SYSTEM_WALLET) {
                $json['message'] = \Yii::t('payment', 'МОЖНО_ПЛАНИРОВАТЬ_ТОЛЬКО_С_БАЛАНСА');
                return $json;
            }
            return $this->redirect(['/pay-planned/add-planned',
                'id' => $post['id'],
                'account' => $post['account'],
                'amount' => $post['amount'],
                'pay_system' => $post['pay_system']
            ]);
        }
    }

    public function actionAddPlanned($id, $account = '', $amount = '', $pay_system = '')
    {
        if ($pay_system != Payments::PAY_SYSTEM_WALLET) {
            \Yii::$app->session->setFlash('error',  \Yii::t('payment', 'МОЖНО_ПЛАНИРОВАТЬ_ТОЛЬКО_С_БАЛАНСА'));
            return $this->redirect(['/payments/step-one', 'id' => $id]);
        }
        $post = \Yii::$app->request->post('PayPlanned');
        $model = new PayPlanned();
        $model->pp_user_id = \Yii::$app->user->id;
        $model->pp_provider_id = $id;
        $model->pp_account = $account;
        $model->pp_summ = $amount;
        $model->pp_system = Payments::PAY_SYSTEM_WALLET;
        $model->pp_currency = Payments::CURRENCY_AZN;
        if ($model->load(\Yii::$app->request->post())) {
            if (in_array($model->pp_type, [PayPlanned::TYPE_WEEK, PayPlanned::TYPE_MONTH])) {
                $model->pp_pay_date = date('Y-m-d').' '.$post['pay_time'].':00';
            }
            if ($model->save()) {
                if ($model->pp_type == PayPlanned::TYPE_WEEK) {
                    foreach ($post['days_of_week'] as $day) {
                        \Yii::$app->db->createCommand()->insert('pay_planned_week', [
                            'ppw_pay_plan_id' => $model->pp_id,
                            'ppw_day' => $day,
                        ])->execute();
                    }
                }
                if ($model->pp_type == PayPlanned::TYPE_MONTH) {
                    foreach ($post['days_of_month'] as $day) {
                        \Yii::$app->db->createCommand()->insert('pay_planned_month', [
                            'ppm_pay_plan_id' => $model->pp_id,
                            'ppm_day' => $day,
                        ])->execute();
                    }
                }
                return $this->redirect(['/pay-planned/index']);
            }

        }
        return $this->render('add_planned', ['model' => $model]);
    }

    public function actionChoosePeriod()
    {
        if (\Yii::$app->request->isAjax) {
            $period = \Yii::$app->request->post('period');
            if ($period == PayPlanned::TYPE_ONCE) {
                $model = PayPlanned::find()
                    ->where('pp_user_id = :id', [':id' => \Yii::$app->user->id])
                    ->andWhere('pp_type = :type', [':type' => PayPlanned::TYPE_ONCE])->all();
                return $this->renderAjax('index_once', ['models' => $model]);
            } elseif ($period == PayPlanned::TYPE_WEEK) {
                $models = $this->weekPaymentsList();
                return $this->renderAjax('index_week', ['models' => $models]);
            } elseif ($period == PayPlanned::TYPE_MONTH) {
                $models = $this->monthPaymentsList();
                return $this->renderAjax('index_month', ['models' => $models]);
            }
        }

    }

    public function weekPaymentsList()
    {
        $days_of_week = range(1,7);
        $days_and_pays = (new Query())
            ->select(['d.ppw_day', 'GROUP_CONCAT(p.pp_id ORDER BY p.pp_id ASC SEPARATOR \',\') AS planned'])
            ->from('{{%pay_planned_week}} d')
            ->leftJoin('{{%pay_planned}} p', 'd.ppw_pay_plan_id = p.pp_id')
            ->where('p.pp_user_id = :id', [':id' => \Yii::$app->user->id])
            ->groupBy('d.ppw_day')
            ->all();
        $days_and_pays = ArrayHelper::map($days_and_pays, 'ppw_day', 'planned');
        $day_pay_map = [];
        foreach ($days_of_week as $d) {
            if (array_key_exists($d, $days_and_pays)) {
                $day_pay_map[$d] = $days_and_pays[$d];
            } else {
                $day_pay_map[$d] = [];
            }
        }
        $model = PayPlanned::find()
            ->where('pp_user_id = :id', [':id' => \Yii::$app->user->id])
            ->andWhere('pp_type = :type', [':type' => PayPlanned::TYPE_WEEK])->all();
        $res = [];
        foreach ($day_pay_map as $days => $pays) {
            if ($pays) {
                foreach ($model as $m) {
                    if (in_array($m->pp_id, explode(',', $pays))) {
                        $res[$days][] = $m;
                    }
                }
            } else {
                $res[$days] = [];
            }
        }
        return $res;
    }
    public function monthPaymentsList()
    {
        $days_of_week = range(1,31);
        $days_and_pays = (new Query())
            ->select(['d.ppm_day', 'GROUP_CONCAT(p.pp_id ORDER BY p.pp_id ASC SEPARATOR \',\') AS planned'])
            ->from('{{%pay_planned_month}} d')
            ->leftJoin('{{%pay_planned}} p', 'd.ppm_pay_plan_id = p.pp_id')
            ->where('p.pp_user_id = :id', [':id' => \Yii::$app->user->id])
            ->groupBy('d.ppm_day')
            ->all();
        $days_and_pays = ArrayHelper::map($days_and_pays, 'ppm_day', 'planned');
        $day_pay_map = [];
        foreach ($days_of_week as $d) {
            if (array_key_exists($d, $days_and_pays)) {
                $day_pay_map[$d] = $days_and_pays[$d];
            } else {
                $day_pay_map[$d] = [];
            }
        }
        $model = PayPlanned::find()
            ->where('pp_user_id = :id', [':id' => \Yii::$app->user->id])
            ->andWhere('pp_type = :type', [':type' => PayPlanned::TYPE_MONTH])->all();
        $res = [];
        foreach ($day_pay_map as $days => $pays) {
            if ($pays) {
                foreach ($model as $m) {
                    if (in_array($m->pp_id, explode(',', $pays))) {
                        $res[$days][] = $m;
                    }
                }
            } else {
                $res[$days] = [];
            }
        }
        return $res;
    }

}
