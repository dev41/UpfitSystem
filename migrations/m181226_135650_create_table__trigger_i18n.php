<?php

use yii\db\Migration;

/**
 * Class m181226_135650_create_table__trigger_i18n
 */
class m181226_135650_create_table__trigger_i18n extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%trigger_i18n}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(5)->notNull(),
            'title' => $this->string(50)->defaultValue(null),
            'template' => $this->text()->defaultValue(null),
        ], $tableOptions);

        $this->addPrimaryKey('pk_trigger_i18n_id_language', '{{%trigger_i18n}}', ['id', 'language']);
        $this->addForeignKey('fk_trigger_i18n_trigger', '{{%trigger_i18n}}', 'id', '{{%trigger}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_trigger_i18n_i18n_language', '{{%trigger_i18n}}', 'language', '{{%i18n_language}}', 'language', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_trigger_i18n_trigger', '{{%trigger_i18n}}');
        $this->dropForeignKey('fk_trigger_i18n_i18n_language', '{{%trigger_i18n}}');

        $this->dropTable('{{%trigger_i18n}}');
    }
}
