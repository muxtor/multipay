<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "partners".
 *
 * @property integer $id
 * @property string $title
 * @property string $site_link
 * @property string $image
 * @property string $text
 * @property integer $status
 * @property integer $sortorder
 * @property string $css
 */
class Partners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_link'], 'required'],
            [['text', 'css'], 'string'],
            [['text', 'title'], 'safe'],
            [['status', 'sortorder'], 'integer'],
            [['title', 'site_link', 'image'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('partners', 'ID'),
            'title' => Yii::t('partners', 'Название'),
            'site_link' => Yii::t('partners', 'Ссылка для сайта'),
            'image' => Yii::t('partners', 'Рисунок'),
            'text' => Yii::t('partners', 'Текст'),
            'status' => Yii::t('partners', 'Актив'),
            'sortorder' => Yii::t('partners', 'Сортировка'),
            'css' => Yii::t('partners', 'Css'),
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
        if(isset($this->image)){
            $file=UploadedFile::getInstance($this,'image');
            if($file){
                $patch = Yii::$app->params['partners.uploads.path'];
                $patch_url = Yii::$app->params['partners_url.uploads.path'];
                if (!file_exists($patch_url)) {
                    mkdir($patch_url, 0777, true);
                }
                $this->image = time() . '_' . md5($file->baseName) . '.' . $file->extension;
                $file->saveAs($patch . '/' . $this->image);
                \Yii::$app->db->createCommand()
                    ->update(self::tableName(), ['image' => $this->image], 'id = "'.$this->id.'"')
                    ->execute(); //manually update image name to db
            }
        }
    }

}
