<?php
namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Api;
use common\models\Payments;
use common\models\PaymentInApi;

/**
 * Site controller
 */
class ApiController extends Controller
{

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = ($action->id !== 'balance');
        return parent::beforeAction($action);
    }

    /*
    sum - Сумма пополнения баланса (пример: 5, 45, 5.23, 12.78)
    login - Логин пользователя(телефон), которому пополняем баланс (пример: +994-333-333-3333, 9943333333333)
    key - Key берется из таблицы выше
    id - Id берется из таблицы выше
     */
    public function actionBalance()
    {
        if (isset($_POST['sum'], $_POST['login'], $_POST['key'], $_POST['id'])) {

            $api = Api::find()->where(['api_id'=>$_POST['id'], 'api_key'=>$_POST['key'], 'api_status' => 1])->one();

            $phone = str_replace(array(' ', '-', ')', '(', '+'), '', $_POST['login']);
            $user = User::find()->where(['phone'=>$phone])->one();

            if ($api !== null AND $user !== null) {

                //$wmpurse = \common\models\WmSetting::find()->where(['wms_name'=>Payments::CURRENCY_AZN])->one();

                $payments = new Payments();
                //$payments->setScenario('balance');
                $payments->pay_user_id = $user->id;
                $payments->pay_status = Payments::PAY_STATUS_NEW;
                $payments->pay_summ = round($_POST['sum'], 2);
                $payments->pay_summ_from = round($_POST['sum'], 2);
                $payments->pay_currency = Payments::CURRENCY_AZN;
                $payments->pay_type = Payments::PAY_TYPE_BALANCE_API;
                $payments->pay_system = Payments::CURRENCY_AZN;
                $payments->pay_comment = 'Пополнение баланса пользователя через API';
                $payments->pay_smsCode = \common\components\Helper::generateSmsCode();
                $payments->api_id = $api->api_id;

                if ($payments->save()) {
                    $payments->pay_status = Payments::PAY_STATUS_DONE;
                    if ($payments->save()) {
                        $pay_api = new PaymentInApi;
                        $pay_api->pay_sum = round($_POST['sum'], 2);
                        $pay_api->user_id = $user->id;
                        $pay_api->agent_id = $api->user_id;
                        $pay_api->api_id = $api->api_id;
                        if ($pay_api->save()) {
                            return 1;
                        }
                    }
                }
            }
        }

        return 0;
    }
}