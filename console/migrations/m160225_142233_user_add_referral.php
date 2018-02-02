<?php

use yii\db\Migration;

class m160225_142233_user_add_referral extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'isReferral', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%user}}', 'referral_id', $this->integer()->notNull()->defaultValue(0).' COMMENT "ID того кто пригласил"');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'isReferral');
        $this->dropColumn('{{%user}}', 'referral_id');
    }
}
