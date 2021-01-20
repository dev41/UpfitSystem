<?php

use app\src\BaseMigration;

/**
 * Class m181226_093409_add_type_field_to__trigger_place
 */
class m181226_093409_add_type_field_to__trigger_place extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {;
        $this->addColumn($this->tableName, 'type', $this->smallInteger()->unsigned()->defaultValue(0)->after('place_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'type');
    }
}
