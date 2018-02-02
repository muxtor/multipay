<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_141059_add_payments_parameters extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%payments}}', 'pay_is_checked', $this->smallInteger()->defaultValue(0).' COMMENT "Проводилась ли проверка платежа"');
        $this->addColumn('{{%payments}}', 'pay_is_payed', $this->smallInteger()->defaultValue(0).' COMMENT "Проводилась ли оплата платежа"');
        $this->addColumn('{{%payments}}', 'pay_check_result', $this->string()->notNull());
        $this->addColumn('{{%payments}}', 'pay_check_result_desc', $this->string()->notNull());
        $this->addColumn('{{%payments}}', 'pay_check_status', $this->integer()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%payments}}', 'pay_check_status');
        $this->dropColumn('{{%payments}}', 'pay_check_result_desc');
        $this->dropColumn('{{%payments}}', 'pay_check_result');
        $this->dropColumn('{{%payments}}', 'pay_is_payed');
        $this->dropColumn('{{%payments}}', 'pay_is_checked');
    }
}
