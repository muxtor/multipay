<?php

use yii\db\Migration;

class m160226_141523_provider_add_pay_count extends Migration
{
    public function up()
    {
        $this->addColumn('{{%providers}}', 'pay_count', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%providers}}', 'pay_count');
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
