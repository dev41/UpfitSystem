<?php
use app\src\BaseMigration;

/**
 * Class m181207_144359_add_payment_fields_to__purchase
 */
class m181207_144359_add_payment_fields_to__purchase
    extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'expired_date', $this->dateTime()->after('amount'));
        $this->addColumn($this->tableName, 'product_type', $this->smallInteger()->after('expired_date'));
        $this->addColumn($this->tableName, 'product_id', $this->smallInteger()->after('product_type'));
        $this->addColumn($this->tableName, 'action', $this->string(255)->after('product_id'));
        $this->addColumn($this->tableName, 'response', $this->string(255)->after('action'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'expired_date');
        $this->dropColumn($this->tableName, 'product_type');
        $this->dropColumn($this->tableName, 'product_id');
        $this->dropColumn($this->tableName, 'action');
        $this->dropColumn($this->tableName, 'response');
    }
}
