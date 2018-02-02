<?php

use yii\db\Migration;

class m160219_185519_table_pay_planned_week_and_month extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pay_planned_week}}', [
            'ppw_pay_plan_id' => $this->integer()->notNull(),
            'ppw_day' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_plan_week_pay', '{{%pay_planned_week}}', 'ppw_pay_plan_id', '{{%pay_planned}}', 'pp_id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%pay_planned_month}}', [
            'ppm_pay_plan_id' => $this->integer()->notNull(),
            'ppm_day' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_plan_month_pay', '{{%pay_planned_month}}', 'ppm_pay_plan_id', '{{%pay_planned}}', 'pp_id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('FK_plan_week_user', '{{%pay_plan_day_week_bind}}');
        $this->truncateTable('{{%pay_plan_day_week_bind}}');
        $this->dropTable('{{%pay_plan_day_week_bind}}');

        $this->dropForeignKey('FK_plan_month_user', '{{%pay_plan_day_month_bind}}');
        $this->truncateTable('{{%pay_plan_day_month_bind}}');
        $this->dropTable('{{%pay_plan_day_month_bind}}');

    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_plan_month_pay', '{{%pay_planned_month}}');
        $this->dropTable('{{%pay_planned_month}}');
        $this->dropForeignKey('FK_plan_week_pay', '{{%pay_planned_week}}');
        $this->dropTable('{{%pay_planned_week}}');
    }
}
