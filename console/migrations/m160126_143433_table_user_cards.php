<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_143433_table_user_cards extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('{{%user_cards}}', [
            'card_id' => $this->primaryKey(),
            'card_user_id' => $this->integer()->notNull(),
            'card_number' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('FK_cards_user', '{{%user_cards}}', 'card_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_cards_user', '{{%user_cards}}');
        $this->dropTable('{{%user_cards}}');
    }
}
