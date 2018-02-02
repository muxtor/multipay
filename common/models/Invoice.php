<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property integer $id
 * @property integer $payment_id
 * @property integer $from_user_id
 * @property integer $to_user_id
 * @property integer $status
 * @property string $comment
 *
 * @property User $toUser
 * @property User $fromUser
 * @property Payments $payment
 */
class Invoice extends ActiveRecord
{
    const STATUS_WAIT = 0;
    const STATUS_CANCEL = 1;
    const STATUS_DONE = 2;

    public function statusLabel()
    {
        $labels = self::getStatusLables();
        return array_key_exists($this->status, $labels) ? $labels[$this->status] : Yii::t('invoice/model', 'Неизвестный статус счета');
    }

    public static function getStatusLables()
    {
        return [
            self::STATUS_WAIT => Yii::t('invoice/model', 'ОЖИДАЕМ'),
            self::STATUS_CANCEL => Yii::t('invoice/model', 'ВЫПОЛНЕНО'),
            self::STATUS_DONE => Yii::t('invoice/model', 'ОТМЕНЕН'),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id', 'from_user_id', 'to_user_id'], 'required'],
            [['payment_id', 'from_user_id', 'to_user_id', 'status'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['comment'], 'filter', 'filter' => 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('invoice/model', 'ID'),
            'payment_id' => Yii::t('invoice/model', 'Payment ID'),
            'from_user_id' => Yii::t('invoice/model', 'From User ID'),
            'to_user_id' => Yii::t('invoice/model', 'To User ID'),
            'status' => Yii::t('invoice/model', 'Status'),
            'comment' => Yii::t('invoice/model', 'Comment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        $toUser = $this->hasOne(User::className(), ['id' => 'to_user_id']);
        return $toUser->exists() ? $toUser : new User();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        $fromUser = $this->hasOne(User::className(), ['id' => 'from_user_id']);
        return $fromUser->exists() ? $fromUser : new User();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        $payment = $this->hasOne(Payments::className(), ['pay_id' => 'payment_id']);
        return $payment->exists() ? $payment : new Payments();
    }
}
