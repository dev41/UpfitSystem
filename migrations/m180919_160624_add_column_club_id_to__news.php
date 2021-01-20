<?php

use app\src\BaseMigration;
use app\src\entities\place\Place;

/**
 * Class m180919_160624_add_column_club_id_to__news
 */
class m180919_160624_add_column_club_id_to__news extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'club_id', $this->integer()->notNull()->after('id'));
        $this->addForeignKey('fk_news__club_id', $this->tableName, 'club_id', Place::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_news__club_id', $this->tableName);
        $this->dropColumn($this->tableName, 'club_id');
    }
}
