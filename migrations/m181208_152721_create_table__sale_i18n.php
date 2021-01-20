<?php

use yii\db\Migration;

/**
 * Class m181208_152721_create_table__sale_i18n
 */
class m181208_152721_create_table__sale_i18n extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sale_i18n}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(5)->notNull(),
            'name' => $this->string(50)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
        ], $tableOptions);

        $this->addPrimaryKey('pk_sale_i18n_id_language', '{{%sale_i18n}}', ['id', 'language']);
        $this->addForeignKey('fk_sale_i18n_sale', '{{%sale_i18n}}', 'id', '{{%sale}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_sale_i18n_i18n_language', '{{%sale_i18n}}', 'language', '{{%i18n_language}}', 'language', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_sale_i18n_sale', '{{%sale_i18n}}');
        $this->dropForeignKey('fk_sale_i18n_i18n_language', '{{%sale_i18n}}');

        $this->dropTable('{{%sale_i18n}}');
    }
}
