<?php

use app\src\BaseMigration;

/**
 * Class m180819_121837_add_column_active_to__place
 */
class m180819_121837_add_column_active_to__place extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'active', $this->tinyInteger(1)->defaultValue(1)->after('address'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'active');
    }
}
