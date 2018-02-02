<?php

use yii\db\Schema;
use yii\db\Migration;

class m160203_090836_money_balance extends Migration
{
    public function up()
    {
        $this->createTable('{{%money_ballance}}', array(
            'money_id' => 'pk',
            'money_user_id' => $this->integer(11)->notNull()->unique(),
            'money_amount' => 'DECIMAL(10,2) NOT NULL DEFAULT "0"',
            'money_bonus_ballance' => $this->integer(11)->notNull()->defaultValue(0),
        ));
        $this->addForeignKey('FK_money_ballance_user', '{{%money_ballance}}', 'money_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('FK_money_ballance_user', '{{%money_ballance}}');
        $this->dropTable('{{%money_ballance}}');
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
