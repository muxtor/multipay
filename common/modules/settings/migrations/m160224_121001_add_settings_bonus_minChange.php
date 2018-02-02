<?php

use yii\db\Migration;
use common\modules\settings\models\Setting;

class m160224_121001_add_settings_bonus_minChange extends Migration
{
    public function up()
    {
        $set1 = new Setting();
        $set1->type = Setting::TYPE_FLOAT;
        $set1->section = 'currency';
        $set1->key = 'min_bonus_transfer';
        $set1->value = '1000';
        $set1->active = 1;
        $set1->save();
    }

    public function down()
    {
        Setting::deleteAll(['section' => 'currency', 'key' => 'min_bonus_transfer']);
    }

}
