<?php

use yii\db\Migration;

class m160307_062101_user_add_setting extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'user_language', $this->string(10)->notNull()->defaultValue('ru'));
        $this->addColumn('{{%user}}', 'user_IP', $this->string(50));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'user_language');
        $this->dropColumn('{{%user}}', 'user_IP');
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
