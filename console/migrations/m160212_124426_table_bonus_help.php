<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_124426_table_bonus_help extends Migration
{
    public function up()
    {
        $this->createTable('{{%bonus_help}}', [
            'bh_id' => $this->primaryKey(),
            'bh_title' => $this->string(255),
            'bh_text' => $this->text()->notNull(),
            'bh_language' => $this->string(10)->notNull(),
        ]);
        $this->insert('{{%bonus_help}}', [
            'bh_title' => 'Полезные советы',
            'bh_text' => '<p>Больше бонусов — больше скидка!
                                Используйте накопленные бонусы для
                                получения скидки в магазинах MultiPay.
                                Размер скидки отображается справа
                                от балланса бонусов.</p><br>
                            <p>Теперь за покупки через MultiPay, вы
                                получите бонусы</p>',
            'bh_language' => 'ru',
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%bonus_help}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
