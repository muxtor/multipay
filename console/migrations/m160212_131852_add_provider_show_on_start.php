<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_131852_add_provider_show_on_start extends Migration
{
    public function up()
    {
        $this->addColumn('providers', 'show_on_start', $this->smallInteger()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('providers', 'show_on_start');
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
