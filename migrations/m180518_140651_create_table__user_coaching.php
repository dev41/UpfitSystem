<?php

use app\src\BaseMigration;
use app\src\entities\coaching\Coaching;
use app\src\entities\user\User;

/**
 * Class m180518_140651_create_table__user_coaching
 */
class m180518_140651_create_table__user_coaching extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'coaching_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_user_coaching__user_id', $this->tableName, 'user_id',
            User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_coaching__coaching_id', $this->tableName, 'coaching_id',
            Coaching::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
