<?php

use app\src\BaseMigration;

/**
 * Class m180917_134754_add_column_type_to__image
 */
class m180917_134754_add_column_type_to__image extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'type', $this->smallInteger()->defaultValue(0)->after('size'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'type');
    }
}
