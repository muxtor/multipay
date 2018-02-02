<?php

namespace frontend\widgets\ProvidersList;

use Yii;
use common\models\Providers;

class ProvidersCategoriesHomeWidget extends \yii\bootstrap\Widget
{
    public function run()
    {
        $roots = Providers::find()->roots()->andWhere('show_on_start = 1')->all();
        $counries = \common\models\Country::find()->where(['<>', 'tel_mask', ''])->all();
        $map = \yii\helpers\ArrayHelper::map($counries, 'id', 'name');
//        \yii\helpers\VarDumper::dump($map, 10, 1);
        return $this->render('providers_categories_home', ['roots' => $roots, 'map' => $map, 'countries'=>$counries]);
        
    }
}