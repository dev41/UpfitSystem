<?php

use app\src\BaseMigration;

/**
 * Class m180805_180508_add_parent_id_to__coaching
 */
class m180805_180508_add_parent_id_to__coaching extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'parent_id', $this->integer()->null()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'parent_id');
    }
}
