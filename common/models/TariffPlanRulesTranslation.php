<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tariff_plan_rules_translation".
 *
 * @property integer $tprt_tpr_id
 * @property string $language
 * @property string $title
 */
class TariffPlanRulesTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tariff_plan_rules_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['tprt_tpr_id', 'language', 'title'], 'required'],
            [['tprt_tpr_id'], 'integer'],
            [['language'], 'string', 'max' => 16],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tprt_tpr_id' => Yii::t('common.models.TariffPlanRulesTranslation', 'Tprt Tpr ID'),
            'language' => Yii::t('common.models.TariffPlanRulesTranslation', 'Language'),
            'title' => Yii::t('common.models.TariffPlanRulesTranslation', 'Title'),
        ];
    }
}
