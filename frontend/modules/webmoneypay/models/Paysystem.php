<?php namespace frontend\modules\webmoneypay\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Paysystem extends Model
{
    public $amount;
    public $type;
    
    const TYPE_WMR = 'WMR';
    const TYPE_WMU = 'WMU';
    const TYPE_WMZ = 'WMZ';
    const TYPE_WME = 'WME';
    
    
    public static function getTypeLabels()
    {
        return [
            static::TYPE_WMR => 'WMR',
            static::TYPE_WMU => 'WMU',
            static::TYPE_WMZ => 'WMZ',
            static::TYPE_WME => 'WME',
        ];
    } 
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'type'], 'required'],
            [['amount'], 'double'],
            [['type'], 'validateType'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => Yii::t('paysystem','Получите на баланс'),
            'type' => Yii::t('paysystem','Способ оплаты'),
        ];
    }
    
    public function validateType($attribute, $params)
    {
        $type = \common\models\WmSetting::find()->where(['wms_name'=>$this->$attribute])->one();
        if (!$type || empty($type->wms_purse)) {
            $this->addError($attribute, Yii::t('paysystem','Данный сервис сейчас недоступен!'));
        }
    }

}
