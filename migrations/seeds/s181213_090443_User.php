<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\AbstractModel;
use app\src\entities\access\AccessRole;
use app\src\entities\user\User;

class s181213_090443_User extends BaseMigration implements ISeeder
{
    public function seed()
    {
        $superAdminRole = AccessRole::getRoleBySlug(AccessRole::ROLE_SUPER_ADMIN);
        $trainerRole = AccessRole::getRoleBySlug('trainer');
        $adminRole = AccessRole::getRoleBySlug('admin');
        $clientRole = AccessRole::getRoleBySlug('client');

        /*
         * Create first user:
         * name: admin
         * pass: 123456
         */
        $this->insert(User::tableName(), [
            'id' => 1,
            'username' => 'admin',
            'first_name' => 'Henry',
            'last_name' => 'Saimon',
            'email' => 'admin@mail.com',
            'fb_user_id' => '426581574530111',
            'phone' => '+370682132612',
            'address' => 'Saxon Euphory Club, улица Михайла Максимовича, Киев',
            'birthday' => AbstractModel::getDateNow(),
            'password_hash' => '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
            'auth_key' => 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-R',
            'status' => User::STATUS_ACTIVE,
            'role_id' => $superAdminRole->id,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => 1,
        ]);

        /*
         * trainer
         * name: andrey
         * pass: 123456
         */
        $this->insert(User::tableName(), [
            'username' => 'andrey',
            'first_name' => 'Andrey',
            'last_name' => 'Vangov',
            'email' => 'vangov@mail.com',
            'fb_user_id' => '181724282660547',
            'phone' => '+370674132612',
            'address' => 'Украина, 01133, г. Киев, ул. Кутузова, 18/7',
            'birthday' => AbstractModel::getDateNow(),
            'password_hash' => '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
            'auth_key' => 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-F',
            'status' => User::STATUS_ACTIVE,
            'role_id' => $trainerRole->id,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        /*
        * admin
        * name: vasiliy
        * pass: 123456
        */
        $this->insert(User::tableName(), [
            'username' => 'vasiliy',
            'first_name' => 'Valisiy',
            'last_name' => 'Ivanov',
            'email' => 'vasiliy.n@mail.com',
            'fb_user_id' => '281724282660547',
            'phone' => '+370614132612',
            'address' => 'Украина, 01133, г. Запорожье, ул. Грязнова, 18/7',
            'birthday' => AbstractModel::getDateNow(),
            'password_hash' => '$2y$13$V1GtkXd0xHXlxdB9eac/4.VMfoAi77er72oy.J5PAFKvOqJbguSSC',
            'auth_key' => 'xYLvDk-HkrBFXeudsUa6ZFPJK5J1Pzuk',
            'status' => User::STATUS_ACTIVE,
            'role_id' => $adminRole->id,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        /*
       * client
       * name: ivan
       * pass: 123456
       */
        $this->insert(User::tableName(), [
            'username' => 'ivan',
            'first_name' => 'Ivan',
            'last_name' => 'Petrov',
            'email' => 'ivan.p@mail.com',
            'fb_user_id' => '291724282660547',
            'phone' => '+370514132612',
            'address' => 'Украина, 02133, г. Харьков, ул. Победы, 28/7',
            'birthday' => AbstractModel::getDateNow(),
            'password_hash' => '$2y$13$V1GtkXd0xHXlxdB9eac/4.VMfoAi77er72oy.J5PAFKvOqJbguSSC',
            'auth_key' => 'xYLvDk-HkrBFXeudsUa6ZFPJK5J1Pzuk',
            'status' => User::STATUS_ACTIVE,
            'role_id' => $clientRole->id,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);
    }

    public function clean()
    {
        User::deleteAll();
        $this->consoleLog('user clean');
    }
}