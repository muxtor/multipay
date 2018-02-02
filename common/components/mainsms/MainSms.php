<?php

/**
 * Yii2 MainSMS API wrapper
 *
 * @author Mihail 
 * @copyright Copyright (c), 2016, Mihail
 * @link 
 *
 * @version 1.0
 */

namespace common\components\mainsms;

use yii\base\Component;
use common\components\mainsms\vendor\MainSmsApi;

class MainSms extends Component {

    /**
     * @var MainSmsApi MainSmsApi class 
     */
    public $api;

    /**
     * @var string Your project name
     */
    public $projectName;

    /**
     * @var string API key which you can get here in dashboard
     */
    public $apiKey;

    /**
     * @var boolean Use SSL for requests?
     */
    public $useSsl = false;

    /**
     * @var boolean Is this test mode?
     */
    public $testMode = false;

    /**
     * Initializes class with default parameters
     *
     * Setup base class.
     * @see CApplicationComponent::init()
     */
    public function init() {
        $this->api = new MainSmsApi($this->projectName, $this->apiKey, $this->useSsl, $this->testMode);
        parent::init();
    }

    /**
     * Returns an object MainSmsApi, which initialized in init() method
     * @return MainSmsApi
     */
    public function getApi() {
        return $this->api;
    }

    /**
     * Shortcut for $this->api->sendSMS(...)
     * @return boolean|integer
     */
    public function send($recipients, $message, $sender = null, $run_at = null) {
        $sms = $this->getApi();
//        \yii\helpers\VarDumper::dump($sms, 10, 1);
//        die();
        return $sms->messageSend($recipients, $message, $sender, $run_at);
    }

    public function balance() {
        $sms = $this->getApi();
        return $sms->getBalance();
    }

}
