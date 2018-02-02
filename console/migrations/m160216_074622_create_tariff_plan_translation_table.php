<?php

use yii\db\Migration;
use yii\db\Schema;


class m160216_074622_create_tariff_plan_translation_table extends Migration
{
    public function up()
    {
        $this->createTable('tariff_plan_translation', [
                'tpt_tp_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'language' => Schema::TYPE_STRING . '(16) NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'descr' => Schema::TYPE_TEXT . ' NOT NULL',
        ]);
        $this->addPrimaryKey('', '{{%tariff_plan_translation}}', ['tpt_tp_id', 'language']);
    }

    public function down()
    {
        $this->dropTable('tariff_plan_translation');
    }
}
