<?php

use yii\db\Schema;
use yii\db\Migration;

class m160125_092659_add_admin_role extends Migration
{
    public function up()
    {
        $this->addColumn('{{%admin}}', 'role', $this->string(50)->notNull());

    }

    public function down()
    {
        $this->dropColumn('{{%admin}}', 'role');
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
