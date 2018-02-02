<?php

use yii\db\Migration;

class m160301_145113_add_date_to_payments_in_pay extends Migration
{
    public function up()
    {
        $this->addColumn('{{%payment_in_pay}}', 'payment_date', $this->string()->defaultValue(''));
    }

    public function down()
    {
        $this->dropColumn('{{%payment_in_pay}}', 'payment_date');
    }
}
