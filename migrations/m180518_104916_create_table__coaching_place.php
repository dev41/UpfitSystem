<?php

use app\src\BaseMigration;
use app\src\entities\coaching\Coaching;
use app\src\entities\place\Place;

/**
 * Class m180518_104916_create_table__coaching_place
 */
class m180518_104916_create_table__coaching_place extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'coaching_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
            'place_type' => $this->smallInteger()->unsigned()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_coaching_place__coaching_id', $this->tableName,
            'coaching_id', Coaching::tableName(), 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_coaching_place__place_id', $this->tableName,
            'place_id', Place::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

}
