<?php

use yii\db\Migration;

class m160406_051008_add_table_terminal extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `terminal` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `number` VARCHAR(50) NOT NULL,
            PRIMARY KEY (`id`)
        )
        ENGINE=MyISAM
        ;");
        $this->batchInsert('{{%terminal}}', ['name', 'number'], [
            ['Qiwi', '49635',],
            ['ExpressPay', '4695',],
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%terminal}}');
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
