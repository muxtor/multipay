<?php

namespace common\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "{{%user_wallets}}".
 *
 * @property integer $wallet_id
 * @property integer $wallet_user_id
 * @property string $wallet_number
 *
 * @property User $walletUser
 */
class UserWallets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_wallets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wallet_user_id', 'wallet_number'], 'required'],
            [['wallet_user_id'], 'integer'],
            [['wallet_number'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wallet_id' => Yii::t('app', 'Wallet ID'),
            'wallet_user_id' => Yii::t('app', 'Wallet User ID'),
            'wallet_number' => Yii::t('app', 'Wallet Number'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletUser()
    {
        return $this->hasOne(User::className(), ['id' => 'wallet_user_id']);
    }
    
    public function getUser()
    {
        $user = $this->hasOne(User::className(), ['id' => 'card_user_id']);
        return $user->exists() ? $user : new User();
    }
}
