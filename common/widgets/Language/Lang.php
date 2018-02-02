<?php
namespace common\widgets\Language;
use common\models\Language;

class Lang extends \yii\bootstrap\Widget
{
    public function init(){}

    public function run() {
        return $this->render('lang/view', [
            'current' => Language::getCurrent(),
            'langs' => Language::find()->where('lang_id != :current_id', [':current_id' => Language::getCurrent()->lang_id])->all(),
        ]);
    }
}