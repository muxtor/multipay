<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_081439_table_pay_planned_day_month_bind extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pay_plan_day_month_bind}}', [
            'ppdmb_id' => $this->primaryKey(),
            'ppdmb_user_id' => $this->integer()->notNull(),
            'ppdmb_day_week_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_plan_month_user', '{{%pay_plan_day_month_bind}}', 'ppdmb_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_plan_month_user', '{{%pay_plan_day_month_bind}}');
        $this->dropTable('{{%pay_plan_day_month_bind}}');
    }
}
