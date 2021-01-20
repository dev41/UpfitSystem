<?php

use app\src\BaseMigration;

/**
 * Class m181220_154011_change_required_column_in__email_notification
 */
class m181220_154011_change_required_column_in__email_notification extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'event', $this->smallInteger()->unsigned());

    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'event', $this->integer()->notNull());
    }
}
