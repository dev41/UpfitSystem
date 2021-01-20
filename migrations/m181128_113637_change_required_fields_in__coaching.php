<?php

use app\src\BaseMigration;
use app\src\entities\coaching\Coaching;

/**
 * Class m181128_113637_change_required_fields_in__coaching
 */
class m181128_113637_change_required_fields_in__coaching extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'name', $this->string(255));
        $this->alterColumn($this->tableName, 'capacity', $this->smallInteger()->unsigned()->defaultValue(Coaching::DEFAULT_CAPACITY));
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'name', $this->string(255)->notNull());
        $this->alterColumn($this->tableName, 'capacity', $this->smallInteger()->unsigned()->notNull()->defaultValue(Coaching::DEFAULT_CAPACITY));
    }
}
