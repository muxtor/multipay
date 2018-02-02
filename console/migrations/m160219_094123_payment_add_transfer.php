<?php

use yii\db\Migration;

class m160219_094123_payment_add_transfer extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%payments}}', 'pay_isProtected', $this->smallInteger()->notNull().' COMMENT "Защита транзакции (только для внутренних переводов)"');
        $this->addColumn('{{%payments}}', 'pay_protected_code', $this->string(255)->defaultValue(null).' COMMENT "Код протекции"');
        $this->addColumn('{{%payments}}', 'pay_summ_from', $this->decimal(10, 2)->notNull().' COMMENT "Сумма к списанию"');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%payments}}', 'pay_isProtected');
        $this->dropColumn('{{%payments}}', 'pay_protected_code');
        $this->dropColumn('{{%payments}}', 'pay_summ_from');
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
