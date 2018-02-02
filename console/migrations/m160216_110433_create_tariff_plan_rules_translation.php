<?php

use yii\db\Migration;
use yii\db\Schema;

class m160216_110433_create_tariff_plan_rules_translation extends Migration
{
    public function up()
    {
        $this->createTable('{{%tariff_plan_rules_translation}}', [
                'tprt_tpr_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'language' => Schema::TYPE_STRING . '(16) NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
        ]);
        $this->addPrimaryKey('', '{{%tariff_plan_rules_translation}}', ['tprt_tpr_id', 'language']);
    }

    public function down()
    {
        $this->dropTable('{{%tariff_plan_rules_translation}}');
    }
}
