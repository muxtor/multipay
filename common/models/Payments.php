<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use common\models\Providers;

/**
 * This is the model class for table "{{%payments}}".
 *
 * @property integer $pay_id
 * @property integer $pay_provider_id
 * @property string $pay_pc_id
 * @property integer $pay_user_id
 * @property integer $pay_status
 * @property integer $pay_check_status
 * @property string $pay_created
 * @property string $pay_payed
 * @property string $pay_pc_provider_id
 * @property string $pay_summ
 * @property string $pay_commission
 * @property string $pay_comment
 * @property string $pay_currency
 * @property string $pay_rate
 * @property string $pay_summ_from
 * @property string $pay_result
 * @property string $pay_check_result
 * @property string $pay_result_desc
 * @property string $pay_check_result_desc
 * @property string $pay_pc_provider_account
 * @property string $pay_provider_currency_pc
 * @property string $pay_protected_code
 * @property string $pay_smsCode
 * @property integer $pay_is_checked
 * @property integer $pay_is_payed
 * @property string $pay_system
 * @property integer $pay_type
 * @property integer $pay_isProtected
 * @property integer $api_id
 */
class Payments extends \yii\db\ActiveRecord
{
    const CHECK_NOT_CHECKED = 0;//проверка не проводилась
    const CHECK_CHECKED = 1;//проверка проводилась
    
    const PAY_NOT_PAYED = 0;//оплата не проводилась
    const PAY_PAYED = 1;//оплата проводилась
    
    const CHECK_STATUS_PROGRESS = 0;
    const CHECK_STATUS_SUCCESS = 1;
    const CHECK_STATUS_ERROR = 2;
    
    const CHECK_RESULT_SUCCESS = 0;
    const CHECK_RESULT_WRONG_REQUEST = 101;
    const CHECK_RESULT_SERVICE_UNAVAILABLE = 102;
    const CHECK_RESULT_WRONG_AUTH = 103;
    const CHECK_RESULT_WRONG_DB = 104;
    const CHECK_RESULT_INTERNAL_ERROR = 105;
    const CHECK_RESULT_UNKNOWN_ERROR = 200;
    
    const PAY_STATUS_NEW = 0;
    const PAY_STATUS_DONE = 1;
    const PAY_STATUS_ERROR = 2;
    
    const PAY_RESULT_SUCCESS = 0;
    const PAY_RESULT_WRONG_REQUEST = 101;
    const PAY_RESULT_SERVICE_UNAVAILABLE = 102;
    const PAY_RESULT_WRONG_AUTH = 103;
    const PAY_RESULT_WRONG_DB = 104;
    const PAY_RESULT_INTERNAL_ERROR = 105;
    const PAY_RESULT_UNKNOWN_ERROR = 200;

    const PAY_TYPE_BALANCE_API = 0;
    const PAY_TYPE_BALANCE = 1;
    const PAY_TYPE_PROVIDER = 2;
    const PAY_TYPE_TRANSFER = 3;
    const PAY_TYPE_INVOICE = 4;

    const PAY_SYSTEM_PC = 'PC';
    const PAY_SYSTEM_WMR = 'WMR';
    const PAY_SYSTEM_WMZ = 'WMZ';
    const PAY_SYSTEM_CARD = 'CARD';
    const PAY_SYSTEM_WALLET = 'WALLET';
    const PAY_SYSTEM_TERMINAL = 'TERMINAL';
    const PAY_SYSTEM_API = 'API';

    const CURRENCY_USD = 'USD';
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_AZN = 'AZN';

    public static function getCurrencyLabels()
    {
        return [
            self::CURRENCY_AZN => 'AZN',
            self::CURRENCY_RUB => 'RUB',
            self::CURRENCY_USD => 'USD',
        ];
    }

