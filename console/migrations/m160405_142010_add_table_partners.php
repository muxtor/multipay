<?php

use yii\db\Migration;

class m160405_142010_add_table_partners extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `partners` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NULL DEFAULT NULL,
            `site_link` VARCHAR(255) NULL DEFAULT NULL,
            `image` VARCHAR(255) NULL DEFAULT NULL,
            `text` TEXT NULL,
            `status` TINYINT(2) NULL DEFAULT '1',
            `sortorder` INT(11) NULL DEFAULT '0',
            `css` TEXT NULL,
            PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        ;");
    }

    public function down()
    {
        $this->dropTable('{{%partners}}');
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
