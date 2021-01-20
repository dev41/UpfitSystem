<?php

use yii\db\Migration;

/**
 * Class m181208_153008_create_table__activity_i18n
 */
class m181208_153008_create_table__activity_i18n extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%activity_i18n}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(5)->notNull(),
            'name' => $this->string(50)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
        ], $tableOptions);

        $this->addPrimaryKey('pk_activity_i18n_id_language', '{{%activity_i18n}}', ['id', 'language']);
        $this->addForeignKey('fk_activity_i18n_activity', '{{%activity_i18n}}', 'id', '{{%activity}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_activity_i18n_i18n_language', '{{%activity_i18n}}', 'language', '{{%i18n_language}}', 'language', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_activity_i18n_activity', '{{%activity_i18n}}');
        $this->dropForeignKey('fk_activity_i18n_i18n_language', '{{%activity_i18n}}');

        $this->dropTable('{{%activity_i18n}}');
    }
}
