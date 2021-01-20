<?php

use yii\db\Migration;

/**
 * Class m181207_134903_create_table__place_i18n
 */
class m181207_134903_create_table__place_i18n extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%place_i18n}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(5)->notNull(),
            'name' => $this->string(50)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
            'address' => $this->string(255)->defaultValue(null),
        ], $tableOptions);

        $this->addPrimaryKey('pk_place_i18n_id_language', '{{%place_i18n}}', ['id', 'language']);
        $this->addForeignKey('fk_place_i18n_place', '{{%place_i18n}}', 'id', '{{%place}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_place_i18n_i18n_language', '{{%place_i18n}}', 'language', '{{%i18n_language}}', 'language', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_place_i18n_place', '{{%place_i18n}}');
        $this->dropForeignKey('fk_place_i18n_i18n_language', '{{%place_i18n}}');

        $this->dropTable('{{%place_i18n}}');
    }
}
