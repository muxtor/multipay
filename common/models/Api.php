<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "api".
 *
 * @property integer $api_id
 * @property string $api_title
 * @property string $api_description
 * @property string $api_key
 * @property integer $user_id
 */
class Api extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['api_title', 'api_description'], 'required'],
            [['api_title'], 'string', 'max' => 50],
            [['api_description'], 'string', 'max' => 255],
            [['api_key'], 'string', 'max' => 20],
            [['api_key'], 'unique'],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'api_id' => 'Id',
            'api_title' => 'Название',
            'api_description' => 'Описание',
            'api_key' => 'KEY',
            'user_id' => 'Агент'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->user_id = Yii::$app->user->id;
                $this->api_key = Yii::$app->security->generateRandomString(20);
            }

            return true;
        }
        return false;
    }
}
