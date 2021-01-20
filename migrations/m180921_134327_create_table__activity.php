<?php

use app\src\BaseMigration;
use app\src\entities\activity\Activity;
use app\src\entities\place\Place;
use app\src\entities\user\User;

/**
 * Class m180921_134327_create_table__activity
 */
class m180921_134327_create_table__activity extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'club_id' => $this->integer()->notNull(),
            'name' => $this->string(50)->notNull(),
            'description' => $this->text(),
            'price' => $this->float(),
            'capacity' => $this->smallInteger()->unsigned()->defaultValue(Activity::DEFAULT_CAPACITY),
            'start' => $this->dateTime(),
            'end' => $this->dateTime(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_activity__club_id', $this->tableName, 'club_id', Place::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_activity__created_by', $this->tableName, 'created_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
