<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_075203_table_payments_templates extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pay_template}}', [
            'pt_id' => $this->primaryKey(),
            'pt_user_id' => $this->integer()->notNull(),
            'pt_provider_id' => $this->integer()->notNull(),
            'pt_accaunt' => $this->string()->notNull(),
            'pt_currency' => $this->string()->notNull(),
            'pt_summ' => $this->decimal(10, 2)->notNull()
        ]);
        $this->addForeignKey('FK_template_user', '{{%pay_template}}', 'pt_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_template_user', '{{%pay_template}}');
        $this->dropTable('{{%pay_template}}');
    }

}
