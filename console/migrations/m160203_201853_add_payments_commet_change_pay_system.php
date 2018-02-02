<?php

use yii\db\Schema;
use yii\db\Migration;

class m160203_201853_add_payments_commet_change_pay_system extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%payments}}', 'pay_system');
        $this->addColumn('{{%payments}}', 'pay_system', $this->string(255)->notNull().' COMMENT "Тип платежной системы"');
        $this->addColumn('{{%payments}}', 'pay_comment', $this->string(255)->notNull().' COMMENT "Примечание к платежу"');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%payments}}', 'pay_comment');
        $this->dropColumn('{{%payments}}', 'pay_system');
        $this->addColumn('{{%payments}}', 'pay_system', $this->smallInteger()->notNull().' COMMENT "Тип платежной системы"');
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
