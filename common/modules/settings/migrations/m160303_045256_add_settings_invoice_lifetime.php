
<?php

use common\modules\settings\models\Setting;

class m160303_045256_add_settings_invoice_lifetime extends \yii\db\Migration
{
  public function up()
  {
      $set1 = new Setting();
      $set1->type = Setting::TYPE_INTEGER;
      $set1->section = 'currency';
      $set1->key = 'invoice_lifetime_hours';
      $set1->value = '24';
      $set1->active = 1;
      $set1->save();
  }

  public function down()
  {
      Setting::deleteAll(['section' => 'currency', 'key' => 'invoice_lifetime_hours']);
  }
}