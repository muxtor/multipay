<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_123506_change_user extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user`
	CHANGE COLUMN `username` `username` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci' AFTER `id`,
	CHANGE COLUMN `email` `email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci' AFTER `password_reset_token`;");
    }

    public function down()
    {
        echo "m160127_123506_change_user cannot be reverted.\n";

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
