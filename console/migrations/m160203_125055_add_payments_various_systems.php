<?php

use yii\db\Schema;
use yii\db\Migration;

class m160203_125055_add_payments_various_systems extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('{{%payments}}', 'pay_system', $this->smallInteger()->notNull().' COMMENT "Тип платежной системы"');
        $this->addColumn('{{%payments}}', 'pay_type', $this->smallInteger()->notNull().' COMMENT "Тип платежа (пополнение баланса или что-то другое)"');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%payments}}', 'pay_type');
        $this->dropColumn('{{%payments}}', 'pay_system');
    }
}
