<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "terminal".
 *
 * @property integer $id
 * @property string $name
 * @property string $number
 */
class Terminal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'number'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['number'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('terminal/models', 'ID'),
            'name' => Yii::t('terminal/models', 'Название'),
            'number' => Yii::t('terminal/models', 'Номер терминала'),
        ];
    }

    /** @return array
    */
    public static function getList()
    {
        return Terminal::find()
            ->select(['name', 'number'])
            ->orderBy(['name' => SORT_ASC])
            ->indexBy('number')
            ->column();
    }
}
