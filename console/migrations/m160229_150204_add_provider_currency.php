<?php

use yii\db\Migration;

class m160229_150204_add_provider_currency extends Migration
{
    public function up()
    {
        $this->addColumn('providers', 'currency', $this->string()->notNull()->defaultValue(''));

    }

    public function down()
    {
        $this->dropColumn('providers', 'currency');
    }

}
