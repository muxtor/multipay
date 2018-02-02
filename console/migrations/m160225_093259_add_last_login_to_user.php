<?php

use yii\db\Migration;

class m160225_093259_add_last_login_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'last_login', $this->dateTime());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'last_login');
    }
}