    public function isChecked()
    {
        $labels = self::isCheckedLabels();
        return array_key_exists($this->pay_is_checked, $labels) ? $labels[$this->pay_is_checked] : Yii::t('payments/model', 'Нет данных о попытках проверок');
    }
    public static function isCheckedLabels()
    {
        return [
            self::CHECK_NOT_CHECKED => Yii::t('payments/model', 'Не было попыток проверки'),
            self::CHECK_CHECKED => Yii::t('payments/model', 'Были попытки проверки')
        ];
    }
    public function isPayed()
    {
        $labels = self::isPayedLabels();
        return array_key_exists($this->pay_is_payed, $labels) ? $labels[$this->pay_is_payed] : Yii::t('payments/model', 'Нет данных о попытках оплаты');
    }
    public static function isPayedLabels()
    {
        return [
            self::PAY_NOT_PAYED => Yii::t('payments/model', 'Не было попыток оплаты'),
            self::PAY_PAYED => Yii::t('payments/model', 'Были попытки оплаты')
        ];
    }
    
    //для неавторизированных пользователей
    public static function systemPayedLabels()
    {
        return [
            self::PAY_SYSTEM_CARD => Yii::t('payments/model', 'CARD'),
            self::PAY_SYSTEM_WMR => Yii::t('payments/model', 'WMR'),
            self::PAY_SYSTEM_TERMINAL => Yii::t('payments/model', 'TERMINAL'),
        ];
    }

    //для авторизированных пользователей
    public static function systemPayedLabelsUser()
    {
        return [
            self::PAY_SYSTEM_WALLET => Yii::t('payments/model', 'WALLET'),
            self::PAY_SYSTEM_CARD => Yii::t('payments/model', 'CARD'),
            self::PAY_SYSTEM_WMR => Yii::t('payments/model', 'WMR'),
            self::PAY_SYSTEM_TERMINAL => Yii::t('payments/model', 'TERMINAL'),
            self::PAY_SYSTEM_API => Yii::t('payments/model', 'API'),
        ];
    }

    public function checkStatusLabel()
    {
        $labels = self::checkStatusLabels();
        return array_key_exists($this->pay_check_status, $labels) ? $labels[$this->pay_check_status] : Yii::t('payments/model', 'Неизвестный статус проверки');
    }

    public static function checkStatusLabels()
    {
        return [
            self::CHECK_STATUS_PROGRESS => Yii::t('payments/model', 'Проверка выполняется'),
            self::CHECK_STATUS_SUCCESS => Yii::t('payments/model', 'Проверка выполнена успешно'),
            self::CHECK_STATUS_ERROR => Yii::t('payments/model', 'Проверка выполнена с ошибками'),
        ];
        
    }
    public function payStatusLabel()
    {
        $labels = self::payStatusLabels();
        return array_key_exists($this->pay_status, $labels) ? $labels[$this->pay_status] : Yii::t('payments/model', 'Неизвестный статус оплаты');
    }

    public static function payStatusLabels()
    {
        return [
            self::PAY_STATUS_NEW => Yii::t('payments/model', 'Новый платеж'),
            self::PAY_STATUS_DONE => Yii::t('payments/model', 'Платеж проведен'),
            self::PAY_STATUS_ERROR => Yii::t('payments/model', 'Ошибочный платеж'),
        ];
        
    }
    
    public static function payTypeLabels()
    {
        return [
            self::PAY_TYPE_BALANCE => Yii::t('payments/model', 'Пополнение'),
            self::PAY_TYPE_BALANCE_API => Yii::t('payments/model', 'Пополнение через API'),
            self::PAY_TYPE_PROVIDER => Yii::t('payments/model', 'Платеж'),
            self::PAY_TYPE_TRANSFER => Yii::t('payments/model', 'Перевод'),
            self::PAY_TYPE_INVOICE => Yii::t('payments/model', 'Счет'),
        ];
        
    }
    
    public function payStatusCss()
    {
        $css = [
            self::PAY_STATUS_NEW => 'b-wait',
            self::PAY_STATUS_DONE => 'b-success',
            self::PAY_STATUS_ERROR => 'b-fail',
        ];
        return array_key_exists($this->pay_status, $css) ? $css[$this->pay_status] : '';
    }
    
    public function checkResultLabel()
    {
        $labels = self::checkResultLabels();
        return array_key_exists($this->pay_check_result, $labels) ? $labels[$this->pay_check_result] : Yii::t('payments/model', 'Неизвестный код завершения проверки');
    }

