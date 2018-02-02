<?php

use yii\db\Migration;

class m160219_124603_payment_change_comments extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `payments`
                ALTER `pay_comment` DROP DEFAULT;
            ALTER TABLE `payments`
                CHANGE COLUMN `pay_comment` `pay_comment` VARCHAR(255) NULL COMMENT 'Примечание к платежу' AFTER `pay_system`,
                CHANGE COLUMN `pay_isProtected` `pay_isProtected` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT 'Защита транзакции (только для внутренних переводов)' AFTER `pay_subpaysystem_transfer`;");
    }

    public function down()
    {
        echo "m160219_124603_payment_change_comments cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
