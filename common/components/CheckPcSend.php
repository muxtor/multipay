<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\Payments;
use yii\db\Query;

class CheckPcSend extends Component
{
    /**
     * @param $payment_id integer
     * @param $pc_provider_id string
     * @param $pc_provider_account string
     * @param $summ float
     * @param $payment_currency string
     * @return bool|mixed
     */
    public static function checkPay($payment_id, $pc_provider_id, $pc_provider_account, $summ, $payment_currency)
    {
        if ($payment_currency == Payments::CURRENCY_AZN) {
            $terminal = Yii::$app->settings->get('AZN_terminal', 'currency');
            $currency = Yii::$app->settings->get('AZN_currency_ISO', 'currency');
        } elseif ($payment_currency == Payments::CURRENCY_RUB) {
            $terminal = Yii::$app->settings->get('RUB_terminal', 'currency');
            $currency = Yii::$app->settings->get('RUB_currency_ISO', 'currency');
        } elseif ($payment_currency == Payments::CURRENCY_USD) {
            $terminal = Yii::$app->settings->get('USD_terminal', 'currency');
            $currency = Yii::$app->settings->get('USD_currency_ISO', 'currency');
        } else {
            return false;
        }
        $url = 'https://pays-api-2012.armax.ru/pays-api2012/api/v1/pays';
        $request = new \SimpleXMLElement('<request></request>');
        $auth = $request->addChild('auth');
        $auth->addAttribute('dealer', Yii::$app->settings->get('dealer_pc', 'currency'));
        $auth->addAttribute('login', Yii::$app->settings->get('login_pc', 'currency'));
        $auth->addAttribute('password', Yii::$app->settings->get('password_pc', 'currency'));
        $auth->addAttribute('terminal', $terminal);
        $check_payment = $request->addChild('check-payment');
        $payment = $check_payment->addChild('payment');
        $payment->addAttribute('id', $payment_id);
        $payment->addAttribute('rate', '1');
        $from = $payment->addChild('from');
        $from->addAttribute('commission', '0.00');
        $from->addAttribute('currency', $currency);
        $from->addAttribute('summ', $summ);
        $to = $payment->addChild('to');
        $to->addAttribute('account', $pc_provider_account);
        $to->addAttribute('provider', $pc_provider_id);
        $xml = $request->asXML();
        if (!$xml) {
            return false;
        }
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain;charset=utf-8'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * @param $payment_id integer
     * @param $result string
     * @return mixed
     */
    public static function analizeCheckResponce($payment_id, $result)
    {
        $pay = Payments::findOne($payment_id);
        /* @var $pay Payments */
        if (!$pay) {
            return false;
        }
        $response = new \SimpleXMLElement($result);
        if ((string)$response['result'] !== '0') {
            $error_code = (string)$response['result'];
            $error_desc = (string)$response['result-description'];
            $pay->pay_check_result = $error_code;
            $pay->pay_check_result_desc = $error_desc;
            $pay->pay_is_checked = Payments::CHECK_CHECKED;
            return $pay->save() ? true : false;
        } else {
            if ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_PROGRESS) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                if ($pay->save()) {
                    $payments_in_check = (new Query())
                        ->select('payment_id')
                        ->from('{{%payment_in_check}}')
                        ->column();
                    if (!in_array($pay->pay_id, $payments_in_check)) {
                        Yii::$app->db->createCommand()->insert('{{%payment_in_check}}',
                            ['payment_id' => $pay->pay_id])->execute();
                    }
                    return true;
                }
                return false;
            } elseif ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_SUCCESS) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                if ($pay->save()) {
                    $cur_date = date('Y-m-d H:i:s');
                    $result = PayPcSend::makePay($pay->pay_id, $cur_date, $pay->pay_pc_provider_id, $pay->pay_pc_provider_account,
                        $pay->pay_summ_from, $pay->pay_currency);
                    if ($result) {
                        PayPcSend::analizePayResponce($pay->pay_id, $result, $cur_date);
                    }
                    return true;
                }
                return false;
            } elseif ((string)$response->{'check-payment'}->payment['status'] === (string)  Payments::CHECK_STATUS_ERROR) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['description'];
                $pay->pay_check_status = (int)(string)$response->{'check-payment'}->payment['status'];
                $pay->pay_rate = (float)(string)$response->{'check-payment'}->payment['rate'];
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                Yii::$app->db->createCommand()->delete('{{%payment_in_check}}',
                    ['payment_id' => $pay->pay_id])->execute();
                return $pay->save() ? true : false;
            } elseif ((string)$response->{'check-payment'}->payment['result'] == (string)Payments::CHECK_RESULT_UNKNOWN_ERROR) {
                $pay->pay_check_result = (string)$response->{'check-payment'}->payment['result'];
                $pay->pay_check_result_desc = (string)$response->{'check-payment'}->payment['result-description'];
                $pay->pay_check_status = Payments::CHECK_STATUS_ERROR;
                $pay->pay_is_checked = Payments::CHECK_CHECKED;
                Yii::$app->db->createCommand()->delete('{{%payment_in_check}}',
                    ['payment_id' => $pay->pay_id])->execute();
                return $pay->save() ? true : false;
            }
        }
        return false;
    }
}