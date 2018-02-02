<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_154429_table_bonus_tariff_plan extends Migration
{
    public function up()
    {
        $this->createTable('{{%tariff_plan}}', [
            'tp_id' => Schema::TYPE_PK,
            'tp_transfer_min' => $this->integer()->notNull(),
            'tp_transfer_max' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tariff_plan}}');
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
