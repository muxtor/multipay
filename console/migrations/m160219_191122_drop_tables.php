<?php

use yii\db\Migration;

class m160219_191122_drop_tables extends Migration
{
    public function safeUp()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->truncateTable('{{%pay_planned}}');
        $this->execute("SET foreign_key_checks = 1;");
        $this->dropTable('{{%pay_plan_day_month}}');
        $this->dropTable('{{%pay_plan_day_week}}');
    }

    public function safeDown()
    {
        $this->createTable('{{%pay_plan_day_week}}', [
            'id' => $this->primaryKey()
        ]);
        $this->createTable('{{%pay_plan_day_month}}', [
            'id' => $this->primaryKey()
        ]);
    }
}
