<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_072513_table_pay_planned extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pay_planned}}', [
            'pp_id' => $this->primaryKey(),
            'pp_user_id' => $this->integer()->notNull(),
            'pp_provider_id' => $this->integer()->notNull(),
            'pp_account' => $this->string()->notNull(),
            'pp_name' => $this->string()->notNull().' COMMENT "Название платежа, как примечание"',
            'pp_system' => $this->string()->notNull().' COMMENT "Способ оплаты"',
            'pp_pay_date' => $this->dateTime()->notNull(),
            'pp_notify_date' => $this->dateTime()->notNull(),
            'pp_type' => $this->smallInteger()->notNull(),
        ]);

        $this->addForeignKey('FK_planned_user', '{{%pay_planned}}', 'pp_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_planned_provider', '{{%pay_planned}}', 'pp_provider_id', '{{%providers}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_planned_provider', '{{%pay_planned}}');
        $this->dropForeignKey('FK_planned_user', '{{%pay_planned}}');
        $this->dropTable('{{%pay_planned}}');
    }

}
