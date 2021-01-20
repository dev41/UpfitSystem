<?php

use app\src\BaseMigration;
use app\src\entities\access\AccessControl;
use app\src\entities\access\AccessPermission;

/**
 * Class m180513_145204_create_table__access_permission
 */
class m180513_145204_create_table__access_permission extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->unsigned()->null(),
            'control_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey(
            'fk_access_permission__control_id',
            $this->tableName, 'control_id',
            AccessControl::tableName(), 'id',
            'CASCADE', 'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

}
