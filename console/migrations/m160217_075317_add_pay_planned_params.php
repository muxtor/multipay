<?php

use yii\db\Schema;
use yii\db\Migration;

class m160217_075317_add_pay_planned_params extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%pay_planned}}', 'pp_currency', $this->string()->notNull());
        $this->addColumn('{{%pay_planned}}', 'pp_summ', $this->decimal()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%pay_planned}}', 'pp_summ');
        $this->dropColumn('{{%pay_planned}}', 'pp_currency');
    }

}
