<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_072658_table_country extends Migration
{
    public function up()
    {
        $this->createTable('{{%country}}', [
            'country_id' => $this->primaryKey(),
            'country_name' => $this->string()->notNull(),
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%country}}');
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
