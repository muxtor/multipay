<?php

use yii\db\Migration;

class m160229_135548_table_payment_in_check extends Migration
{
    public function up()
    {
        $this->createTable('{{%payment_in_check}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_payment_in_check', '{{%payment_in_check}}', 'payment_id', '{{%payments}}', 'pay_id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('FK_payment_in_check', '{{%payment_in_check}}');
        $this->dropTable('{{%payment_in_check}}');;
    }

}
