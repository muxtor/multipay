<?php

use yii\db\Migration;

class m160229_153939_tarifplans_rules_add_active extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariff_plan_rules}}', 'tpr_active', $this->smallInteger()->notNull()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('{{%tariff_plan_rules}}', 'tpr_active');
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
