<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_082719_user_add_name_country extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'firstName', $this->string(32)->defaultValue(null));
        $this->addColumn('{{%user}}', 'lastName', $this->string(32)->defaultValue(null));
        $this->addColumn('{{%user}}', 'country_id', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%user}}', 'date_bird', $this->date()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'date_bird');
        $this->dropColumn('{{%user}}', 'country_id');
        $this->dropColumn('{{%user}}', 'lastName');
        $this->dropColumn('{{%user}}', 'firstName');
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
