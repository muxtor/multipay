<?php

use yii\db\Migration;

class m160311_103838_add_notice_balannce_isPhone_isActive_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'notice_balannce_isPhone_isActive', $this->smallInteger(). " COMMENT 'Активна или нет рассылка по балансу'");
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'notice_balannce_isPhone_isActive');
    }
}
