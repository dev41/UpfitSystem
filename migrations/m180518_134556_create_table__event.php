<?php

use app\src\BaseMigration;
use app\src\entities\coaching\Coaching;
use app\src\entities\user\User;

/**
 * Class m180518_134556_create_table__event
 */
class m180518_134556_create_table__event extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'coaching_id' => $this->integer()->notNull(),
            'start' => $this->dateTime(),
            'end' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_event__coaching_id', $this->tableName, 'coaching_id', Coaching::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event__created_by', $this->tableName, 'created_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event__updated_by', $this->tableName, 'updated_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
