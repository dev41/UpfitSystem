<?php

use app\src\BaseMigration;

/**
 * Class m181218_154132_add_foreign_key_to__coaching
 */
class m181218_154132_add_foreign_key_to__coaching extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_coaching__parent_id', $this->tableName, 'parent_id', $this->tableName, 'id', null, 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_coaching__parent_id', $this->tableName);
    }
}