<?php

use app\src\BaseMigration;
use app\src\entities\user\User;

/**
 * Class m180723_231931_create_table__trigger
 */
class m180723_231931_create_table__trigger extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'type' => $this->smallInteger()->unsigned()->notNull(),
            'event' => $this->smallInteger()->unsigned()->notNull(),
            'to_staff' => $this->smallInteger(1)->defaultValue(0),
            'to_customers' => $this->smallInteger(1)->defaultValue(0),
            'template' => $this->text()->notNull(),
            'sender_type' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'sender_email' => $this->string(255),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $this->getTableOptions());

        $this->addForeignKey('fk_trigger__created_by', $this->tableName, 'created_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_trigger__updated_by', $this->tableName, 'updated_by', User::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
