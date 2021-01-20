<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use app\src\BaseMigration;

/**
 * Initializes i18n messages tables.
 *
 *
 *
 * @author Dmitry Naumenko <d.naumenko.a@gmail.com>
 * @since 2.0.7
 */
class m150207_210500_i18n_init extends BaseMigration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%i18n_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%i18n_translation}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(5)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%i18n_language}}', [
            'id' => $this->primaryKey(),
            'language' => $this->string(5)->notNull()->unique(),
            'name' => $this->string(50)->notNull(),
            'visible' => $this->boolean()->notNull()->defaultValue(true),
        ], $tableOptions);

        $this->addPrimaryKey('pk_i18n_translation_id_i18n_language', '{{%i18n_translation}}', ['id', 'language']);
        $this->addForeignKey('fk_i18n_translation_i18n_message', '{{%i18n_translation}}', 'id', '{{%i18n_message}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_i18n_translation_i18n_language', '{{%i18n_translation}}', 'language', '{{%i18n_language}}', 'language', 'CASCADE', 'CASCADE');

        $this->batchInsert('{{%i18n_language}}', ['id', 'language', 'name'], [
            [1, 'en', 'English'],
            [2, 'ru', 'Русский'],
            [3, 'uk', 'Українська'],
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_i18n_translation_i18n_message', '{{%i18n_translation}}');
        $this->dropForeignKey('fk_i18n_translation_i18n_language', '{{%i18n_translation}}');

        $this->dropTable('{{%i18n_language}}');
        $this->dropTable('{{%i18n_translation}}');
        $this->dropTable('{{%i18n_message}}');
    }
}
