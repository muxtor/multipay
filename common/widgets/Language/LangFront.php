<?php
namespace common\widgets\Language;
use common\models\Language;

class LangFront extends \yii\bootstrap\Widget
{
    public function init(){}

    public function run() {
        return $this->render('lang/view_front', [
            'langs' => Language::find()->all(),
        ]);
    }
}