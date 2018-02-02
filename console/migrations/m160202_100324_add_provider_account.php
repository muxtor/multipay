<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_100324_add_provider_account extends Migration
{
    public function up()
    {
        $this->addColumn('{{%providers}}', 'account', $this->string()->notNull(). ' COMMENT "Номер счета провайдера"');

    }

    public function down()
    {
        $this->dropColumn('{{%providers}}', 'account');
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
