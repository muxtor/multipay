<?php

use yii\db\Migration;

class m160224_072443_add_status_to_provider extends Migration
{
    public function up()
    {
        $this->addColumn('{{%providers}}', 'status', $this->smallInteger()->notNull()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('{{%providers}}', 'status');
    }
}
