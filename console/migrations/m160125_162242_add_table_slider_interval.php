<?php

use yii\db\Schema;
use yii\db\Migration;

class m160125_162242_add_table_slider_interval extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `slider_interval` (
            `interval_id` INT NOT NULL AUTO_INCREMENT,
            `interval_main` INT(11) NOT NULL DEFAULT '10',
            `interval_user` INT(11) NOT NULL DEFAULT '10',
            PRIMARY KEY (`interval_id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;
        ");
        $this->insert('slider_interval', [
            'interval_main' => '10',
            'interval_user' => '10'
        ]);
    }

    public function down()
    {
        $this->dropTable('slider_interval');
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
