<?php

use yii\db\Migration;

class m160229_135557_table_payment_in_pay extends Migration
{
    public function up()
    {
        $this->createTable('{{%payment_in_pay}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_payment_in_pay', '{{%payment_in_pay}}', 'payment_id', '{{%payments}}', 'pay_id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('FK_payment_in_pay', '{{%payment_in_pay}}');
        $this->dropTable('{{%payment_in_pay}}');;
    }

}
