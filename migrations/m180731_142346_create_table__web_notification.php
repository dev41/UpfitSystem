<?php

use app\src\BaseMigration;
use app\src\entities\user\User;

/**
 * Class m180731_142346_create_table__web_notification
 */
class m180731_142346_create_table__web_notification extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'event' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'message' => $this->text(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_web_notification__user_id', $this->tableName, 'user_id', User::tableName(), 'id', null, 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
