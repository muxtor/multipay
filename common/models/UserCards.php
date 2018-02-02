<?php

namespace common\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "{{%user_cards}}".
 *
 * @property integer $card_id
 * @property integer $card_user_id
 * @property string $card_number
 */
class UserCards extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_cards}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_user_id', 'card_number'], 'required'],
            [['card_user_id'], 'integer'],
            [['card_number'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'card_id' => Yii::t('app', 'Card ID'),
            'card_user_id' => Yii::t('app', 'Card User ID'),
            'card_number' => Yii::t('app', 'Card Number'),
        ];
    }
    
    public function getUser()
    {
        $user = $this->hasOne(User::className(), ['id' => 'card_user_id']);
        return $user->exists() ? $user : new User();
    }
}
