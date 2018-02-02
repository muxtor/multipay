<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "user_login_stats".
 *
 * @property integer $uls_id
 * @property integer $uls_user_id
 * @property string $uls_IP
 * @property string $uls_app
 * @property string $uls_location
 * @property string $uls_date_visit
 *
 * @property User $ulsUser
 */
class UserLoginStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_login_stats';
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'uls_date_visit',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uls_user_id', 'uls_IP', 'uls_app', 'uls_location'], 'required'],
            [['uls_user_id'], 'integer'],
            [['uls_date_visit'], 'safe'],
            [['uls_IP'], 'string', 'max' => 50],
            [['uls_app', 'uls_location'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uls_id' => Yii::t('common.models.UserLoginStats', 'Uls ID'),
            'uls_user_id' => Yii::t('common.models.UserLoginStats', 'Uls User ID'),
            'uls_IP' => Yii::t('common.models.UserLoginStats', 'Uls  Ip'),
            'uls_app' => Yii::t('common.models.UserLoginStats', 'Uls App'),
            'uls_location' => Yii::t('common.models.UserLoginStats', 'Uls Location'),
            'uls_date_visit' => Yii::t('common.models.UserLoginStats', 'Uls Date Visit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUlsUser()
    {
        return $this->hasOne(User::className(), ['id' => 'uls_user_id']);
    }
}
