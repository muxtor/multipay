<?php

namespace frontend\controllers;

use common\components\Helper;
use common\models\Invoice;
use common\models\MoneyBallance;
use common\models\Payments;
use common\models\User;
use frontend\components\FrontendController;
use frontend\models\TransferForm;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class InvoiceController extends FrontendController
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

    private function _checkDates()
    {
        $invoiceLifetimeHours = \Yii::$app->settings->get('invoice_lifetime_hours', 'currency');
        if (!$invoiceLifetimeHours) {
            return;
        }
        $invoices = (new Query())
            ->select('id, payment_id')
            ->from(Invoice::tableName())
            ->join('INNER JOIN', 'payments', 'pay_id = payment_id')
            ->where(['to_user_id' => \Yii::$app->user->identity->getId()])
            ->andWhere(['status' => Invoice::STATUS_WAIT])
            ->andWhere('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(pay_created)  > :lifetime', [':lifetime' => ceil((int)$invoiceLifetimeHours*3600)])
            ->all();
        if (!$invoices) {
            return;
        }
        $invoices = ArrayHelper::map($invoices, 'id', 'payment_id');
        Invoice::updateAll(['status' => Invoice::STATUS_CANCEL], ['in', 'id', array_keys($invoices)]);
        Payments::updateAll([
            'pay_check_status' => Payments::CHECK_STATUS_ERROR,
            'pay_check_result' => (string)Payments::CHECK_RESULT_UNKNOWN_ERROR,
            'pay_check_result_desc' => 'Canceled by system (out of date)',
            'pay_is_checked' => Payments::CHECK_CHECKED,
            'pay_comment' => 'Cancel date '. date('d-m-Y H:i:s'),
        ], ['in', 'pay_id', array_values($invoices)]);
        return;
    }

    public function actionIndex()
    {
        $this->_checkDates();
        $query = Invoice::find()->join('INNER JOIN', 'payments', 'pay_id = payment_id')
            ->where(['to_user_id' => \Yii::$app->user->identity->getId()])
            ->orderBy('pay_created DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index',[
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionOutInvoice()
    {
        $query = Invoice::find()->join('INNER JOIN', 'payments', 'pay_id = payment_id')
            ->where(['from_user_id' => \Yii::$app->user->identity->getId()])
            ->orderBy('pay_created DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('out_invoice', [
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionAddInvoice()
    {
        $transfer = new TransferForm();
        $transfer->setScenario('request');
        if ($transfer->load(\Yii::$app->request->post()) && $transfer->validate() ) {
            $user_from = User::findByPhone($transfer->phone);
            $payments = new Payments();
            $payments->pay_user_id = $user_from->id;
            $payments->pay_pc_provider_id = 'MultiPay';
            $payments->pay_pc_provider_account = \Yii::$app->user->identity->phone;

            $payments->pay_summ = round($transfer->sum_to,2);
            $payments->pay_summ_from = round($transfer->sum_from,2);
            $payments->pay_comment = $transfer->comment;

            $payments->pay_currency = Payments::CURRENCY_AZN; //дефолтная валюта системы
            $payments->pay_type = Payments::PAY_TYPE_INVOICE; //тип платежа - счет на оплату
            $payments->pay_system = Payments::PAY_SYSTEM_WALLET; //с кошелька

            $payments->pay_status = Payments::PAY_STATUS_NEW;
            $payments->pay_is_payed = Payments::PAY_NOT_PAYED;
            $payments->pay_smsCode = Helper::generateSmsCode();
            if ($payments->save()) {
                //сохраняем счет
                $invoice = new Invoice();
                $invoice->payment_id = $payments->pay_id;
                $invoice->from_user_id = \Yii::$app->user->identity->getId();
                $invoice->to_user_id = $payments->pay_user_id;
                $invoice->status = Invoice::STATUS_WAIT;
                $invoice->comment = $payments->pay_comment;
                if ($invoice->save()) {
                    \Yii::$app->session->setFlash('success', \Yii::t('common.models.user', 'Ваш запрос отправлен'));
                } else {
                    Payments::findOne($payments->pay_id)->delete();
                    \Yii::$app->session->setFlash('error', \Yii::t('common.models.user', 'Ваш запрос отклонен'));
                }
                return $this->redirect('out-invoice');
            } else {
                \Yii::$app->session->setFlash('warning', \Yii::t('common.models.user', 'Возникла ошибка при формировании запроса.'));
            }
        }
        return $this->render('add_invoice', [
            'transfer' => $transfer,
        ]);
    }
    public function actionHistoryInvoice()
    {
        $query = Invoice::find();
        $query->innerJoin(Payments::tableName(), 'pay_id = payment_id');


        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $from = !empty($params['date_start'])
                ? implode('-', array_reverse(explode('-', $params['date_start']))) . ' 00:00:00'
                : false;
            $to =  !empty($params['date_end'])
                ? implode('-', array_reverse(explode('-', $params['date_end']))) . ' 23:59:59'
                : date('Y-m-d') . ' 23:59:59';
            $in =  \Yii::$app->request->post('in', false);
            $out =  \Yii::$app->request->post('out', false);
            if (($from && $to && strtotime($from) > strtotime($to)) || (!$in && !$out)) {
                \Yii::$app->session->setFlash('error', \Yii::t('app', 'Дата "От" не может быль больше даты "До" и должен быть выбран хотя бы один тип счета'));
                return $this->redirect('history-invoice');
            }
            if ($from) {
                $query->andWhere('pay_created >= :date_from', [':date_from' => $from]);
            }

            $query->andWhere('pay_created <= :date_to', [':date_to' => $to]);

            if ($in && !$out) {//выбраны входящие
                $query->andWhere(['to_user_id' => \Yii::$app->user->identity->getId()]);
            } elseif (!$in && $out) {//выбраны исходящие
                $query->andWhere(['from_user_id' => \Yii::$app->user->identity->getId()]);
            } elseif ($in && $out) {
                $query->andWhere(['or', 'from_user_id = :user', 'to_user_id = :user'], [':user' => \Yii::$app->user->identity->getId()]);
            }

        } else {
            $query->orWhere(['from_user_id' => \Yii::$app->user->identity->getId()]);
            $query->orWhere(['to_user_id' => \Yii::$app->user->identity->getId()]);
        }

        $query->orderBy('pay_created DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('history_invoice', [
            'dataProvider' => $dataProvider
        ]);

    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCancelInvoice($id)
    {
        $model = Invoice::findOne($id);
        /** @var $model Invoice */
        if ($model === null) {
            throw new NotFoundHttpException('Такого счета не существует');
        }
        if (\Yii::$app->user->identity->getId() != $model->to_user_id) {
            throw new ForbiddenHttpException('Вам не разрешено выполнять данное действие');
        }

        $payment = Payments::findOne($model->payment_id);
        /** @var $payment Payments */
        if ($payment === null) {
            throw new NotFoundHttpException('Такого счета для оплаты не существует');
        }

        $model->status = Invoice::STATUS_CANCEL;

        $payment->pay_check_status = Payments::CHECK_STATUS_ERROR;
        $payment->pay_check_result = (string)Payments::CHECK_RESULT_UNKNOWN_ERROR;
        $payment->pay_check_result_desc = 'Canceled by user '. $model->toUser->phone. '(id '. $model->toUser->id .')';
        $payment->pay_is_checked = Payments::CHECK_CHECKED;
        $payment->pay_comment = 'Cancel date '. date('d-m-Y H:i:s');

        if ($model->validate() && $payment->validate()) {
            $model->save();
            $payment->save();
            \Yii::$app->session->setFlash('success', \Yii::t('invoice/model', 'ПАРАМЕТРЫ СЧЕТА ИЗМЕНЕНЫ'));
        } else {
            \Yii::$app->session->setFlash('error', \Yii::t('invoice/model', 'НЕ УДАЛОСЬ ИЗМЕНИТЬ ПАРАМЕТРЫ СЧЕТА'));
        }

        return $this->redirect('index');
    }

    public function actionCheckConfirmInvoice($id)
    {
        $model = Invoice::findOne($id);
        /** @var $model Invoice */
        if ($model === null) {
            throw new NotFoundHttpException('Такого счета не существует');
        }
        if (\Yii::$app->user->identity->getId() != $model->to_user_id || \Yii::$app->user->identity->getId() == $model->from_user_id) {
            throw new ForbiddenHttpException('Вам не разрешено выполнять данное действие');
        }

        $payment = Payments::findOne($model->payment_id);
        /** @var $payment Payments */
        if ($payment === null) {
            throw new NotFoundHttpException('Такого счета для оплаты не существует');
        }
        $payment->pay_smsCode = Helper::generateSmsCode();
        Yii::$app->mainsms->api->sendSMS(\Yii::$app->user->identity->phone, \Yii::t('payments', 'Код подтверждения: ', [], \Yii::$app->user->identity->user_language).$payment->pay_smsCode);
        $session = Yii::$app->session;
        $session->set('sms_code_time', time());
        if ($payment->save(FALSE)) {
            return $this->redirect(['/invoice/confirm-invoice', 'id' => $payment->pay_id]);
        } else {
            \Yii::$app->session->setFlash('error', \Yii::t('invoice/model', 'НЕ УДАЛОСЬ ИЗМЕНИТЬ ПАРАМЕТРЫ СЧЕТА'));
            return $this->redirect(['/invoice/index']);
        }

    }

    public function actionConfirmInvoice($id)
    {
        $model = Invoice::findOne($id);
        /** @var $model Invoice */
        if ($model === null) {
            throw new NotFoundHttpException('Такого счета не существует');
        }
        if (\Yii::$app->user->identity->getId() != $model->to_user_id || \Yii::$app->user->identity->getId() == $model->from_user_id) {
            throw new ForbiddenHttpException('Вам не разрешено выполнять данное действие');
        }

        $payment = Payments::findOne($model->payment_id);
        /** @var $payment Payments */
        if ($payment === null) {
            throw new NotFoundHttpException('Такого счета для оплаты не существует');
        }
        $post = \Yii::$app->request->post();
        if (isset($post['pay_smsCode'])) {
            if ($payment->pay_smsCode != $post['pay_smsCode'] && isset($post['agree'])) {
                \Yii::$app->session->setFlash('warning', 'Incorrect Sms Code!');
                return $this->redirect(['/invoice/index']);
            }

            $payment->pay_status = Payments::PAY_STATUS_DONE;
            $payment->pay_is_payed = Payments::PAY_PAYED;
            $payment->pay_result = (string)Payments::PAY_RESULT_SUCCESS;
            $payment->pay_type = Payments::PAY_TYPE_TRANSFER;//если тип платежа был "счет" меняем на "перевод"
            $payment->pay_payed = new Expression('NOW()');

            $model->status = Invoice::STATUS_DONE;

            if ($payment->validate() && $model->validate()) {
                $payment->save();
                $model->save();
                //снимаем с плательщика
                \Yii::$app->db->createCommand()->update(MoneyBallance::tableName(), [
                    'money_amount' => new Expression('money_amount - :pay_amount', [':pay_amount' => $payment->pay_summ_from])
                ], 'money_user_id = :id', [':id' => $model->to_user_id])->execute();
                //платим получателю
                \Yii::$app->db->createCommand()->update(MoneyBallance::tableName(), [
                    'money_amount' => new Expression('money_amount + :pay_amount', [':pay_amount' => $payment->pay_summ_from])
                ], 'money_user_id = :id', [':id' => $model->from_user_id])->execute();
                \Yii::$app->session->setFlash('success', \Yii::t('invoice/model', 'СЧЕТ ОПЛАЧЕН'));
            } else {
                \Yii::$app->session->setFlash('error', \Yii::t('invoice/model', 'СЧЕТ НЕ ОПЛАЧЕН'));
            }
            return $this->redirect('index');
        }

        return $this->render('confirm-invoice', [
           'payments' =>  $payment,
        ]);

    }

}