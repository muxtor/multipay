<?php

use yii\db\Migration;

class m160302_140856_table_invoice extends Migration
{
    public function up()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer()->notNull(),
            'from_user_id' => $this->integer()->notNull(),
            'to_user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'comment' => $this->string()->notNull()->defaultValue(''),
        ]);

        $this->addForeignKey('FK_payment_invoice', '{{%invoice}}', 'payment_id', '{{%payments}}', 'pay_id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_from_user_invoice', '{{%invoice}}', 'from_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_to_user_invoice', '{{%invoice}}', 'to_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('FK_to_user_invoice', '{{%invoice}}');
        $this->dropForeignKey('FK_from_user_invoice', '{{%invoice}}');
        $this->dropForeignKey('FK_payment_invoice', '{{%invoice}}');
        $this->dropTable('{{%invoice}}');
    }

}
