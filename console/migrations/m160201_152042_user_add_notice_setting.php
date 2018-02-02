<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_152042_user_add_notice_setting extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'notice_safety_isEmail', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_safety_isPhone', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_plannedPayments_isEmail', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_plannedPayments_isPhone', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_latePayments_isEmail', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_latePayments_isPhone', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_news_isEmail', $this->smallInteger()->defaultValue(null));
        $this->addColumn('{{%user}}', 'notice_news_isPhone', $this->smallInteger()->defaultValue(null));
        
        $this->addColumn('{{%user}}', 'verification_code_send', $this->smallInteger()->defaultValue(0));
        $this->addColumn('{{%user}}', 'verification_code_method', $this->smallInteger()->defaultValue(1));
        
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'notice_safety_isEmail');
        $this->dropColumn('{{%user}}', 'notice_safety_isPhone');
        $this->dropColumn('{{%user}}', 'notice_plannedPayments_isEmail');
        $this->dropColumn('{{%user}}', 'notice_plannedPayments_isPhone');
        $this->dropColumn('{{%user}}', 'notice_latePayments_isEmail');
        $this->dropColumn('{{%user}}', 'notice_latePayments_isPhone');
        $this->dropColumn('{{%user}}', 'notice_news_isEmail');
        $this->dropColumn('{{%user}}', 'notice_news_isPhone');
        
        $this->dropColumn('{{%user}}', 'verification_code_send');
        $this->dropColumn('{{%user}}', 'verification_code_method');
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
