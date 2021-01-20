<?php

use app\src\BaseMigration;
use app\src\entities\activity\Activity;
use app\src\entities\news\News;
use app\src\entities\sale\Sale;

/**
 * Class m181106_152959_change_name_fields_length
 */
class m181106_152959_change_name_fields_length extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(News::tableName(), 'name', $this->string(255)->notNull());
        $this->alterColumn(Activity::tableName(), 'name', $this->string(255)->notNull());
        $this->alterColumn(Sale::tableName(), 'name', $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
