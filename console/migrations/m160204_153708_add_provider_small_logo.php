<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_153708_add_provider_small_logo extends Migration
{
    public function up()
    {
        $this->addColumn('{{%providers}}', 'logo_sidebar', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%providers}}', 'logo_sidebar');
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
