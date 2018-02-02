<?php

use yii\db\Schema;
use yii\db\Migration;

class m160218_081129_add_pay_planned_params extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%pay_planned}}', 'pp_is_auto', $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn('{{%pay_planned}}', 'pp_is_notif', $this->smallInteger()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%pay_planned}}', 'pp_is_notif');
        $this->dropColumn('{{%pay_planned}}', 'pp_is_auto');
    }
}
