<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tariff_plan_translation".
 *
 * @property integer $tpt_tp_id
 * @property string $language
 * @property string $title
 * @property string $descr
 */
class TariffPlanTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tariff_plan_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['tpt_tp_id', 'language', 'title', 'descr'], 'required'],
            [['tpt_tp_id'], 'integer'],
            [['descr'], 'string'],
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
            'tpt_tp_id' => Yii::t('common.models.TariffPlanTranslation', 'Tpt Tp ID'),
            'language' => Yii::t('common.models.TariffPlanTranslation', 'Language'),
            'title' => Yii::t('common.models.TariffPlanTranslation', 'Title'),
            'descr' => Yii::t('common.models.TariffPlanTranslation', 'Descr'),
        ];
    }
}
