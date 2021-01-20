<?php

use yii\db\Migration;

/**
 * Class m181208_152352_create_table__i18n_news
 */
class m181208_152352_create_table__news_i18n extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news_i18n}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(5)->notNull(),
            'name' => $this->string(50)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
        ], $tableOptions);

        $this->addPrimaryKey('pk_news_i18n_id_language', '{{%news_i18n}}', ['id', 'language']);
        $this->addForeignKey('fk_news_i18n_news', '{{%news_i18n}}', 'id', '{{%news}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_news_i18n_i18n_language', '{{%news_i18n}}', 'language', '{{%i18n_language}}', 'language', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_news_i18n_news', '{{%news_i18n}}');
        $this->dropForeignKey('fk_news_i18n_i18n_language', '{{%news_i18n}}');

        $this->dropTable('{{%news_i18n}}');
    }
}
