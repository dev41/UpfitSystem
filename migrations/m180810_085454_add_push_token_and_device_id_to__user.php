<?php

use app\src\BaseMigration;

/**
 * Class m180810_085454_add_push_token_and_device_id_to__user
 */
class m180810_085454_add_push_token_and_device_id_to__user extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'push_token', $this->string()->null()->after('fb_token'));
        $this->addColumn($this->tableName, 'device_id', $this->string()->null()->after('push_token'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'push_token');
        $this->dropColumn($this->tableName, 'device_id');
    }
}
