<?php

use app\src\BaseMigration;

/**
 * Class m181211_155036_create_table__transform_transactions
 */
class m181211_155036_create_table__transform_transactions extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, array(
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'type' => $this->integer(),
            'apply_time' => $this->integer(),
        ), $this->getTableOptions());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
