<?php
namespace common\modules\settings\models;

use common\modules\settings\Module;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $type
 * @property string $section
 * @property string $key
 * @property string $value
 * @property boolean $active
 * @property string $created
 * @property string $modified
 *
 */
class Setting extends ActiveRecord implements SettingInterface
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['section', 'key'], 'string', 'max' => 255],
            [
                ['key'],
                'unique',
                'targetAttribute' => ['section', 'key'],
                'message' =>
                    Yii::t('settings', '{attribute} "{value}" already exists for this section.')
            ],
            [['created', 'modified', 'type'], 'safe'],
            [['active'], 'boolean'],
            ['type', 'in', 'range' => $this->getTypes()],
        ];
    }


    /**
    * @param bool $forDropDown if false - return array or validators, true - key=>value for dropDown
    * @return array
    */
    public static function getTypes($forDropDown = true)
    {
        $values = [
            'string' => ['value', 'string'],
            'integer' => ['value', 'integer'],
            'float' => ['value', 'number'],
        ];

        if (!$forDropDown) {
            return $values;
        }

        $return = [];
        foreach ($values as $key => $value) {
                $return[$key] = Yii::t('settings', $key);
            }

        return $return;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->settings->clearCache();
    }
    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->settings->clearCache();
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('settings', 'ID'),
            'type' => Yii::t('settings', 'Type'),
            'section' => Yii::t('settings', 'Section'),
            'key' => Yii::t('settings', 'Key'),
            'value' => Yii::t('settings', 'Value'),
            'active' => Yii::t('settings', 'Active'),
            'created' => Yii::t('settings', 'Created'),
            'modified' => Yii::t('settings', 'Modified'),
        ];
    }
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function getSettings()
    {
        $settings = static::find()->where(['active' => true])->asArray()->all();
        return array_merge_recursive(
            ArrayHelper::map($settings, 'key', 'value', 'section'),
            ArrayHelper::map($settings, 'key', 'type', 'section')
        );
    }
    /**
     * @inheritdoc
     */
    public function setSetting($section, $key, $value, $type = null)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);
        if ($model === null) {
            $model = new static();
            $model->active = 1;
        }
        $model->section = $section;
        $model->key = $key;
        $model->value = strval($value);
        if ($type !== null) {
            $model->type = $type;
        } else {
            $model->type = gettype($value);
        }
        return $model->save();
    }
    /**
     * @inheritdoc
     */
    public function activateSetting($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);
        if ($model && $model->active == 0) {
            $model->active = 1;
            return $model->save();
        }
        return false;
    }
    /**
     * @inheritdoc
     */
    public function deactivateSetting($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);
        if ($model && $model->active == 1) {
            $model->active = 0;
            return $model->save();
        }
        return false;
    }
    /**
     * @inheritdoc
     */
    public function deleteSetting($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);
        if ($model) {
            return $model->delete();
        }
        return true;
    }
    /**
     * @inheritdoc
     */
    public function deleteAllSettings()
    {
        return static::deleteAll();
    }
}