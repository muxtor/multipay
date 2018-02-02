<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\Payments;
use yii\db\Query;

class PayPcSend extends Component
{
    /**
     * @param $payment_id integer
     * @param $pay_date string
     * @param $pc_provider_id integer
     * @param $pc_provider_account string
     * @param $summ float
     * @param $payment_currency string
     * @return bool|mixed
     */
    public static function makePay($payment_id, $pay_date, $pc_provider_id, $pc_provider_account, $summ, $payment_currency)
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
        $add_payment = $request->addChild('add-payment');
        $payment = $add_payment->addChild('payment');
        $payment->addAttribute('id', $payment_id);
        $payment->addAttribute('date', explode(' ', $pay_date)[0].'T'.explode(' ', $pay_date)[1]);
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
     * @param $cur_date string
     * @return mixed
     */
    public static function analizePayResponce($payment_id, $result, $cur_date)
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
            $pay->pay_result = $error_code;
            $pay->pay_result_desc = $error_desc;
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            return $pay->save() ? true : false;
        }
        if ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_NEW) {
            $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
            $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
            $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
            $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            if ($pay->save()) {
                $payments_in_pay = (new Query())
                    ->select('payment_id')
                    ->from('{{%payment_in_pay}}')
                    ->column();
                if (!in_array($pay->pay_id, $payments_in_pay)) {
                    Yii::$app->db->createCommand()->insert('{{%payment_in_pay}}', [
                        'payment_id' => $pay->pay_id,
                        'payment_date' => $cur_date])->execute();
                }
                return true;
            }
            return false;
        } elseif ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_DONE) {
            $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
            $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
            $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
            $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            Yii::$app->db->createCommand()->delete('{{%payment_in_pay}}',
                ['payment_id' => $pay->pay_id])->execute();
            return $pay->save() ? true : false;
        } elseif ((string)$response->{'add-payment'}->payment['status'] === (string)  Payments::PAY_STATUS_ERROR) {
            $pay->pay_result = (string)$response->{'add-payment'}->payment['result'];
            $pay->pay_result_desc = (string)$response->{'add-payment'}->payment['description'];
            $pay->pay_status = (int)(string)$response->{'add-payment'}->payment['status'];
            $pay->pay_pc_id = (string)$response->{'add-payment'}->payment['transaction-id'];
            $pay->pay_payed = $cur_date;
            $pay->pay_is_payed = Payments::PAY_PAYED;
            Yii::$app->db->createCommand()->delete('{{%payment_in_pay}}',
                ['payment_id' => $pay->pay_id])->execute();
            return $pay->save() ? true : false;
        }
        return false;
    }
}