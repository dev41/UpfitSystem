<?php

use app\src\BaseMigration;
use app\src\entities\access\AccessControl;

/**
 * Class m180513_140248_create_table__access_control
 */
class m180513_140248_create_table__access_control extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'slug' => $this->string(50)->notNull(),
            'type' => $this->smallInteger()->unsigned()->defaultValue(AccessControl::TYPE_CUSTOM),
            'access_type' => $this->smallInteger()->unsigned()->defaultValue(AccessControl::ACCESS_TYPE_PERMISSION),
            'parent_id' => $this->integer()->defaultValue(null),
        ], $this->getTableOptions());
    }

    public function seed()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
