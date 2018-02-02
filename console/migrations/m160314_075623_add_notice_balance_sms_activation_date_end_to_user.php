<?php

use yii\db\Migration;

class m160314_075623_add_notice_balance_sms_activation_date_end_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'notice_balannce_isPhone_activationDateEnd', $this->dateTime()." COMMENT 'Дата окончания смс-подписки о балансе'");
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'notice_balannce_isPhone_activationDateEnd');
    }
}
