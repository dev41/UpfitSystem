<?php

use app\src\BaseMigration;
use app\src\entities\place\Place;
use app\src\entities\user\User;

/**
 * Class m180513_164204_create_table__place
 */
class m180513_164204_create_table__place extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'type' => $this->smallInteger()->unsigned()->notNull()->defaultValue(Place::TYPE_CLUB),
            'name' => $this->string(50)->notNull(),
            'description' => $this->text(),
            'address' => $this->string(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_place__created_by', $this->tableName, 'created_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_place__updated_by', $this->tableName, 'updated_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
