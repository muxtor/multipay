<?php

use yii\db\Schema;
use yii\db\Migration;

class m160125_152519_add_table_static_page extends Migration
{
    public function up()
    {
        $this->createTable('{{%static_page}}', [
            'page_id' => $this->primaryKey(),
            'page_alias' => $this->string(50)->notNull(),
            'page_text' => $this->text()->notNull(),
            'page_language' => $this->string(10)->notNull(),
            'page_title' => $this->string(255),
            'page_keywords' => $this->string(255),
            'page_description' => $this->text(),
            'page_show' => $this->smallInteger(2)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%static_page}}');
    }
}
