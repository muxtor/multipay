<?php

namespace frontend\widgets\Logos;

use Yii;

class LogosWidget extends \yii\bootstrap\Widget
{
    public function run()
    {
        return $this->render('logos_list');

    }
}