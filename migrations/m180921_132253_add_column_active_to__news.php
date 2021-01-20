<?php

use app\src\BaseMigration;

/**
 * Class m180921_132253_add_column_active_to__news
 */
class m180921_132253_add_column_active_to__news extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'active', $this->tinyInteger(1)->defaultValue(1)->after('description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'active');
    }
}
