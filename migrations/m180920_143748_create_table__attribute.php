<?php

use app\src\BaseMigration;
use app\src\entities\place\Place;

/**
 * Class m180920_143748_create_table__attribute
 */
class m180920_143748_create_table__attribute extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'type' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
        ], $this->getTableOptions());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
