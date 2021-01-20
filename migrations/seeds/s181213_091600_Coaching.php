<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\AbstractModel;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use app\src\entities\user\UserCoaching;

class s181213_091600_Coaching extends BaseMigration implements ISeeder
{
    public function seed()
    {
        $this->consoleLog('add coaching levels...');

        $this->batchInsert(CoachingLevel::tableName(), ['name'], [
            ['easy',],
            ['normal',],
            ['hard',],
        ]);

        $this->consoleLog('create coaching...');

        $this->batchInsert(Coaching::tableName(),
            ['name', 'description', 'price', 'capacity', 'coaching_level_id', 'created_at', 'created_by'],
            [
                [
                    'Йога',
                    'Занятия ведет преподаватель хатха йоги, медицинский психолог Евгения Танковская.',
                    '0',
                    '30',
                    CoachingLevel::getFirstEntityId(),
                    AbstractModel::getDateTimeNow(),
                    User::getFirstEntityId(),
                ],

                [
                    'Boxaerobics',
                    'Отрабатывая на мешках технику удара, нужно двигаться не только правильно, но и красиво! Это тяжело, но очень круто – выходишь как победитель после настоящего боя',
                    '0',
                    '15',
                    CoachingLevel::findOne(['name' => 'hard'])->id,
                    AbstractModel::getDateTimeNow(),
                    User::getFirstEntityId(),

                ]
            ]
        );

        $this->consoleLog('add coaching to places...');

        /** @var Place $placePull */
        $fitHausClub = Place::findOne(['name' => 'FIT HAUS']);
        $fitHausPlace = Place::findOne(['name' => 'Зал Йоги']);

        $kievSportClub = Place::findOne(['name' => 'Kiev Sport']);
        $placeBox = Place::findOne(['name' => 'Зал Бокса']);

        $this->batchInsert(CoachingPlace::tableName(), ['coaching_id', 'place_id', 'place_type'], [
            [Coaching::getFirstEntityId(), $fitHausClub->id, Place::TYPE_CLUB],
            [Coaching::getFirstEntityId(), $fitHausPlace->id, Place::TYPE_SUB_PLACE],
            [Coaching::findOne(['name' => 'Boxaerobics'])->id, $kievSportClub->id, Place::TYPE_CLUB],
            [Coaching::findOne(['name' => 'Boxaerobics'])->id, $placeBox->id, Place::TYPE_SUB_PLACE],
        ]);

        $this->consoleLog('add trainers to coaching...');

        /** @var Coaching $coaching */
        $coaching = Coaching::find()->one();
        $user = User::findOne(['username' => 'andrey']);

        $this->insert(UserCoaching::tableName(), [
            'user_id' => $user->id,
            'coaching_id' => $coaching->id,
        ]);
    }

    public function clean()
    {
        UserCoaching::deleteAll();
        $this->consoleLog('user_coaching clean');
        CoachingPlace::deleteAll();
        $this->consoleLog('coaching_place clean');
        Coaching::deleteAll();
        $this->consoleLog('coaching clean');
        CoachingLevel::deleteAll();
        $this->consoleLog('coaching_level clean');
    }
}