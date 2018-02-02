<?php

use yii\db\Migration;

class m160216_110234_create_tariff_plan_rules extends Migration
{
    public function up()
    {
        $this->createTable('{{%tariff_plan_rules}}', [
            'tpr_id' => $this->primaryKey(),
            'tpr_tp_id' => $this->integer()->notNull(),
            'tpr_period' => $this->integer()->notNull()->defaultValue(0),
            'tpr_bonus_value' => $this->integer()->notNull()->defaultValue(0),
        ]);
        
        $this->addForeignKey('FK_tariff_plan_rules_tariff_plan', '{{%tariff_plan_rules}}', 'tpr_tp_id', '{{%tariff_plan}}', 'tp_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('FK_tariff_plan_rules_tariff_plan', '{{%tariff_plan_rules}}');
        $this->dropTable('{{%tariff_plan_rules}}');
    }
}
