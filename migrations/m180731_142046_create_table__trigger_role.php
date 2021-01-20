<?php

use app\src\BaseMigration;
use app\src\entities\access\AccessRole;
use app\src\entities\trigger\Trigger;

/**
 * Class m180731_142046_create_table__trigger_role
 */
class m180731_142046_create_table__trigger_role extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'trigger_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_trigger_role', $this->tableName, ['trigger_id', 'role_id']);

        $this->addForeignKey('fk_trigger_role__trigger_id', $this->tableName, 'trigger_id',
            Trigger::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_trigger_role__role_id', $this->tableName, 'role_id',
            AccessRole::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
