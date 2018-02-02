<?php

namespace frontend\widgets\ProvidersList;

use Yii;
use common\models\Providers;

class ProvidersSidebarWidget extends \yii\bootstrap\Widget
{
    public function run()
    {
        $roots = Providers::find()->roots()->andWhere('status = 1')->all();
        return $this->render('providers_sidebar', ['roots' => $roots]);
        
    }
}