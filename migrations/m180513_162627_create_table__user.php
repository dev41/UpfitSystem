<?php

use app\src\BaseMigration;
use app\src\entities\access\AccessRole;
use app\src\entities\user\User;

/**
 * Class m180513_162627_create_table__user
 */
class m180513_162627_create_table__user extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, array(
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->unique(),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string(100),
            'password_reset_token' => $this->string(100),
            'email' => $this->string(100)->unique()->null(),
            'api_auth_key' => $this->string(32),
            'fb_user_id' => $this->string(50)->unique(),
            'fb_token' => $this->string(255),
            'role_id' => $this->integer()->defaultValue(null),
            'first_name' => $this->string(50)->defaultValue(null),
            'last_name' => $this->string(50)->defaultValue(null),
            'address' => $this->string(100)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
            'phone' => $this->string(20)->defaultValue(null),
            'birthday' => $this->date(),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(User::STATUS_ACTIVE),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),// one signal
        ), $this->getTableOptions());

        $this->addForeignKey('fk_user__role_id', $this->tableName, 'role_id', AccessRole::tableName(), 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
