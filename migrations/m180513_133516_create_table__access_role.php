<?php

use app\src\BaseMigration;
use app\src\entities\access\AccessRole;

/**
 * Class m180513_133516_create_table__access_role
 */
class m180513_133516_create_table__access_role extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->unsigned()->defaultValue(AccessRole::TYPE_DEFAULT),
            'name' => $this->string(50)->notNull(),
            'slug' => $this->string(50)->notNull()->unique(),
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
