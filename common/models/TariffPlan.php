<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;

/**
 * This is the model class for table "tariff_plan".
 *
 * @property integer $tp_id
 * @property integer $tp_transfer_min
 * @property integer $tp_transfer_max
 */
class TariffPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tariff_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tp_transfer_min', 'tp_transfer_max'], 'required'],
            [['tp_transfer_min', 'tp_transfer_max'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tp_id' => Yii::t('common.models.TariffPlan', 'Tp ID'),
            'tp_transfer_min' => Yii::t('common.models.TariffPlan', 'Tp Transfer Min'),
            'tp_transfer_max' => Yii::t('common.models.TariffPlan', 'Tp Transfer Max'),
        ];
    }
    
    public function behaviors()
    {
        return [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'descr'],
                // translationRelation => 'translations',
                // translationLanguageAttribute => 'language',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(TariffPlanTranslation::className(), ['tpt_tp_id' => 'tp_id']);
    }
    
    public function getRules()
    {
        return $this->hasMany(TariffPlanRules::className(), ['tpr_tp_id' => 'tp_id']);
    }
}
