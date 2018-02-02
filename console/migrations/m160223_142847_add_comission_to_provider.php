<?php

use yii\db\Migration;

class m160223_142847_add_comission_to_provider extends Migration
{
    public function up()
    {
        $this->addColumn('{{%providers}}', 'comission_percent', $this->decimal(10,2)->notNull()->defaultValue(0).' COMMENT "Комиссия при оплате провайдера (%)"');
    }

    public function down()
    {
        $this->dropColumn('{{%providers}}', 'comission_percent');
    }
}
