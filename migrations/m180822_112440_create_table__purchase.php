<?php

use app\src\BaseMigration;
use app\src\entities\place\Place;
use app\src\entities\user\User;

/**
 * Class m180731_142346_create_table__purchase
 */
class m180822_112440_create_table__purchase extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
            'currency' => $this->string(10),
            'type' => $this->smallInteger(),
            'pay_type' => $this->smallInteger(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'amount' => $this->decimal()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_purchase__user_id', $this->tableName, 'user_id', User::tableName(), 'id', null, 'CASCADE');
        $this->addForeignKey('fk_purchase__place_id', $this->tableName, 'place_id', Place::tableName(), 'id', null, 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
