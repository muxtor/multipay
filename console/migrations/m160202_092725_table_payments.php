<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_092725_table_payments extends Migration
{
    public function up()
    {
        $this->createTable('{{%payments}}', [
            'pay_id' => $this->primaryKey(),
            'pay_pc_id' => $this->string()->notNull().' COMMENT "ID платежа в системе процессингового центра, формируется центром"',
            'pay_user_id' => $this->integer()->notNull()->defaultValue(0),
            'pay_status' => $this->integer()->notNull(),
            'pay_created' => $this->dateTime(),
            'pay_payed' => $this->dateTime(). ' COMMENT "Дата оплаты"',
            'pay_pc_provider_id' => $this->string()->notNull(),
            'pay_summ' => $this->decimal(10, 2)->notNull(),
            'pay_commission' => $this->decimal(10, 2)->notNull(),
            'pay_currency' => $this->string()->notNull(),
            'pay_rate' => $this->decimal(10, 2)->notNull(). ' COMMENT "Курс конвертации из исходной валюты платежа в валюту счета в ПС"',
            'pay_result' => $this->string()->notNull(),
            'pay_result_desc' => $this->string()->notNull(),
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%payments}}');
    }

}
