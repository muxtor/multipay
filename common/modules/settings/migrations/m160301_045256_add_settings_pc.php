
<?php

use common\modules\settings\models\Setting;

class m160301_045256_add_settings_pc extends \yii\db\Migration
{
  public function up()
  {
      $set1 = new Setting();
      $set1->type = Setting::TYPE_STRING;
      $set1->section = 'currency';
      $set1->key = 'dealer_pc';
      $set1->value = '2915';
      $set1->active = 1;
      $set1->save();

      $set2 = new Setting();
      $set2->type = Setting::TYPE_STRING;
      $set2->section = 'currency';
      $set2->key = 'login_pc';
      $set2->value = 'testmultipay';
      $set2->active = 1;
      $set2->save();

      $set3 = new Setting();
      $set3->type = Setting::TYPE_STRING;
      $set3->section = 'currency';
      $set3->key = 'password_pc';
      $set3->value = 'AsW258951testmp5';
      $set3->active = 1;
      $set3->save();

      $set4 = new Setting();
      $set4->type = Setting::TYPE_STRING;
      $set4->section = 'currency';
      $set4->key = 'AZN_terminal';
      $set4->value = '6197';
      $set4->active = 1;
      $set4->save();

      $set5 = new Setting();
      $set5->type = Setting::TYPE_STRING;
      $set5->section = 'currency';
      $set5->key = 'AZN_currency_ISO';
      $set5->value = '944';
      $set5->active = 1;
      $set5->save();

      $set6 = new Setting();
      $set6->type = Setting::TYPE_STRING;
      $set6->section = 'currency';
      $set6->key = 'RUB_terminal';
      $set6->value = '14885';
      $set6->active = 1;
      $set6->save();

      $set7 = new Setting();
      $set7->type = Setting::TYPE_STRING;
      $set7->section = 'currency';
      $set7->key = 'RUB_currency_ISO';
      $set7->value = '643';
      $set7->active = 1;
      $set7->save();

      $set8 = new Setting();
      $set8->type = Setting::TYPE_STRING;
      $set8->section = 'currency';
      $set8->key = 'USD_terminal';
      $set8->value = '14884';
      $set8->active = 1;
      $set8->save();

      $set9 = new Setting();
      $set9->type = Setting::TYPE_STRING;
      $set9->section = 'currency';
      $set9->key = 'USD_currency_ISO';
      $set9->value = '840';
      $set9->active = 1;
      $set9->save();

  }

  public function down()
  {
      Setting::deleteAll(['section' => 'currency', 'key' => 'USD_currency_ISO']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'USD_terminal']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'RUB_currency_ISO']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'RUB_terminal']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'AZN_currency_ISO']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'AZN_terminal']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'password_pc']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'login_pc']);
      Setting::deleteAll(['section' => 'currency', 'key' => 'dealer_pc']);
  }
}