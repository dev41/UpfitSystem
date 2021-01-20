<?php

use app\src\BaseMigration;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\user\Position;
use app\src\entities\user\User;

/**
 * Class m180518_140652_create_table__staff_position_place
 */
class m180518_140652_create_table__staff_position_place extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
            'position_id' => $this->integer()->notNull(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_staff_position_place__user_id', $this->tableName, 'user_id',
            User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_staff_position_place__place_id', $this->tableName, 'place_id',
            Place::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_staff_position_place__position_id', $this->tableName, 'position_id',
            Position::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
