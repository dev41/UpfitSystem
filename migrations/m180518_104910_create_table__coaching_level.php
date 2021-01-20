<?php

use app\src\BaseMigration;

/**
 * Class m180518_104910_create_table__coaching_level
 */
class m180518_104910_create_table__coaching_level extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
        ], $this->getTableOptions());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
