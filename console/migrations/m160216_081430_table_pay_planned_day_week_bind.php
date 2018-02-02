<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_081430_table_pay_planned_day_week_bind extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pay_plan_day_week_bind}}', [
            'ppdwb_id' => $this->primaryKey(),
            'ppdwb_user_id' => $this->integer()->notNull(),
            'ppdwb_day_week_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_plan_week_user', '{{%pay_plan_day_week_bind}}', 'ppdwb_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_plan_week_user', '{{%pay_plan_day_week_bind}}');
        $this->dropTable('{{%pay_plan_day_week_bind}}');
    }

}
