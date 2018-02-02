<?php

use yii\db\Migration;

class m160405_142310_alter_table_slider_interval extends Migration
{
    public function up()
    {
        $this->addColumn('{{%slider_interval}}', 'interval_partner', $this->integer()->defaultValue(10));
    }

    public function down()
    {
        $this->dropColumn('{{%slider_interval}}', 'interval_partner');
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
