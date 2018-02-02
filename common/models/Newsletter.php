<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "{{%newsletter}}".
 *
 * @property integer $id
 * @property integer $news_id
 * @property integer $type
 * @property integer $send_by
 * @property string $created
 */
class Newsletter extends \yii\db\ActiveRecord
{
    const VIA_SMS = 10;
    const VIA_EMAIL = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'type', 'send_by'], 'required'],
            [['news_id', 'type', 'send_by'], 'integer'],
            [['created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('newsletter/model', 'ID'),
            'news_id' => Yii::t('newsletter/model', 'News ID'),
            'type' => Yii::t('newsletter/model', 'Type'),
            'send_by' => Yii::t('newsletter/model', 'Send By'),
            'created' => Yii::t('newsletter/model', 'Created'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getNews()
    {
        $news = $this->hasOne(News::className(), ['news_id' => 'id']);
        return $news->exists() ? $news : new News();
    }

    public static function getLastDate($news_id)
    {
        $lastDate = (new Query())
            ->select('created, type')
            ->from(static::tableName())
            ->where(['news_id' => $news_id])
            ->orderBy('created DESC')
            ->limit(1)
            ->all();
        return $lastDate
            ? $lastDate[0]['created'] . ' ( ' . ($lastDate[0]['type'] == static::VIA_EMAIL ? 'Email' : ($lastDate[0]['type'] == static::VIA_SMS ? 'SMS' : '-')) . ' )'
            : '-';

    }
}
