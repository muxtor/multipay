<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country_mask".
 *
 * @property integer $id
 * @property string $name
 * @property integer $group_id
 * @property string $name_en
 * @property string $iso
 * @property string $timezone
 * @property string $lat
 * @property string $lon
 * @property string $tel_code
 * @property string $tel_mask
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country_mask';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['name'], 'string', 'max' => 45],
            [['name_en', 'iso', 'timezone', 'tel_code', 'tel_mask'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('country', 'ID'),
            'name' => Yii::t('country', 'Name'),
            'group_id' => Yii::t('country', 'Group ID'),
            'name_en' => Yii::t('country', 'Name En'),
            'iso' => Yii::t('country', 'Iso'),
            'timezone' => Yii::t('country', 'Timezone'),
            'lat' => Yii::t('country', 'Lat'),
            'lon' => Yii::t('country', 'Lon'),
            'tel_code' => Yii::t('country', 'Tel Code'),
            'tel_mask' => Yii::t('country', 'Tel Mask'),
        ];
    }
}
