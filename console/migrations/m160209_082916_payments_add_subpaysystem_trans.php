<?php

use yii\db\Schema;
use yii\db\Migration;

class m160209_082916_payments_add_subpaysystem_trans extends Migration
{
    public function up()
    {
        $this->addColumn('{{%payments}}', 'pay_subpaysystem_transfer', $this->text()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%payments}}', 'pay_subpaysystem_transfer');
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
