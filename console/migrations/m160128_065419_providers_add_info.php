<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_065419_providers_add_info extends Migration
{
    public function safeUp()
    {
        $this->addColumn('providers', 'pc_id', $this->text()->notNull());
        $this->addColumn('providers', 'country_id', $this->integer()->notNull());
        $this->addColumn('providers', 'regexp', $this->text()->notNull());
        $this->addColumn('providers', 'logo', $this->text()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('providers', 'logo');
        $this->dropColumn('providers', 'regexp');
        $this->dropColumn('providers', 'country_id');
        $this->dropColumn('providers', 'pc_id');
    }

}
