<?php

use yii\db\Migration;

class m160219_131216_user_drop_index extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user`
            DROP INDEX `username`,
            DROP INDEX `email`;");
    }

    public function down()
    {
        echo "m160219_131216_user_drop_index cannot be reverted.\n";

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
