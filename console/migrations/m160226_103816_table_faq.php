<?php

use yii\db\Migration;

class m160226_103816_table_faq extends Migration
{
    public function up()
    {
        $this->createTable('{{%faq}}', [
            'faq_id' => $this->primaryKey(),
            'faq_title' => $this->string()->notNull(),
            'faq_text' => $this->text()->notNull(),
            'faq_language' => $this->string(10)->notNull(),
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%faq}}');
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
