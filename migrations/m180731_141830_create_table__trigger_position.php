<?php

use app\src\BaseMigration;
use app\src\entities\trigger\Trigger;
use app\src\entities\user\Position;

/**
 * Class m180731_141830_create_table__trigger_position
 */
class m180731_141830_create_table__trigger_position extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'trigger_id' => $this->integer()->notNull(),
            'position_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_trigger_position', $this->tableName, ['trigger_id', 'position_id']);

        $this->addForeignKey('fk_trigger_position__trigger_id', $this->tableName, 'trigger_id',
            Trigger::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_trigger_position__position_id', $this->tableName, 'position_id',
            Position::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
