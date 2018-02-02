<?php

namespace frontend\widgets\Tabs;

use Yii;

class TabsWidget extends \yii\bootstrap\Widget
{
    public function run()
    {
        return $this->render('tabs_list');

    }
}