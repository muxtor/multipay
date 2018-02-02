
<?php

use common\modules\settings\models\Setting;

class m140618_045256_add_settings extends \yii\db\Migration
{
  public function up()
  {
      $set1 = new Setting();
      $set1->type = Setting::TYPE_FLOAT;
      $set1->section = 'currency';
      $set1->key = 'AZN_to_balance';
      $set1->value = '10.00';
      $set1->active = 1;
      $set1->save();

      $set2 = new Setting();
      $set2->type = Setting::TYPE_FLOAT;
      $set2->section = 'currency';
      $set2->key = 'RUB_to_balance';
      $set2->value = '20.00';
      $set2->active = 1;
      $set2->save();

      $set3 = new Setting();
      $set3->type = Setting::TYPE_FLOAT;
      $set3->section = 'currency';
      $set3->key = 'USD_to_balance';
      $set3->value = '30.00';
      $set3->active = 1;
      $set3->save();

  }

  public function down()
  {
      Setting::deleteAll(['section' => 'currency', 'key' => 'USD_to_balance']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'RUB_to_balance']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'AZN_to_balance']);
  }
}