<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_134029_providers_add_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('providers', 'description', $this->text()->notNull());

    }

    public function safeDown()
    {
        $this->dropColumn('providers', 'description');
    }

}
