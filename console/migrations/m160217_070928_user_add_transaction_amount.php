<?php

use yii\db\Migration;

class m160217_070928_user_add_transaction_amount extends Migration
{
    public function up()
    {
        $this->addColumn('{{%money_ballance}}', 'money_transaction_amount', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%money_ballance}}', 'money_transaction_amount');
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
