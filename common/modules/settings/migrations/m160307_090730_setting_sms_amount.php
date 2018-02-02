<?php

use yii\db\Migration;
use common\modules\settings\models\Setting;


class m160307_090730_setting_sms_amount extends Migration
{
  public function up()
  {
        $set1 = new Setting();
        $set1->type = Setting::TYPE_FLOAT;
        $set1->section = 'currency';
        $set1->key = 'sms_pay_planned';
        $set1->value = '0.01';
        $set1->active = 1;
        $set1->save();

        $set2 = new Setting();
        $set2->type = Setting::TYPE_FLOAT;
        $set2->section = 'currency';
        $set2->key = 'sms_change_balance';
        $set2->value = '1.00';
        $set2->active = 1;
        $set2->save();
  }

  public function down()
  {
      Setting::deleteAll(['section' => 'currency', 'key' => 'sms_pay_planned']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'sms_change_balance']);
  }
}
