<?php
use app\src\BaseMigration;
use app\src\entities\user\Position;

/**
 * Class m180513_162648_create_table__position
 */
class m180513_162648_create_table__position extends BaseMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string('50')->unique()->notNull(),
            'type' => $this->smallInteger()->unsigned()->notNull(),
        ], $this->getTableOptions());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
