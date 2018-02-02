<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_081710_add_news extends Migration
{
    public function up()
    {
        $this->createTable('{{%news}}', [
            'news_id' => $this->primaryKey(),
            'news_alias' => $this->string(50)->notNull(),
            'news_title' => $this->string(255),
            'news_date' => $this->date()->defaultValue(Null),
            'news_description' => $this->text(),
            'news_text' => $this->text()->notNull(),
            'news_language' => $this->string(10)->notNull(),
            'news_keywords' => $this->string(255),
            'news_show' => $this->smallInteger(2)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%news}}');
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
