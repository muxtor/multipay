<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_094841_add_user_phone extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'phone', $this->string(16)->notNull()->unique());
        $this->addColumn('{{%user}}', 'sms_code', $this->string(32)->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'phone');
        $this->dropColumn('{{%user}}', 'sms_code');
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
