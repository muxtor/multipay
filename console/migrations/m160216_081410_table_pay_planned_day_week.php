<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_081410_table_pay_planned_day_week extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pay_plan_day_week}}', [
            'ppdw_id' => $this->primaryKey(),
            'ppdw_date' => $this->integer()->notNull(),
        ]);

        foreach (range(1, 7) as $num) {
            $this->insert('{{%pay_plan_day_week}}', [
                'ppdw_date' => $num,
            ]);
        }

    }

    public function safeDown()
    {
        $this->truncateTable('{{%pay_plan_day_week}}');
        $this->dropTable('{{%pay_plan_day_week}}');
    }
}