    public static function checkResultLabels()
    {
        return [
            self::CHECK_RESULT_SUCCESS => Yii::t('payments/model', 'Нет ошибок'),
            self::CHECK_RESULT_WRONG_REQUEST => Yii::t('payments/model', 'Неверный запрос'),
            self::CHECK_RESULT_SERVICE_UNAVAILABLE => Yii::t('payments/model', 'Сервис не доступен'),
            self::CHECK_RESULT_WRONG_AUTH => Yii::t('payments/model', 'Неверный логин/пароль'),
            self::CHECK_RESULT_WRONG_DB => Yii::t('payments/model', 'Ошибка базы данных'),
            self::CHECK_RESULT_INTERNAL_ERROR => Yii::t('payments/model', 'Внутренняя ошибка'),
            self::CHECK_RESULT_UNKNOWN_ERROR => Yii::t('payments/model', 'Неизвестная ошибка'),
        ];
        
    }
    public function payResultLabel()
    {
        $labels = self::payStatusLabels();
        return array_key_exists($this->pay_result, $labels) ? $labels[$this->pay_result] : Yii::t('payments/model', 'Неизвестный код завершения оплаты');
    }

    public static function payResultLabels()
    {
        return [
            self::PAY_RESULT_SUCCESS => Yii::t('payments/model', 'Нет ошибок (оплата)'),
            self::PAY_RESULT_WRONG_REQUEST => Yii::t('payments/model', 'Неверный запрос (оплата)'),
            self::PAY_RESULT_SERVICE_UNAVAILABLE => Yii::t('payments/model', 'Сервис не доступен (оплата)'),
            self::PAY_RESULT_WRONG_AUTH => Yii::t('payments/model', 'Неверный логин/пароль (оплата)'),
            self::PAY_RESULT_WRONG_DB => Yii::t('payments/model', 'Ошибка базы данных (оплата)'),
            self::PAY_RESULT_INTERNAL_ERROR => Yii::t('payments/model', 'Внутренняя ошибка (оплата)'),
            self::PAY_RESULT_UNKNOWN_ERROR => Yii::t('payments/model', 'Неизвестная ошибка (оплата)'),
        ];
        
    }
    

    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_pc_provider_account', 'pay_summ', 'pay_currency', 'pay_system'], 'required', 'on'=>'step_one'],
//            [['pay_pc_id', 'pay_status', 'pay_pc_provider_id', 'pay_summ', 'pay_commission', 'pay_currency', 'pay_rate', 'pay_result', 'pay_result_desc'], 'required'],
            [['pay_user_id', 'pay_status', 'pay_is_checked', 'pay_is_payed', 'pay_check_status', 'pay_type', 'pay_provider_id', 'pay_smsCode', 'api_id'], 'integer'],
            [['pay_created', 'pay_payed', 'pay_type'], 'safe'],
            [['pay_subpaysystem_transfer', 'pay_provider_currency_pc', 'pay_comment'], 'string'],
//            ['pay_provider_currency_pc', 'in', 'range' => [self::CURRENCY_AZN, self::CURRENCY_RUB, self::CURRENCY_USD]],
            [['pay_summ', 'pay_commission', 'pay_rate'], 'number'],
            [['pay_pc_id', 'pay_pc_provider_id', 'pay_currency', 'pay_result', 
                'pay_result_desc', 'pay_check_result_desc', 'pay_check_result', 'pay_system', 'pay_pc_provider_account'], 'string', 'max' => 255],
            [['pay_user_id'], 'default', 'value' => 0],
            ['pay_is_payed', 'default', 'value' => static::PAY_NOT_PAYED],
            ['pay_is_checked', 'default', 'value' => static::CHECK_NOT_CHECKED],
            //[['pay_summ'], 'checkProv', 'on'=>'step_one'],
            ['pay_comment', 'filter', 'filter' => 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pay_id' => Yii::t('payments/model', 'ID'),
            'pay_pc_id' => Yii::t('payments/model', 'ID в ПЦ'),
            'pay_user_id' => Yii::t('payments/model', 'Pay User ID'),
            'pay_status' => Yii::t('payments/model', 'Pay Status'),
            'pay_check_status' => Yii::t('payments/model', 'Pay Check Status'),
            'pay_created' => Yii::t('payments/model', 'Pay Created'),
            'pay_payed' => Yii::t('payments/model', 'Pay Payed'),
            'pay_pc_provider_id' => Yii::t('payments/model', 'ID ПРОВАЙДЕРА В ПЦ'),
            'pay_summ' => Yii::t('payments/model', 'СУММА_ОПЛАТЫ'),
            'pay_commission' => Yii::t('payments/model', 'Pay Commission'),
            'pay_currency' => Yii::t('payments/model', 'Pay Currency'),
            'pay_rate' => Yii::t('payments/model', 'Pay Rate'),
            'pay_result' => Yii::t('payments/model', 'Pay Result'),
            'pay_result_desc' => Yii::t('payments/model', 'Pay Result Desc'),
            'pay_check_result' => Yii::t('payments/model', 'Pay Check Result'),
            'pay_check_result_desc' => Yii::t('payments/model', 'Pay Check Result Desc'),
            'pay_type' => Yii::t('payments/model', 'Pay Type'),
            'pay_system' => Yii::t('payments/model', 'СПОСОБ_ОПЛАТЫ'),
            'pay_provider_id' => Yii::t('payments/model', 'Pay Provider ID'),
            'pay_pc_provider_account' => Yii::t('payments/model', 'АККАУНТ ПОЛЬЗОВАТЕЛЯ У ПРОВАЙДЕРА'),
            'pay_summ_from' => Yii::t('payments/model', 'СУММА_С_КОМИСИЕЙ'),
            'pay_smsCode' => Yii::t('payments/model', 'SMS_CODE_CONFIRM'),
            'pay_provider_currency_pc' => Yii::t('payments/model', 'ВАЛЮТА СЧЕТА ПРОВАЙДЕРА В ПЦ'),
            'comment' => Yii::t('payments/model', 'Comment'),
            'api_id' => Yii::t('payments/model', 'API')
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['pay_created'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    public function getProvider()
    {
        $provider = $this->hasOne(Providers::className(), ['id' => 'pay_provider_id']);
        return $provider->exists() ? $provider : new Providers;
    }
    
    public function getProviderName()
    {
        $provider = Providers::find()->andWhere(['id' => $this->pay_provider_id])->one();
        return $provider? $provider->name : 'MultiPay';
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$this->isNewRecord && $this->pay_status == self::PAY_STATUS_DONE && $this->oldAttributes['pay_status'] != self::PAY_STATUS_DONE) {
                if (!empty($this->pay_user_id) && $this->pay_type == self::PAY_TYPE_PROVIDER) {
                    \common\components\Helper::setBonus($this->pay_user_id, TariffPlanRules::TYPE_PAY);
                    $user = \common\models\User::findOne($this->pay_user_id);
                    $user->ballance->money_transaction_amount += $this->pay_summ;
                    $user->ballance->save(FALSE);
                }

                if (!empty($this->pay_user_id) && $this->pay_type == self::PAY_TYPE_BALANCE_API) {
                    \common\components\Helper::setBonus($this->pay_user_id, TariffPlanRules::TYPE_PAY);
                    $user = \common\models\User::findOne($this->pay_user_id);
                    $user->ballance->money_transaction_amount += $this->pay_summ;
                    $user->ballance->money_amount += $this->pay_summ;
                    $user->ballance->save(FALSE);
                }
                
                if (!empty($this->pay_user_id) && $this->pay_type == self::PAY_TYPE_TRANSFER) {
                    \common\components\Helper::setBonus($this->pay_user_id, TariffPlanRules::TYPE_WALLET_TRANSFER);
                }

                if (!empty($this->pay_provider_id)) {
                    $this->provider->pay_count += $this->pay_summ;
                    $this->provider->save(FALSE);
                }
                
            }

            return true;
        }
        return false;
    }
    
    public function checkProv($attribute, $params)
    {
        if (!empty($this->pay_provider_id) && ($this->$attribute < $this->provider->pay_sum_min || $this->$attribute > $this->provider->pay_sum_max)) {
            $this->addError($attribute, Yii::t('payments/model', 'Некорректная сумма оплаты'));
        }
    }
    
}
