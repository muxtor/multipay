<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payment_in_api".
 *
 * @property integer $pay_id
 * @property string $pay_created
 * @property string $pay_updated
 * @property integer $agent_id
 * @property integer $user_id
 * @property double $pay_sum
 * @property integer $api_id
 */
class PaymentInApi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_in_api';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_id', 'user_id', 'pay_sum', 'api_id'], 'required'],
            [['pay_created', 'pay_updated'], 'safe'],
            [['agent_id', 'user_id', 'api_id'], 'integer'],
            [['pay_sum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pay_id' => Yii::t('app', 'Id'),
            'pay_created' => Yii::t('app', 'Дата'),
            'pay_updated' => Yii::t('app', 'Дата обновления'),
            'agent_id' => Yii::t('app', 'Агент'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'pay_sum' => Yii::t('app', 'Сумма'),
            'api_id' => Yii::t('app', 'Апи'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'pay_created',
                'updatedAtAttribute' => 'pay_updated',
                'value' => function() { return date('Y-m-d H:i:s'); }
            ],
        ];
    }

    public function getApi()
    {
        return $this->hasOne(Api::className(), ['api_id'=>'api_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

    public function getUserAgent()
    {
        return $this->hasOne(User::className(), ['id'=>'agent_id']);
    }
}
