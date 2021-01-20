<?php
use app\src\BaseMigration;

/**
 * Class m181207_141224_add_status_column_to__user_event
 */
class m181207_141224_add_status_column_to__user_event
    extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', $this->tinyInteger(1)->defaultValue(1)->after('event_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
    }
}
