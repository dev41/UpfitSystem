<?php
use app\src\BaseMigration;

/**
 * Class m181205_090752_add_pay_fields_to__place
 */
class m181205_090752_add_pay_fields_to__place extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'public_key', $this->string(50)->after('instagram_id'));
        $this->addColumn($this->tableName, 'private_key', $this->string(100)->after('public_key'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'public_key');
        $this->dropColumn($this->tableName, 'private_key');
    }
}
