<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%static_page}}".
 *
 * @property integer $page_id
 * @property string $page_alias
 * @property string $page_text
 * @property string $page_language
 * @property string $page_title
 * @property string $page_keywords
 * @property string $page_description
 * @property integer $page_show
 */
class StaticPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%static_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_alias', 'page_text', 'page_language', 'page_show'], 'required'],
            [['page_text', 'page_description'], 'string'],
            [['page_show'], 'integer'],
            [['page_alias'], 'string', 'max' => 50],
            [['page_language'], 'string', 'max' => 10],
            [['page_title', 'page_keywords'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('app', 'Page ID'),
            'page_alias' => Yii::t('app', 'Page Alias'),
            'page_text' => Yii::t('app', 'Page Text'),
            'page_language' => Yii::t('app', 'Page Language'),
            'page_title' => Yii::t('app', 'Page Title'),
            'page_keywords' => Yii::t('app', 'Page Keywords'),
            'page_description' => Yii::t('app', 'Page Description'),
            'page_show' => Yii::t('app', 'Page Show'),
        ];
    }
}
