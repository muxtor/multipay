<?php

use yii\db\Migration;

class m160219_194611_add_plan_pay_notif_days extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pay_planned}}', 'pay_notif_day_amount', $this->integer()->notNull()->defaultValue(0));

    }

    public function down()
    {
        $this->dropColumn('{{%pay_planned}}', 'pay_notif_day_amount');
    }


}
