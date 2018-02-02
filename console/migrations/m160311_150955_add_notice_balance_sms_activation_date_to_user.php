<?php

use yii\db\Migration;

class m160311_150955_add_notice_balance_sms_activation_date_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'notice_balannce_isPhone_activationDate', $this->dateTime()." COMMENT 'Дата активации смс-подписки о балансе'");
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'notice_balannce_isPhone_activationDate');
    }
}
