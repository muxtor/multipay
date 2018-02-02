<?php

use yii\db\Migration;
use common\modules\settings\models\Setting;


class m160311_090730_setting_sms_notif extends Migration
{
  public function up()
  {
        $set1 = new Setting();
        $set1->type = Setting::TYPE_INTEGER;
        $set1->section = 'currency';
        $set1->key = 'sms_notif_balance_free_period';
        $set1->value = '30';
        $set1->active = 1;
        $set1->save();
  }

  public function down()
  {
      Setting::deleteAll(['section' => 'currency', 'key' => 'sms_notif_balance_free_period']);
  }
}
