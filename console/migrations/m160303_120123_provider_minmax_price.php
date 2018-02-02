<?php

use yii\db\Migration;

class m160303_120123_provider_minmax_price extends Migration
{
    public function up()
    {
        $this->addColumn('providers', 'pay_sum_min', $this->decimal(10, 2)->notNull()->defaultValue(0));
        $this->addColumn('providers', 'pay_sum_max', $this->decimal(10, 2)->notNull()->defaultValue(0));

    }

    public function down()
    {
        $this->dropColumn('providers', 'pay_sum_min');
        $this->dropColumn('providers', 'pay_sum_max');
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
