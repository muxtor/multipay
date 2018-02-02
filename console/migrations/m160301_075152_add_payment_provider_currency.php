<?php

use yii\db\Migration;

class m160301_075152_add_payment_provider_currency extends Migration
{
    public function up()
    {
        $this->addColumn('{{%payments}}', 'pay_provider_currency_pc', $this->string()->notNull()->defaultValue(''). " COMMENT 'Валюта счета провайдера в ПЦ'");

    }

    public function down()
    {
        $this->dropColumn('{{%payments}}', 'pay_provider_currency_pc');
    }

}
