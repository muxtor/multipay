<?php

use yii\db\Migration;

class m160229_083038_payments_add_smsCode extends Migration
{
    public function up()
    {
        $this->addColumn('{{%payments}}', 'pay_smsCode', $this->string()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%payments}}', 'pay_smsCode');
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
