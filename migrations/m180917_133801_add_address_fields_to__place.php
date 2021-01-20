<?php

use app\src\BaseMigration;

/**
 * Class m180917_133801_add_address_fields_to__place
 */
class m180917_133801_add_address_fields_to__place extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'country', $this->string()->after('active'));
        $this->addColumn($this->tableName, 'city', $this->string()->after('country'));
        $this->addColumn($this->tableName, 'lat', $this->float()->after('city'));
        $this->addColumn($this->tableName, 'lng', $this->float()->after('lat'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'country');
        $this->dropColumn($this->tableName, 'city');
        $this->dropColumn($this->tableName, 'lat');
        $this->dropColumn($this->tableName, 'lng');
    }
}
