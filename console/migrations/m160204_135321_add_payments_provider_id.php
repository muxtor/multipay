<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_135321_add_payments_provider_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%payments}}', 'pay_provider_id', $this->integer()->notNull());
        $this->addColumn('{{%payments}}', 'pay_pc_provider_account', $this->string()->notNull());

    }

    public function down()
    {
        $this->dropColumn('{{%payments}}', 'pay_pc_provider_account');
        $this->dropColumn('{{%payments}}', 'pay_provider_id');
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
