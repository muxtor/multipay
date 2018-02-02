<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_092043_add_user_login_stats extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_login_stats}}', [
            'uls_id' => $this->primaryKey(),
            'uls_user_id' => $this->integer()->notNull(),
            'uls_IP' => $this->string(50)->notNull(),
            'uls_app' => $this->string(255)->notNull(),
            'uls_location' => $this->string(255)->notNull(),
            'uls_date_visit' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('FK_user_login_stats_user', '{{%user_login_stats}}', 'uls_user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_user_login_stats_user', '{{%user_login_stats}}');
        $this->dropTable('{{%user_login_stats}}');
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
