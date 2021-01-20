<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\user\Position;

class s181213_090347_Position extends BaseMigration implements ISeeder
{
    public function seed()
    {
        $this->batchInsert(Position::tableName(), ['name', 'type'], [
            [
                'name' => Position::POSITION_OWNER,
                'type' => Position::TYPE_STAFF,
            ],
            [
                'name' => Position::POSITION_ADMIN,
                'type' => Position::TYPE_STAFF,
            ],
            [
                'name' => Position::POSITION_MANAGER,
                'type' => Position::TYPE_STAFF,
            ],
            [
                'name' => Position::POSITION_TRAINER,
                'type' => Position::TYPE_STAFF,
            ],
        ]);
    }

    public function clean()
    {
        Position::deleteAll();
        $this->consoleLog('position clean');
    }
}