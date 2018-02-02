<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "main_slider".
 *
 * @property integer $slider_id
 * @property string $slider_title
 * @property string $slider_image_url
 */
class MainSlider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'main_slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slider_title'], 'required'],
            [['slider_title', 'slider_image_url', 'slider_text'], 'string', 'max' => 255],
            [['slider_image_url'], 'file', 'extensions' => 'png, jpg, jpeg, gif',],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'slider_id' => Yii::t('MainSlider', 'Slider ID'),
            'slider_title' => Yii::t('MainSlider', 'Название слайда'),
            'slider_text' => Yii::t('MainSlider', 'Slider Text'),
            'slider_image_url' => Yii::t('MainSlider', 'Изображение для слайда'),
        ];
    }
}
