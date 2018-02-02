<?php

use yii\db\Migration;

class m160217_132658_bonus_help_add_alias extends Migration
{
    public function up()
    {
        $this->truncateTable('{{%bonus_help}}');
        $this->addColumn('{{%bonus_help}}', 'bh_alias', $this->string(50)->notNull());
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
            'bh_alias' => 'help_block',
        ]);
        
        $this->insert('{{%bonus_help}}', [
            'bh_title' => 'Как получить бонусы за регистрации людей',
            'bh_text' => '<div class="b-bonus-steps">
                                <div class="b-bonus-step-block"><span class="b-bonus-step-nubmer">1</span><span class="b-bonus-step-title">Приглашаете<br />людей</span></div>
                                <div class="b-bonus-step-block"><span class="b-bonus-step-nubmer">2</span><span class="b-bonus-step-title">Они<br />регистрируются</span></div>
                                <div class="b-bonus-step-block"><span class="b-bonus-step-nubmer">3</span><span class="b-bonus-step-title">Вы получаете<br />бонус</span></div>
                            </div>',
            'bh_language' => 'ru',
            'bh_alias' => 'reclame_block',
        ]);
    }

    public function down()
    {
        $this->truncateTable('{{%bonus_help}}');
        $this->dropColumn('{{%bonus_help}}', 'bh_alias');
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
