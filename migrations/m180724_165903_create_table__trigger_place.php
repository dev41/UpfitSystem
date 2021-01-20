<?php

use app\src\BaseMigration;
use app\src\entities\place\Place;
use app\src\entities\trigger\Trigger;

/**
 * Class m180724_165903_create_table__trigger_place
 */
class m180724_165903_create_table__trigger_place extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'trigger_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_trigger_place', $this->tableName, ['trigger_id', 'place_id']);

        $this->addForeignKey('fk_trigger_place__trigger_id', $this->tableName, 'trigger_id',
            Trigger::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_trigger_place__place_id', $this->tableName, 'place_id',
            Place::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

}
