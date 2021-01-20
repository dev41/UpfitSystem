<?php

use app\src\BaseMigration;
use app\src\entities\coaching\Event;
use app\src\entities\user\User;

/**
 * Class m180518_142153_create_table__user_event
 */
class m180518_142153_create_table__user_event extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'user_id' => $this->integer()->notNull(),
            'event_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_user_event', $this->tableName, ['user_id', 'event_id']);

        $this->addForeignKey('fk_user_event__user_id', $this->tableName, 'user_id', User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_event__event_id', $this->tableName, 'event_id', Event::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
