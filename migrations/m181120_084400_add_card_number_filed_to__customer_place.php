<?php
use app\src\BaseMigration;

/**
 * Class m181120_084400_add_card_number_filed_to__customer_place
 */
class m181120_084400_add_card_number_filed_to__customer_place extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'card_number', $this->string(50)->after('place_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'card_number');
    }
}
