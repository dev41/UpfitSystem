<?php

use app\src\BaseMigration;
use app\src\entities\user\User;

/**
 * Class m180513_162637_create_table__image
 */
class m180513_162637_create_table__image extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, array(
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'file_name' => $this->string(255)->unique(),
            'parent_id' => $this->integer(),
            'size' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
        ), $this->getTableOptions());

        $this->addForeignKey('fk_image__created_by', $this->tableName, 'created_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
