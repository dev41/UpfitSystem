<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\AbstractModel;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\place\PlaceAccessRole;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\Position;
use app\src\entities\user\User;

class s181213_091525_Place extends BaseMigration implements ISeeder
{
    public function seed()
    {
        $this->consoleLog('create places...');

        $this->insert(Place::tableName(), [
            'id' => 1,
            'name' => 'Super Сlub',
            'type' => Place::TYPE_CLUB,
            'description' => 'Этот клуб видит только суперадмин',
            'address' => 'ул. Лермонтова 9а, Запорожье, Украина',
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Place::tableName(), [
            'id' => 2,
            'name' => 'FIT HAUS',
            'type' => Place::TYPE_CLUB,
            'description' => 'В сети Fit Haus представлен самый широкий выбор премиум оборудования от лучших мировых производителей фитнес индустрии. Широкий выбор современных и эффективных как индивидуальных, так и групповых программ которые помогут Вам достичь максимального результата',
            'address' => 'ул. Лермонтова 9а, Запорожье, Украина',
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Place::tableName(), [
            'id' => 3,
            'name' => 'Kiev Sport',
            'type' => Place::TYPE_CLUB,
            'description' => 'У нас это не ассоциация барменов и косметологов, это спортивная семья. Дети первых клиентов теперь тренируются вместе со своими детьми и родителями и дружат семьями с инструкторами.',
            'address' => 'г. Киев, Бульвар Дружбы Народов 5,',
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Place::tableName(), [
            'id' => 4,
            'parent_id' => Place::getFirstEntityId(),
            'name' => 'Бассейн',
            'description' => 'Да, он небольшой. А разве может райский уголок быть большим? Конечно, киевское солнце не сравнить с калифорнийским, но зеленая трава, синяя вода, белый шезлонг и легкая музыка заставляют забыть об этом. А еще мы здесь круглый год тренируемся.',
            'address' => 'г. Киев, Бульвар Дружбы Народов 5,',
            'type' => Place::TYPE_OPEN_SPACE,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Place::tableName(), [
            'id' => 5,
            'parent_id' => Place::findOne(['name' => 'Kiev Sport'])->id,
            'name' => 'Зал Бокса',
            'description' => 'Отдельный зал, оборудованный всем необходимым как для спарринговых тренировок по боксу и кикбоксингу, так и для групповых занятий.',
            'address' => 'г. Киев, Бульвар Дружбы Народов 5,',
            'type' => Place::TYPE_GYM,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Place::tableName(), [
            'id' => 6,
            'parent_id' => Place::findOne(['name' => 'Kiev Sport'])->id,
            'name' => 'Кардио Зал',
            'description' => 'Отдельный зал, где расположены более 30 кардиотренажеров: это беговые дорожки Star Track, степперы, лыжи и веломашины Stair Master. Тут можно размяться перед силовой тренировкой или получить полноценную аэробную нагрузку. Зал достаточно большой, поэтому даже в “часы пик” в очереди стоять не придется.',
            'address' => 'г. Киев, Бульвар Дружбы Народов 5,',
            'type' => Place::TYPE_GYM,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->insert(Place::tableName(), [
            'id' => 7,
            'parent_id' => Place::findOne(['name' => 'FIT HAUS'])->id,
            'name' => 'Зал Йоги',
            'description' => 'Имеются коврики, пледы и удобные подушечки для медитации. Также в вашем распоряжении позитивная атмосфера кулба FIT HAUS.',
            'address' => 'г. Киев, ул. Дмитриевская,39',
            'type' => Place::TYPE_GYM,
            'created_at' => AbstractModel::getDateTimeNow(),
            'created_by' => User::getFirstEntityId(),
        ]);

        $this->consoleLog('add clubs positions...');

        /** @var Club $club */
        $club = Place::findOne(['name' => 'FIT HAUS']);
        $positionAdmin = Position::findByName(Position::POSITION_ADMIN);
        $user = User::findOne(['username' => 'vasiliy']);

        $this->insert(StaffPositionPlace::tableName(), [
            'user_id' => $user->id,
            'place_id' => $club->id,
            'position_id' => $positionAdmin->id,
        ]);

        $club = Place::findOne(['name' => 'Kiev Sport']);
        $positionTrainer = Position::findByName(Position::POSITION_TRAINER);
        $user = User::findOne(['username' => 'andrey']);

        $this->insert(StaffPositionPlace::tableName(), [
            'user_id' => $user->id,
            'place_id' => $club->id,
            'position_id' => $positionTrainer->id,
        ]);

        $club = Place::findOne(['name' => 'FIT HAUS']);

        $this->insert(StaffPositionPlace::tableName(), [
            'user_id' => $user->id,
            'place_id' => $club->id,
            'position_id' => $positionTrainer->id,
        ]);


        $this->consoleLog('add customers to clubs...');

        /** @var Club $club */
        $club = Place::findOne(['name' => 'FIT HAUS']);
        $user = User::findOne(['username' => 'ivan']);

        $this->insert(CustomerPlace::tableName(), [
            'user_id' => $user->id,
            'place_id' => $club->id,
        ]);
        $club = Place::findOne(['name' => 'Kiev Sport']);

        $this->insert(CustomerPlace::tableName(), [
            'user_id' => $user->id,
            'place_id' => $club->id,
        ]);

    }

    public function clean()
    {
        CustomerPlace::deleteAll();
        $this->consoleLog('customer_place clean');
        StaffPositionPlace::deleteAll();
        $this->consoleLog('staff_position_place clean');
        PlaceAccessRole::deleteAll();
        Place::deleteAll();
        $this->consoleLog('place clean');
    }
}