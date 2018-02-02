<?php

namespace common\models;

use Yii;
use creocoder\translateable\TranslateableBehavior;

/**
 * This is the model class for table "tariff_plan_rules".
 *
 * @property integer $tpr_id
 * @property integer $tpr_tp_id
 * @property integer $tpr_period
 * @property integer $tpr_bonus_value
 *
 * @property TariffPlan $tprTp
 */
class TariffPlanRules extends \yii\db\ActiveRecord
{
    
    const PERIOD_ONETIME = 0;
    const PERIOD_MULTIPLE = 1;
    
    const TYPE_REG = 1;
    const TYPE_PAY = 2;
    const TYPE_PROFIL = 3;
    const TYPE_REG_SELF = 4;
    const TYPE_WALLET_TERMINAL = 5;
    const TYPE_REG_CARD = 6;
    const TYPE_WALLET_CARD = 7;
    const TYPE_WALLET_TRANSFER = 8;
    const TYPE_MOBILE_TRANSFER = 9;
    
    const STATUS_BLOCK = 0;
    const STATUS_ACTIV = 1;
    
    
    public function getPeriodLabel()
    {
        $labels = self::getPeriodLabels();
        return array_key_exists($this->tpr_period, $labels) ? $labels[$this->tpr_period] : Yii::t('payments/model', 'Нет данных');
    }
    
    public function getStatusLabel()
    {
        $labels = self::getStatusLabels();
        return array_key_exists($this->tpr_active, $labels) ? $labels[$this->tpr_active] : Yii::t('payments/model', 'Нет данных');
    }
    
    public function getTypeLabel()
    {
        $labels = self::getTypeLabels();
        return array_key_exists($this->tpr_type, $labels) ? $labels[$this->tpr_type] : Yii::t('payments/model', 'Нет данных');
    }
    
    public static function getStatusLabels()
    {
        return [
            self::STATUS_BLOCK => Yii::t('common.models.TariffPlanRules', 'Выкл.'),
            self::STATUS_ACTIV => Yii::t('common.models.TariffPlanRules', 'Включено')
        ];
    }
    
    public static function getPeriodLabels()
    {
        return [
            self::PERIOD_ONETIME => Yii::t('common.models.TariffPlanRules', 'Разово'),
            self::PERIOD_MULTIPLE => Yii::t('common.models.TariffPlanRules', 'За каждое действие')
        ];
    }
    
    public static function getTypeLabels()
    {
        return [
            self::TYPE_REG => Yii::t('common.models.TariffPlanRules', 'Регистрация друга(реферала)'),
            self::TYPE_PAY => Yii::t('common.models.TariffPlanRules', 'Проведение платежа'),
            self::TYPE_PROFIL => Yii::t('common.models.TariffPlanRules', 'Заполнение профиля'),
            self::TYPE_REG_SELF => Yii::t('common.models.TariffPlanRules', 'Регистрация кошелька'),
            self::TYPE_WALLET_TERMINAL => Yii::t('common.models.TariffPlanRules', 'Пополнение кошелька в терминале'),
            self::TYPE_REG_CARD => Yii::t('common.models.TariffPlanRules', 'Регистрация банковской карты'),
            self::TYPE_WALLET_CARD => Yii::t('common.models.TariffPlanRules', 'Пополнение кошелька картой'),
            self::TYPE_WALLET_TRANSFER => Yii::t('common.models.TariffPlanRules', 'Перевод с кошелька на кошелек другого пользователя'),
            self::TYPE_MOBILE_TRANSFER => Yii::t('common.models.TariffPlanRules', 'Пополнение моб.телефона'),
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tariff_plan_rules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tpr_tp_id', 'tpr_type'], 'required'],
            [['tpr_tp_id', 'tpr_period', 'tpr_bonus_value', 'tpr_type', 'tpr_active'], 'integer'],
            ['tpr_type', 'unique', 'targetAttribute' => ['tpr_type', 'tpr_tp_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tpr_id' => Yii::t('common.models.TariffPlanRules', 'ID правила'),
            'tpr_tp_id' => Yii::t('common.models.TariffPlanRules', 'ID тарифного плана'),
            'tpr_type' => Yii::t('common.models.TariffPlanRules', 'Правило'),
            'tpr_period' => Yii::t('common.models.TariffPlanRules', 'Период начисления'),
            'tpr_bonus_value' => Yii::t('common.models.TariffPlanRules', 'Размер бонуса'),
            'tpr_active' => Yii::t('common.models.TariffPlanRules', 'Статус'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(TariffPlan::className(), ['tp_id' => 'tpr_tp_id']);
    }
    
    
//    public function behaviors()
//    {
//        return [
//            'translateable' => [
//                'class' => TranslateableBehavior::className(),
//                'translationAttributes' => ['title'],
//                // translationRelation => 'translations',
//                // translationLanguageAttribute => 'language',
//            ],
//        ];
//    }

//    public function transactions()
//    {
//        return [
//            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
//        ];
//    }

//    public function getTranslations()
//    {
//        return $this->hasMany(TariffPlanRulesTranslation::className(), ['tprt_tpr_id' => 'tpr_id']);
//    }
}
