<?php

use yii\db\Migration;

class m160310_120853_table_newsletter extends Migration
{
    public function up()
    {
        $this->createTable('{{%newsletter}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(). " COMMENT 'Смс или email'",
            'send_by' => $this->integer()->notNull(). " COMMENT 'Кто отправил'",
            'created' => $this->dateTime(),
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%newsletter}}');
    }

}
