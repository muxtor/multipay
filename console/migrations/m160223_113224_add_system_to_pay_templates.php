<?php

use yii\db\Migration;

class m160223_113224_add_system_to_pay_templates extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pay_template}}', 'pt_system', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%pay_template}}', 'pt_system');
    }
}
