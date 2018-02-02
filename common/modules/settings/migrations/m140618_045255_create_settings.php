
<?php

class m140618_045255_create_settings extends \yii\db\Migration
{
  public function up()
  {
    $this->createTable('{{%settings}}',
        [
            'id' => $this->primaryKey(),
            'type' => $this->string(255)->notNull(),
            'section' => $this->string(255)->notNull(),
            'key' => $this->string(255)->notNull(),
            'value' => $this->text(),
            'active' => $this->boolean(),
            'created' => $this->dateTime(),
            'modified' => $this->dateTime(),
        ]
    );
    $this->createIndex('settings_unique_key_section', '{{%settings}}', ['section', 'key'], true);
  }

  public function down()
  {
    $this->dropIndex('settings_unique_key_section', '{{%settings}}');
    $this->dropTable('{{%settings}}');
  }
}