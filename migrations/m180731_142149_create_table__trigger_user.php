<?php

use app\src\BaseMigration;
use app\src\entities\trigger\Trigger;
use app\src\entities\user\User;

/**
 * Class m180731_142149_create_table__trigger_user
 */
class m180731_142149_create_table__trigger_user extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'trigger_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_trigger_user', $this->tableName, ['trigger_id', 'user_id']);

        $this->addForeignKey('fk_trigger_user__trigger_id', $this->tableName, 'trigger_id',
            Trigger::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_trigger_user__user_id', $this->tableName, 'user_id',
            User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
