<?php

use app\src\BaseMigration;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\user\User;

/**
 * Class m180518_104915_create_table__coaching
 */
class m180518_104915_create_table__coaching extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'price' => $this->float(),
            'capacity' => $this->smallInteger()->unsigned()->notNull()->defaultValue(Coaching::DEFAULT_CAPACITY),
            'color' => $this->string(10),
            'coaching_level_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_coaching__coaching_level_id', $this->tableName, 'coaching_level_id', CoachingLevel::tableName(), 'id');
        $this->addForeignKey('fk_coaching__created_by', $this->tableName, 'created_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_coaching__updated_by', $this->tableName, 'updated_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
