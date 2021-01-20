<?php

use app\src\BaseMigration;
use app\src\entities\activity\Activity;
use app\src\entities\user\User;

/**
 * Class m180922_105512_create_table__user_activity
 */
class m180922_105512_create_table__user_activity extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'user_id' => $this->integer()->notNull(),
            'activity_id' => $this->integer()->notNull(),
            'is_staff' => $this->tinyInteger(1)->defaultValue(0)
        ], $this->getTableOptions());

        $this->addForeignKey('fk_user_activity__activity_id', $this->tableName, 'activity_id', Activity::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_activity__user_id', $this->tableName, 'user_id', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
