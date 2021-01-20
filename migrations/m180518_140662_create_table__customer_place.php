<?php

use app\src\BaseMigration;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\user\User;

/**
 * Class m180518_140662_create_table__customer_place
 */
class m180518_140662_create_table__customer_place extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'user_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger(3)->notNull()->defaultValue(CustomerPlace::STATUS_PENDING),
            'created_at' => $this->dateTime()->defaultExpression('NOW()')->notNull(),
        ], $this->getTableOptions());

        $this->addPrimaryKey('pk_customer_place', $this->tableName, ['user_id', 'place_id']);

        $this->addForeignKey('fk_customer_place__user_id', $this->tableName, 'user_id',
            User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_customer_place__place_id', $this->tableName, 'place_id',
            Place::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
