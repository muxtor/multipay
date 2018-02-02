<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $news_id
 * @property string $news_alias
 * @property string $news_text
 * @property string $news_language
 * @property string $news_title
 * @property string $news_keywords
 * @property string $news_description
 * @property integer $news_show
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_alias', 'news_text', 'news_language', 'news_show', 'news_date'], 'required'],
            [['news_text', 'news_description'], 'string'],
            [['news_alias'], 'unique'],
            [['news_show'], 'integer'],
            [['news_alias'], 'string', 'max' => 50],
            [['news_language'], 'string', 'max' => 10],
            [['news_title', 'news_keywords'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'news_id' => Yii::t('news', 'News ID'),
            'news_alias' => Yii::t('news', 'News Alias'),
            'news_text' => Yii::t('news', 'News Text'),
            'news_date' => Yii::t('news', 'News Date'),
            'news_language' => Yii::t('news', 'News Language'),
            'news_title' => Yii::t('news', 'News Title'),
            'news_keywords' => Yii::t('news', 'News Keywords'),
            'news_description' => Yii::t('news', 'News Description'),
            'news_show' => Yii::t('news', 'News Show'),
        ];
    }
}
