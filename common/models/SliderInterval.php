<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slider_interval".
 *
 * @property integer $interval_id
 * @property integer $interval_main
 * @property integer $interval_user
 * @property integer $interval_partner
 */
class SliderInterval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slider_interval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['interval_main', 'interval_user','interval_partner'], 'integer', 'min' => 1],
            [['interval_main', 'interval_user','interval_partner'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'interval_id' => 'Interval ID',
            'interval_main' => 'Главный слайдер (сек)',
            'interval_user' => 'Нижний слайдер (сек)',
            'interval_partner' => 'Слайдер партнеры (сек)',
        ];
    }
}
