<?php

use yii\db\Migration;

class m160224_140329_tarif_plan_rules_add_type extends Migration
{
    public function up()
    {
        $this->truncateTable('{{%tariff_plan_rules}}');
        $this->truncateTable('{{%tariff_plan_rules_translation}}');
        $this->addColumn('{{%tariff_plan_rules}}', 'tpr_type', $this->smallInteger()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%tariff_plan_rules}}', 'tpr_type');
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
