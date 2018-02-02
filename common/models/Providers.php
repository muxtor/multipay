<?php
namespace common\models;

use Yii;
use common\models\Country;
use common\models\Payments;

/**
 * This is your extended tree model.
 *
 * @property string $description
 * @property string $pc_id
 * @property string $regexp
 * @property string $logo
 * @property string $logo_sidebar
 * @property string $account
 * @property string $currency
 * @property integer $country_id
 * @property integer $show_on_start
 * @property integer $status
 * @property number $comission_percent
 */

class Providers extends \kartik\tree\models\Tree
{
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_TEST = 2;

    const CURRENCY_AZN = 'AZN';
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_USD = 'USD';

    public $logofile;
    public $sidebarlogofile;

    public function currencyLabel()
    {
        $labels = self::getCurrencyLabels();
        return array_key_exists($this->currency, $labels) ? $labels[$this->currency] : Yii::t('provider/model', 'НЕИЗВЕСТНАЯ_ВАЛЮТА');
    }

    public static function getCurrencyLabels()
    {
        return [
            self::CURRENCY_AZN => self::CURRENCY_AZN,
            self::CURRENCY_RUB => self::CURRENCY_RUB,
            self::CURRENCY_USD => self::CURRENCY_USD
        ];
    }
    public function statusLabel()
    {
        $labels = self::getStatusLabels();
        return array_key_exists($this->status, $labels) ? $labels[$this->status] : Yii::t('provider/model', 'НЕИЗВЕСТНЫЙ_СТАТУС');
    }

    public static function getStatusLabels()
    {
        return [
            self::STATUS_DISABLED => Yii::t('provider/model', 'ОТКЛЮЧЕН'),
            self::STATUS_ACTIVE => Yii::t('provider/model', 'АКТИВЕН'),
            //self::STATUS_TEST => Yii::t('provider/model', 'ТЕСТ')
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'providers';
    }    

    /**
     * Override isDisabled method if you need as shown in the  
     * example below. You can override similarly other methods
     * like isActive, isMovable etc.
     */
    public function isDisabled()
    {
        if (Yii::$app->user->identity->username !== 'multiadmin') {
            return true;
        }
        return parent::isDisabled();
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            [['logofile', 'description', 'pc_id', 'regexp', 'country_id', 'logo', 'sidebarlogofile', 'logo_sidebar', 'pay_count'], 'safe'],
            [['logofile', 'sidebarlogofile'], 'image', 'extensions' => ['jpeg', 'png', 'jpg']],
            [['country_id','show_on_start', 'status'], 'integer'],
            [['pay_sum_min', 'pay_sum_max'], 'number'],
            [['pc_id', 'regexp', 'account', 'currency'], 'string', 'max' => 255],
            ['comission_percent', 'number', 'min' => 0, 'max' => 100],
//            ['currency', 'in', 'range' => [self::CURRENCY_AZN, self::CURRENCY_RUB, self::CURRENCY_USD]]
        ]);
        return $rules;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pc_id' => Yii::t('provider/model', 'ID_В_ПЦ'),
            'description' => Yii::t('provider/model', 'ОПИСАНИЕ'),
            'regexp' => Yii::t('provider/model', 'Regexp'),
            'country_id' => Yii::t('provider/model', 'СТРАНА'),
            'logo' => Yii::t('provider/model', 'Logo'),
            'account' => Yii::t('provider/model', 'Account'),
            'logo_sidebar' => Yii::t('provider/model', 'Sidebar Logo'),
            'show_on_start' => Yii::t('provider/model', 'Show On Start'),
            'comission_percent' => Yii::t('provider/model', 'ПРОЦЕНТ_КОМИССИИ'),
            'status' => Yii::t('provider/model', 'СТАТУС'),
            'pay_count' => Yii::t('provider/model', 'СУММА_ОПЛАТ'),
            'pay_sum_min' => Yii::t('provider/model', 'Минимальный платеж'),
            'pay_sum_max' => Yii::t('provider/model', 'Максимальный платеж'),
        ];
    }
    
    public function getCountry()
    {
        $country = $this->hasOne(Country::className(), ['id' => 'country_id']);
        return $country->exists() ? $country : new Country();
    }
    
    public function afterDelete()
    {
        parent::afterDelete();
        $file = \Yii::$app->params['files.uploads.path'] . '/providers-logo/'.$this->logo;
        if (is_file($file)) {
            unlink($file);
        };
    }
    
    public function getPayments()
    {
        return $this->hasMany(Payments::className(), ['pay_provider_id' => 'id']);
    }
}

