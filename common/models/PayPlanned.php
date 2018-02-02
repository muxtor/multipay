<?php

namespace common\models;

use Yii;
use common\models\Providers;
use common\models\User;

/**
 * This is the model class for table "{{%pay_planned}}".
 *
 * @property integer $pp_id
 * @property integer $pp_user_id
 * @property integer $pp_provider_id
 * @property string $pp_account
 * @property string $pp_name
 * @property string $pp_system
 * @property string $pp_pay_date
 * @property string $pp_notify_date
 * @property integer $pp_type
 * @property integer $pp_is_auto
 * @property integer $pp_is_notif
 * @property integer $pay_notif_day_amount
 * @property string $pp_currency
 * @property string $pp_summ
 *
 * @property Providers $ppProvider
 * @property User $ppUser
 */
class PayPlanned extends \yii\db\ActiveRecord
{
    public $days_of_week;
    public $days_of_month;
    public $pay_time;

    const TYPE_ONCE = 1;
    const TYPE_WEEK = 7;
    const TYPE_MONTH = 31;

    public static function getTypeLabels()
    {
        return [
            static::TYPE_ONCE => Yii::t('payplanned/model', 'ONE_TIME'),
            static::TYPE_WEEK => Yii::t('payplanned/model', 'EVERY_WEEK'),
            static::TYPE_MONTH => Yii::t('payplanned/model', 'EVERY_MONTH'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_planned}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pp_user_id', 'pp_provider_id', 'pp_account', 'pp_name', 'pp_pay_date', 'pp_type', 'pp_system'], 'required'],
            [['pp_user_id', 'pp_provider_id', 'pp_type', 'pp_is_auto', 'pp_is_notif', 'pay_notif_day_amount'], 'integer'],
            [['pp_is_auto', 'pp_is_notif'], 'default', 'value' => 0],
            [['pp_pay_date', 'pp_notify_date', 'days_of_week', 'days_of_month', 'pay_time'], 'safe'],
            [['pp_account', 'pp_name', 'pp_system', 'pp_currency'], 'string', 'max' => 255],
            [['pp_summ'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pp_id' => Yii::t('payplanned/model', 'PAY_PLANNED_ID'),
            'pp_user_id' => Yii::t('payplanned/model', 'PAY_PLANNED_USER'),
            'pp_provider_id' => Yii::t('payplanned/model', 'PAY_PLANNED_PROVIDER'),
            'pp_account' => Yii::t('payplanned/model', 'PAY_PLANNED_ACCOUNT'),
            'pp_name' => Yii::t('payplanned/model', 'PAY_PLANNED_NAME'),
            'pp_system' => Yii::t('payplanned/model', 'PAY_PLANNED_SYSTEM'),
            'pp_pay_date' => Yii::t('payplanned/model', 'PAY_PLANNED_PAY_DATE'),
            'pp_notify_date' => Yii::t('payplanned/model', 'PAY_PLANNED_NOTIFY_DATE'),
            'pp_type' => Yii::t('payplanned/model', 'PAY_PLANNED_TYPE'),
            'pp_summ' => Yii::t('payplanned/model', 'PAY_PLANNED_SUMM'),
            'pp_currency' => Yii::t('payplanned/model', 'PAY_PLANNED_CURRENCY'),
            'pay_time' => Yii::t('payplanned/model', 'PAY_PLANNED_TIME'),
            'pp_is_auto' => Yii::t('payplanned/model', 'PAY_IS_AUTO'),
            'pp_is_notif' => Yii::t('payplanned/model', 'PAY_IS_NOTIF'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        $provider = $this->hasOne(Providers::className(), ['id' => 'pp_provider_id']);
        return $provider->exists() ? $provider : new Providers();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        $user = $this->hasOne(User::className(), ['id' => 'pp_user_id']);
        return $user->exists() ? $user : new User();
    }

    public static function getWeekDays()
    {
        return [
            1 => Yii::t('payment', 'ПОНЕДЕЛЬНИК'),
            2 => Yii::t('payment', 'ВТОРНИК'),
            3 => Yii::t('payment', 'СРЕДА'),
            4 => Yii::t('payment', 'ЧЕТВЕРГ'),
            5 => Yii::t('payment', 'ПЯТНИЦА'),
            6 => Yii::t('payment', 'СУББОТА'),
            7 => Yii::t('payment', 'ВОСКРЕСЕНЬЕ'),
        ];
    }

    public static function getMonthDays()
    {
        return [
            1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8,
            9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15,
            16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20, 21 => 21, 22 => 22,
            23 => 23, 24 => 24, 25 => 25, 26 => 26, 27 => 27, 28 => 28, 29 => 29,
            30 => 30, 31 => 31,
        ];
    }

    public static function getMonthNames()
    {
        return [
          1 => Yii::t('payment', 'ЯНВАРЯ'),
          2 => Yii::t('payment', 'ФЕВРАЛЯ'),
          3 => Yii::t('payment', 'МАРТА'),
          4 => Yii::t('payment', 'АПРЕЛЯ'),
          5 => Yii::t('payment', 'МАЯ'),
          6 => Yii::t('payment', 'ИЮНЯ'),
          7 => Yii::t('payment', 'ИЮЛЯ'),
          8 => Yii::t('payment', 'АВГУСТА'),
          9 => Yii::t('payment', 'СЕНТЯБРЯ'),
          10 => Yii::t('payment', 'ОКТЯБРЯ'),
          11 => Yii::t('payment', 'НОЯБРЯ'),
          12 => Yii::t('payment', 'ДЕКАБРЯ'),
        ];
    }
}
