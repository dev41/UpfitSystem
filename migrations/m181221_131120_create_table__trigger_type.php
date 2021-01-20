<?php

use app\src\BaseMigration;
use app\src\entities\trigger\Trigger;

/**
 * Class m181221_131120_create_table__trigger_type
 */
class m181221_131120_create_table__trigger_type extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'trigger_id' => $this->integer(),
            'type' => $this->smallInteger()->unsigned()->notNull(),
            'priority' => $this->smallInteger()->unsigned()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_trigger_type__trigger_id', $this->tableName, 'trigger_id', Trigger::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
