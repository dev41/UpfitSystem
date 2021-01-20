<?php

use app\src\BaseMigration;

/**
 * Class m181221_131603_delete_type_field_from__trigger
 */
class m181221_131603_delete_type_field_from__trigger extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'type');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn($this->tableName, 'type' ,$this->smallInteger()->unsigned()->notNull());
    }
}
