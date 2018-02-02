<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_081356_table_pay_planned_day_month extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pay_plan_day_month}}', [
            'ppdm_id' => $this->primaryKey(),
            'ppdm_date' => $this->integer()->notNull(),
        ]);

        foreach (range(1, 31) as $num) {
            $this->insert('{{%pay_plan_day_month}}', [
                'ppdm_date' => $num,
            ]);
        }

    }

    public function safeDown()
    {
        $this->truncateTable('{{%pay_plan_day_month}}');
        $this->dropTable('{{%pay_plan_day_month}}');
    }
}
