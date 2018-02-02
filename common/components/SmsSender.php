<?php
/**
 * @author Pavlyenko Maksym <maxmpvl@gmail.com>
 */

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;

class SmsSender extends Component
{
    public $url;
    public $login;
    public $password;

    public  function send($phone, $message)
    {
        $this->_request('/sys/send.php', [
            'login'=>$this->login,
            'psw'=>md5($this->password),
            'phones'=> $phone,
            'mes'=> $message,
        ]);
    }

    private function _request($url, $data, $type = 'GET')
    {
        try{
            $ch = curl_init();
            $url = $this->url . $url;

            $_data = http_build_query($data);
            if ($type == 'POST'){
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            if ($type == 'GET'){
                $url = $url . '?' . $_data;
            }

            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_TIMEOUT, 60);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);

            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }
        catch(Exception $e){
            return null;
        }
    }
}