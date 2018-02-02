<?php

use yii\db\Migration;

class m160307_134721_user_change_notice_safety_isPhone extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%user}}', 'notice_safety_isPhone');
        $this->addColumn('{{%user}}', 'notice_safety_isPhone', $this->smallInteger()->notNull()->defaultValue(1) . ' AFTER `notice_safety_isEmail`');
        
    }

    public function down()
    {
        $this->execute("ALTER TABLE `user`
            CHANGE COLUMN `notice_safety_isPhone` `notice_safety_isPhone` SMALLINT(6) NOT NULL DEFAULT '1' AFTER `notice_safety_isEmail`;");
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
