<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wm_setting".
 *
 * @property integer $wms_id
 * @property string $wms_name
 * @property string $wms_purse
 * @property string $wms_rate
 */
class WmSetting extends \yii\db\ActiveRecord
{
    
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
    public static function tableName()
    {
        return 'wm_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wms_name', 'wms_rate'], 'required'],
            [['wms_rate'], 'number'],
            [['wms_name', 'wms_purse'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wms_id' => Yii::t('common.models.MoneyBallance', 'Wms ID'),
            'wms_name' => Yii::t('common.models.MoneyBallance', 'Wms Name'),
            'wms_purse' => Yii::t('common.models.MoneyBallance', 'Wms Purse'),
            'wms_rate' => Yii::t('common.models.MoneyBallance', 'Wms Rate'),
        ];
    }
}
