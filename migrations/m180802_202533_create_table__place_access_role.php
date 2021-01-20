<?php

use app\src\BaseMigration;
use app\src\entities\access\AccessRole;
use app\src\entities\place\Place;

/**
 * Class m180802_202533_create_table__place_access_role
 */
class m180802_202533_create_table__place_access_role extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'access_role_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_place_access_role', $this->tableName, ['access_role_id', 'place_id']);

        $this->addForeignKey('fk_place_access_role__access_role_id', $this->tableName, 'access_role_id', AccessRole::tableName(), 'id');
        $this->addForeignKey('fk_place_access_role__place_id', $this->tableName, 'place_id', Place::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
