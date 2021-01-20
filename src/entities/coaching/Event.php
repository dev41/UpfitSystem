<?php
namespace app\src\entities\coaching;

use app\src\entities\AbstractModel;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\user\User;
use app\src\entities\user\UserCoaching;
use app\src\entities\user\UserEvent;
use app\src\library\AccessChecker;
use app\src\library\Query;

/**
 * @property int $id
 * @property int $coaching_id
 * @property string $start
 * @property string $end
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Coaching $coaching
 * @property User $createdBy
 * @property User $updatedBy
 * @property User[] $users
 */
class Event extends AbstractModel
{
    public $users;

    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start', 'end', 'coaching_id', 'created_by'], 'required'],
            [['coaching_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'users'], 'safe'],
            [['coaching_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coaching::class, 'targetAttribute' => ['coaching_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coaching_id' => 'Coaching ID',
            'start' => 'Start',
            'end' => 'End',
            'users' => 'Users',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        Coaching::deleteAll(['id' => $this->coaching_id]);
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoaching()
    {
        return $this->hasOne(CopyCoaching::class, ['id' => 'coaching_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable(UserEvent::tableName(), ['event_id' => 'id'])->all();
    }

    protected static function getFCEventSql(int $clubId = null, int $coachingId = null)
    {
        $query = new Query();
        $query
            ->from(['e' => self::tableName()])
            ->leftJoin(['c' => Coaching::tableName()], 'e.coaching_id = c.id')
            ->leftJoin(['cl' => CoachingLevel::tableName()], 'c.coaching_level_id = cl.id')
            ->leftJoin(['pc' => Coaching::tableName()], 'pc.id = c.parent_id')
            ->leftJoin(['pcl' => CoachingLevel::tableName()], 'pc.coaching_level_id = pcl.id')

            ->leftJoin(['uc' => UserCoaching::tableName()], 'uc.coaching_id = c.id')
            ->leftJoin(['u' => User::tableName()], 'u.id = uc.user_id')

            ->leftJoin(['puc' => UserCoaching::tableName()], 'uc.coaching_id = pc.id')
            ->leftJoin(['pu' => User::tableName()], 'u.id = puc.user_id')

            ->leftJoin(['ue' => UserEvent::tableName()], 'ue.event_id = e.id')
            ->leftJoin(['customer' => User::tableName()], 'ue.user_id = customer.id')

            ->leftJoin(['cp' => CoachingPlace::tableName()], 'cp.coaching_id = c.id')
            ->leftJoin(['club' => Place::tableName()], 'cp.place_id = club.id AND club.type = :place_type',
                ['place_type' => Place::TYPE_CLUB]
            )
            ->leftJoin(
                ['p' => Place::tableName()],
                'cp.place_id = p.id AND (p.type != :club)',
                [
                    'club' => Place::TYPE_CLUB,
                ]
            )
            ->andWhere(['or',
                ['u.status' => User::STATUS_ACTIVE],
                ['u.id' => NULL]
            ])
            ->andWhere(['or',
                ['customer.status' => User::STATUS_ACTIVE],
                ['customer.id' => NULL]
            ])->select([
                'event_id' => 'e.id',
                'clubNames' => 'GROUP_CONCAT(DISTINCT club.name SEPARATOR ", ")',
                'placeNames' => 'GROUP_CONCAT(DISTINCT p.name SEPARATOR ", ")',
                'trainerNames' => 'COALESCE(
                GROUP_CONCAT(DISTINCT ' . self::getSQLUserTitlePriority('u') . ' SEPARATOR ", "),
                GROUP_CONCAT(DISTINCT ' . self::getSQLUserTitlePriority('pu') . ' SEPARATOR ", ")
                )',
                'start' => 'e.start',
                'end' => 'e.end',
                'title' => 'COALESCE(c.name, pc.name)',
                'description' => 'COALESCE(c.description, pc.description)',
                'level' => 'COALESCE(cl.name, pcl.name)',
                'price' => 'COALESCE(c.price, pc.price)',
                'color' => 'COALESCE(c.color, pc.color)',
                'capacity' => 'COALESCE(c.capacity, pc.capacity)',
                'customers' => 'GROUP_CONCAT(DISTINCT ' . self::getSQLUserTitlePriority('customer') . ' SEPARATOR ", ")'
            ])
            ->groupBy('e.id');

        if ($clubId) {
            $query->andWhere(['club.id' => $clubId]);
        }

        if ($coachingId) {
            $query->andWhere(['c.parent_id' => $coachingId]);
        }

        if (!AccessChecker::isSuperAdmin()) {
            $query->innerJoin(['spp' => StaffPositionPlace::tableName()],
                'spp.place_id = club.id AND spp.user_id = :spp_user_id', [
                    'spp_user_id' => \Yii::$app->user->id,
            ]);
        }

        return $query;
    }

    public static function getFCEventById(int $eventId)
    {
        $query = self::getFCEventSql()
            ->where([
                'e.id' => $eventId
            ])
        ;

        return $query->one();
    }

    public static function getFCEvents(int $clubId = null)
    {
        $query = self::getFCEventSql($clubId);
        return $query->all();
    }

    public static function getFCEventsByCoachingId(int $coachingId)
    {
        $query = self::getFCEventSql(null, $coachingId);
        return $query->all();
    }

    public function afterFind()
    {
        $this->users = $this->getUsers();
        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

//        $users = array_filter((array) $this->users);
//        $coachingService->setPlaces($this->id, Place::TYPE_NOT_CLUB, $places);
    }
}