<?php

use yii\db\Schema;
use yii\db\Migration;

class m160125_132806_add_slider extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `main_slider` (
            `slider_id` INT NOT NULL AUTO_INCREMENT,
            `slider_title` VARCHAR(255) NOT NULL,
            `slider_image_url` VARCHAR(255) NOT NULL,
            `slider_text` TEXT NOT NULL,
            PRIMARY KEY (`slider_id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;  ");
    }

    public function down()
    {
        $this->dropTable('main_slider');
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
