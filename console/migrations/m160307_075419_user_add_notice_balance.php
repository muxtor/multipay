<?php

use yii\db\Migration;

class m160307_075419_user_add_notice_balance extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'notice_balannce_isEmail', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_balannce_isPhone', $this->smallInteger()->defaultValue(null));
        $this->execute(" ALTER TABLE `user`
            CHANGE COLUMN `notice_safety_isPhone` `notice_safety_isPhone` SMALLINT(6) NULL DEFAULT '1' AFTER `notice_safety_isEmail`;");
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'notice_balannce_isEmail');
        $this->dropColumn('{{%user}}', 'notice_balannce_isPhone');
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
