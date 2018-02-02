<?php

use yii\db\Migration;

class m160224_154631_user_table_bonus_history extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%bonus_history}}', [
            'bh_id' => $this->primaryKey(),
            'bh_user_id' => $this->integer()->notNull(),
            'bh_type' => $this->integer()->notNull(),
            'bh_period' => $this->integer()->notNull(),
            'bh_bonus' => $this->integer()->notNull(),
            'bh_create' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('FK_bonus_history_user', '{{%bonus_history}}', 'bh_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_bonus_history_user', '{{%bonus_history}}');
        $this->dropForeignKey('FK_planned_user', '{{%pay_planned}}');
        $this->dropTable('{{%bonus_history}}');
    }
}
