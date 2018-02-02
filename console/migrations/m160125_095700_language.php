<?php
use yii\db\Schema;
use yii\db\Migration;

class m160125_095700_language extends Migration
{

    public function up()
    {
        $this->createTable('{{%language}}', [
            'lang_id' => Schema::TYPE_PK,                                       // идентификатор языка
            'lang_url' => Schema::TYPE_STRING . '(255) NOT NULL',               //буквенный идентификатор языка для отображения в URL(ru, en, de,...)
            'lang_local' => Schema::TYPE_STRING . '(255) NOT NULL',             // язык (локаль) пользователя
            'lang_name' => Schema::TYPE_STRING . '(255) NOT NULL',              // название(English, Русский,...)
            'lang_default' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',    // флаг, указывающий на язык по умолчанию(1 — язык по умолчанию)
            'lang_date_update' => Schema::TYPE_DATE . ' NOT NULL',              // дата обновления(в unixtimestamp)
            'lang_date_create' => Schema::TYPE_DATE. ' NOT NULL',               //дата создания(в unixtimestamp)
        ]);

        $this->batchInsert('{{%language}}', ['lang_url', 'lang_local', 'lang_name', 'lang_default', 'lang_date_update', 'lang_date_create'], [
            ['en', 'en-EN', 'Eng', 0, date('Y-m-d'), date('Y-m-d')],
            ['ru', 'ru-RU', 'Рус', 1, date('Y-m-d'), date('Y-m-d')],
            ['az', 'az-AZ', 'Aze', 0, date('Y-m-d'), date('Y-m-d')],
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%language}}');
    }
}
