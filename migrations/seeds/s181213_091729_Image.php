<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\user\User;

class s181213_091729_Image extends BaseMigration implements ISeeder
{
    public function seed()
    {
        $this->consoleLog('create images...');

        $this->insert(Image::tableName(), [
            'name' => 'super.jpg',
            'file_name' => '1533538526_super.jpg',
            'parent_id' => 1,
            'size' => 169156,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Image::tableName(), [
            'name' => 'fithaus.png',
            'file_name' => '1533538532_fithaus.png',
            'parent_id' => 2,
            'size' => 3199,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Image::tableName(), [
            'name' => 'kiev.jpg',
            'file_name' => '1533538547_kiev.jpg',
            'parent_id' => 3,
            'size' => 919580,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Image::tableName(), [
            'name' => 'pool.jpg',
            'file_name' => '1533538676_pool.jpg',
            'parent_id' => 4,
            'size' => 206170,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Image::tableName(), [
            'name' => 'box.jpg',
            'file_name' => '1533538682_box.jpg',
            'parent_id' => 5,
            'size' => 161865,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Image::tableName(), [
            'name' => 'cardio.jpg',
            'file_name' => '1533538688_cardio.jpg',
            'parent_id' => 6,
            'size' => 149384,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Image::tableName(), [
            'name' => 'yoga.jpg',
            'file_name' => '1533538697_yoga.jpg',
            'parent_id' => 7,
            'size' => 86198,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);
    }

    public function clean()
    {
        Image::deleteAll();
        $this->consoleLog('image clean');

    }
}