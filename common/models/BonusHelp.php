<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bonus_help".
 *
 * @property integer $bh_id
 * @property string $bh_title
 * @property string $bh_text
 * @property string $bh_language
 */
class BonusHelp extends \yii\db\ActiveRecord
{
    
    const ALIAS_HELP = 'help_block';
    const ALIAS_RECKLAM = 'reclame_block';
    
    public function getAliasLabel()
    {
        $labels = self::getAliasLabels();
        return array_key_exists($this->bh_alias, $labels) ? $labels[$this->bh_alias] : Yii::t('common.models.MoneyBallance', 'Нет данных');
    }
    
    public static function getAliasLabels()
    {
        return [
            self::ALIAS_HELP => Yii::t('common.models.MoneyBallance', 'Блок помощь'),
            self::ALIAS_RECKLAM => Yii::t('common.models.MoneyBallance', 'Рекламный блок')
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bonus_help';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bh_text', 'bh_language', 'bh_alias'], 'required'],
            [['bh_text', 'bh_alias'], 'string'],
            [['bh_title'], 'string', 'max' => 255],
            [['bh_language'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bh_id' => Yii::t('common.models.MoneyBallance', 'Bh ID'),
            'bh_title' => Yii::t('common.models.MoneyBallance', 'Bh Title'),
            'bh_text' => Yii::t('common.models.MoneyBallance', 'Bh Text'),
            'bh_language' => Yii::t('common.models.MoneyBallance', 'Bh Language'),
            'bh_alias' => Yii::t('common.models.MoneyBallance', 'Alias'),
        ];
    }
}
