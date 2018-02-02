<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property integer $faq_id
 * @property string $faq_title
 * @property string $faq_text
 * @property integer $faq_show_on_main
 */
class Faq extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%faq}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faq_title', 'faq_text'], 'required'],
            [['faq_text'], 'string'],
            [['faq_title'], 'string', 'max' => 255],
            [['faq_language'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'faq_id' => Yii::t('faq/model', 'ID'),
            'faq_title' => Yii::t('faq/model', 'Заголовок'),
            'faq_text' => Yii::t('faq/model', 'Текст'),
            'faq_language' => Yii::t('faq/model', 'Язык'),
        ];
    }
}
