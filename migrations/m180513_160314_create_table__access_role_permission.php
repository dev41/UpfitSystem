<?php

use app\src\BaseMigration;
use app\src\entities\access\AccessPermission;
use app\src\entities\access\AccessRole;

/**
 * Class m180513_160314_create_table__access_role_permission
 */
class m180513_160314_create_table__access_role_permission extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'role_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_access_role_permission', $this->tableName, ['role_id', 'permission_id']);

        $this->addForeignKey(
            'fk_access_role_permission__role_id',
            $this->tableName, 'role_id',
            AccessRole::tableName(), 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            'fk_access_role_permission__permission_id',
            $this->tableName, 'permission_id',
            AccessPermission::tableName(), 'id',
            'CASCADE', 'CASCADE'
        );
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
