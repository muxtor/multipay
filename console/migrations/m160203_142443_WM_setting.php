<?php

use yii\db\Schema;
use yii\db\Migration;

class m160203_142443_WM_setting extends Migration
{
    public function up()
    {
        $this->createTable('{{%wm_setting}}', array(
            'wms_id' => 'pk',
            'wms_name' => $this->string(255)->notNull()->unique(),
            'wms_purse' => $this->string(255)->notNull()->unique(),
            'wms_rate' => 'DECIMAL(10,2) NOT NULL DEFAULT "1"',
        ));
        
        $this->insert('{{%wm_setting}}', ['wms_name' => 'WMR']);
    }

    public function down()
    {
        $this->dropTable('{{%wm_setting}}');
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
