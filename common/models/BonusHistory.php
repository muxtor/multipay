<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "bonus_history".
 *
 * @property integer $bh_id
 * @property integer $bh_user_id
 * @property integer $bh_type
 * @property integer $bh_period
 * @property integer $bh_bonus
 * @property string $bh_create
 *
 * @property User $bhUser
 */
class BonusHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bonus_history';
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'bh_create',
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
            [['bh_user_id', 'bh_type', 'bh_period', 'bh_bonus'], 'required'],
            [['bh_user_id', 'bh_type', 'bh_period', 'bh_bonus'], 'integer'],
            [['bh_create'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bh_id' => Yii::t('common.models.BonusHistory', 'Bh ID'),
            'bh_user_id' => Yii::t('common.models.BonusHistory', 'Bh User ID'),
            'bh_type' => Yii::t('common.models.BonusHistory', 'Правило'),
            'bh_period' => Yii::t('common.models.BonusHistory', 'Периодичность'),
            'bh_bonus' => Yii::t('common.models.BonusHistory', 'Размер бонуса'),
            'bh_create' => Yii::t('common.models.BonusHistory', 'Дата'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'bh_user_id']);
    }
    
    public function getTypeLabel()
    {
        $labels = TariffPlanRules::getTypeLabels();
        return array_key_exists($this->bh_type, $labels) ? $labels[$this->bh_type] : Yii::t('payments/model', 'Нет данных');
    }
}
