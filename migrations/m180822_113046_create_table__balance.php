<?php

use app\src\BaseMigration;
use app\src\entities\place\Place;
use app\src\entities\user\User;

/**
 * Class m180731_142346_create_table__balance
 */
class m180822_113046_create_table__balance extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
            'amount' => $this->decimal()->defaultValue(0),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_balance__user_id', $this->tableName, 'user_id', User::tableName(), 'id', null, 'CASCADE');
        $this->addForeignKey('fk_balance__place_id', $this->tableName, 'place_id', Place::tableName(), 'id', null, 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
