<?php

use app\src\BaseMigration;
use app\src\entities\place\Place;
use app\src\entities\user\User;

/**
 * Class m180924_072926_create_table__sale
 */
class m180924_072926_create_table__sale extends BaseMigration
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
            'start' => $this->dateTime()->notNull(),
            'end' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_sale__club_id', $this->tableName, 'club_id', Place::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_sale__created_by', $this->tableName, 'created_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
