<?php namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%language}}".
 *
 * @property integer $lang_id
 * @property string $lang_url
 * @property string $lang_local
 * @property string $lang_name
 * @property integer $lang_default
 * @property string $lang_date_update
 * @property string $lang_date_create
 */
class Language extends \yii\db\ActiveRecord
{

    static $current = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_url', 'lang_local', 'lang_name', 'lang_date_update', 'lang_date_create'], 'required'],
            [['lang_default'], 'integer'],
            [['lang_date_update', 'lang_date_create'], 'safe'],
            [['lang_url', 'lang_local', 'lang_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lang_id' => Yii::t('app', 'Lang ID'),
            'lang_url' => Yii::t('app', 'Lang Url'),
            'lang_local' => Yii::t('app', 'Lang Local'),
            'lang_name' => Yii::t('app', 'Lang Name'),
            'lang_default' => Yii::t('app', 'Lang Default'),
            'lang_date_update' => Yii::t('app', 'Lang Date Update'),
            'lang_date_create' => Yii::t('app', 'Lang Date Create'),
        ];
    }

//Получение текущего объекта языка
    static function getCurrent()
    {
        if (self::$current === null) {
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

//Установка текущего объекта языка и локаль пользователя
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
//		\yii\helpers\VarDumper::dump(self::$current,10,10);exit;
        Yii::$app->language = self::$current->lang_local;
    }

//Получения объекта языка по умолчанию
    static function getDefaultLang()
    {
        return Language::find()->where('`lang_default` = :default', [':default' => 1])->one();
    }

//Получения объекта языка по буквенному идентификатору
    static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $language = Language::find()->where('lang_url = :url', [':url' => $url])->one();
            if ($language === null) {
                return null;
            } else {
                return $language;
            }
        }
    }
}
