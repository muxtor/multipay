<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_150327_table_user_wallet extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('{{%user_wallets}}', [
            'wallet_id' => $this->primaryKey(),
            'wallet_user_id' => $this->integer()->notNull(),
            'wallet_number' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('FK_wallets_user', '{{%user_wallets}}', 'wallet_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_wallets_user', '{{%user_wallets}}');
        $this->dropTable('{{%user_wallets}}');
    }
}
