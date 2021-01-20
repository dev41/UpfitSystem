<?php
use app\src\BaseMigration;

/**
 * Class m181113_081849_add_contact_fields_to__place
 */
class m181113_081849_add_contact_fields_to__place extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'phone_number', $this->string(50)->after('lng'));
        $this->addColumn($this->tableName, 'email', $this->string(100));
        $this->addColumn($this->tableName, 'site', $this->string(255));
        $this->addColumn($this->tableName, 'facebook_id', $this->string(50));
        $this->addColumn($this->tableName, 'instagram_id', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'phone_number');
        $this->dropColumn($this->tableName, 'email');
        $this->dropColumn($this->tableName, 'site');
        $this->dropColumn($this->tableName, 'facebook_id');
        $this->dropColumn($this->tableName, 'instagram_id');
    }
}
