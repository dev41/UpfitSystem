<?php

use app\src\BaseMigration;

/**
 * Class m181220_115552_add_fields_to__trigger
 */
class m181220_115552_add_fields_to__trigger extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {;
        $this->addColumn($this->tableName, 'title', $this->string(50)->after('sender_email'));
        $this->addColumn($this->tableName, 'advanced_filters', $this->tinyInteger(1)->defaultValue(0)->after('title'));
        $this->addColumn($this->tableName, 'is_newsletter', $this->tinyInteger(1)->defaultValue(0)->after('advanced_filters'));
        $this->alterColumn($this->tableName, 'event', $this->smallInteger()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'title');
        $this->dropColumn($this->tableName, 'advanced_filters');
        $this->dropColumn($this->tableName, 'is_newsletter');
        $this->alterColumn($this->tableName, 'event', $this->smallInteger()->unsigned()->notNull());
    }
}
