<?php
namespace app\src\entities\trigger;

use app\src\behaviors\I18nBehavior;
use app\src\entities\AbstractModel;
use app\src\entities\access\AccessRole;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\translate\Language;
use app\src\entities\user\Position;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use app\src\library\Query;
use app\src\service\TriggerService;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Trigger
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $event
 * @property bool $to_staff
 * @property bool $to_customers
 * @property string $template
 * @property int $sender_type
 * @property string $sender_email
 * @property string $title
 * @property int $advanced_filters
 * @property int $is_newsletter
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Trigger extends AbstractModel
{
    const TYPE_WEB_NOTIFICATION = 1;
    const TYPE_MOBILE_NOTIFICATION = 2;
    const TYPE_EMAIL_NOTIFICATION = 3;

    const SENDER_CLUB = 1;
    const SENDER_USER = 2;
    const SENDER_SYSTEM = 3;
    const SENDER_CUSTOM = 4;

    const TYPE_WEB_NOTIFICATION_LABEL = 'web notification';
    const TYPE_MOBILE_NOTIFICATION_LABEL = 'mobile notification';
    const TYPE_EMAIL_NOTIFICATION_LABEL = 'email notification';

    const SENDER_CLUB_LABEL = 'club';
    const SENDER_USER_LABEL = 'user';
    const SENDER_SYSTEM_LABEL = 'system';
    const SENDER_CUSTOM_LABEL = 'custom';

    const TYPE_LABELS = [
        self::TYPE_WEB_NOTIFICATION => self::TYPE_WEB_NOTIFICATION_LABEL,
        self::TYPE_MOBILE_NOTIFICATION => self::TYPE_MOBILE_NOTIFICATION_LABEL,
        self::TYPE_EMAIL_NOTIFICATION => self::TYPE_EMAIL_NOTIFICATION_LABEL,
    ];
    const SENDER_LABELS = [
        self::SENDER_CLUB => self::SENDER_CLUB_LABEL,
        self::SENDER_USER => self::SENDER_USER_LABEL,
        self::SENDER_SYSTEM => self::SENDER_SYSTEM_LABEL,
        self::SENDER_CUSTOM => self::SENDER_CUSTOM_LABEL,
    ];


    const EVENT_NEWSLETTER = 0;

    const EVENT_REQUEST_CUSTOMER_TO_CLUB = 1;
    const EVENT_CUSTOMER_ACCEPTED_TO_CLUB = 2;
    const EVENT_CUSTOMER_REJECTED_FROM_CLUB = 3;
    const EVENT_CHANGE_EVENT_DATE = 4;
    const EVENT_CHANGE_TRAINER = 5;
    const EVENT_CANCELED = 6;


    const EVENT_REQUEST_CUSTOMER_TO_CLUB_LABEL = 'customer request to club';
    const EVENT_CUSTOMER_ACCEPTED_TO_CLUB_LABEL = 'customer accepted to club';
    const EVENT_CUSTOMER_REJECTED_FROM_CLUB_LABEL = 'customer rejected from club';
    const EVENT_CHANGE_EVENT_DATE_LABEL = 'event date updated';
    const EVENT_CHANGE_TRAINER_LABEL = 'event trainer changed';
    const EVENT_CANCELED_LABEL = 'event canceled';
    const EVENT_NEWSLETTER_LABEL = 'newsletter';

    const EVENT_LABELS = [
        self::EVENT_NEWSLETTER => self::EVENT_NEWSLETTER_LABEL,
        self::EVENT_REQUEST_CUSTOMER_TO_CLUB => self::EVENT_REQUEST_CUSTOMER_TO_CLUB_LABEL,
        self::EVENT_CUSTOMER_ACCEPTED_TO_CLUB => self::EVENT_CUSTOMER_ACCEPTED_TO_CLUB_LABEL,
        self::EVENT_CUSTOMER_REJECTED_FROM_CLUB => self::EVENT_CUSTOMER_REJECTED_FROM_CLUB_LABEL,
        self::EVENT_CHANGE_EVENT_DATE => self::EVENT_CHANGE_EVENT_DATE_LABEL,
        self::EVENT_CHANGE_TRAINER => self::EVENT_CHANGE_TRAINER_LABEL,
        self::EVENT_CANCELED => self::EVENT_CANCELED_LABEL,
    ];

    const CODE_RECEIVER_USERNAME = 'receiver.username';
    const CODE_RECEIVER_EMAIL = 'receiver.email';
    const CODE_RECEIVER_FB_USER_ID = 'receiver.fb_user_id';
    const CODE_RECEIVER_PUSH_TOKEN = 'receiver.push_token';
    const CODE_RECEIVER_DEVICE_ID = 'receiver.device_id';
    const CODE_RECEIVER_STATUS = 'receiver.status';
    const CODE_RECEIVER_FIRST_NAME = 'receiver.first_name';
    const CODE_RECEIVER_LAST_NAME = 'receiver.last_name';
    const CODE_RECEIVER_PHONE = 'receiver.phone';
    const CODE_RECEIVER_ADDRESS = 'receiver.address';
    const CODE_RECEIVER_DESCRIPTION = 'receiver.description';
    const CODE_RECEIVER_BIRTHDAY = 'receiver.birthday';
    const CODE_RECEIVER_CREATED_AT = 'receiver.created_at';
    const CODE_RECEIVER_UPDATED_AT = 'receiver.updated_at';

    const DEFAULT_CODES = [
        self::CODE_RECEIVER_USERNAME,
        self::CODE_RECEIVER_EMAIL,
        self::CODE_RECEIVER_FB_USER_ID,
        self::CODE_RECEIVER_PUSH_TOKEN,
        self::CODE_RECEIVER_DEVICE_ID,
        self::CODE_RECEIVER_STATUS,
        self::CODE_RECEIVER_FIRST_NAME,
        self::CODE_RECEIVER_LAST_NAME,
        self::CODE_RECEIVER_PHONE,
        self::CODE_RECEIVER_ADDRESS,
        self::CODE_RECEIVER_DESCRIPTION,
        self::CODE_RECEIVER_BIRTHDAY,
        self::CODE_RECEIVER_CREATED_AT,
    ];

    const CODE_INVITE_USER_TO_CLUB_PROFILE = 'user.profile';
    const CODE_INVITE_USER_TO_CLUB_USERNAME = 'user.name';
    const CODE_INVITE_USER_TO_CLUB_DATE = 'date';
    const CODE_INVITE_USER_TO_CLUB_CLUB_NAME = 'club.name';

    const CODE_CLUB_NAME = 'club.name';
    const CODE_CLUB_DESCRIPTION = 'club.description';
    const CODE_CLUB_ADDRESS = 'club.address';
    const CODE_CLUB_CREATED_AT = 'club.created_at';
    const CODE_CLUB_UPDATED_AT = 'club.updated_at';

    const CODE_USER_USERNAME = 'user.username';
    const CODE_USER_EMAIL = 'user.email';
    const CODE_USER_FB_USER_ID = 'user.fb_user_id';
    const CODE_USER_PUSH_TOKEN = 'user.push_token';
    const CODE_USER_DEVICE_ID = 'user.device_id';
    const CODE_USER_STATUS = 'user.status';
    const CODE_USER_FIRST_NAME = 'user.first_name';
    const CODE_USER_LAST_NAME = 'user.last_name';
    const CODE_USER_PHONE = 'user.phone';
    const CODE_USER_ADDRESS = 'user.address';
    const CODE_USER_DESCRIPTION = 'user.description';
    const CODE_USER_BIRTHDAY = 'user.birthday';
    const CODE_USER_CREATED_AT = 'user.created_at';
    const CODE_USER_UPDATED_AT = 'user.updated_at';

    const CODE_COACHING_NAME = 'coaching.name';
    const CODE_COACHING_CLUBS = 'coaching.clubs';

    const CODE_EVENT_START = 'event.start';
    const CODE_EVENT_END = 'event.end';
    const CODE_EVENT_OLD_START = 'event.old_start';
    const CODE_EVENT_OLD_END = 'event.old_end';

    const EVENT_CODES = [

        self::EVENT_REQUEST_CUSTOMER_TO_CLUB => [
            self::CODE_INVITE_USER_TO_CLUB_PROFILE,
            self::CODE_INVITE_USER_TO_CLUB_USERNAME,
            self::CODE_INVITE_USER_TO_CLUB_DATE,
            self::CODE_INVITE_USER_TO_CLUB_CLUB_NAME,
        ],

        self::EVENT_CUSTOMER_ACCEPTED_TO_CLUB => [
            self::CODE_CLUB_NAME,
            self::CODE_CLUB_DESCRIPTION,
            self::CODE_CLUB_ADDRESS,
            self::CODE_CLUB_CREATED_AT,

            self::CODE_USER_USERNAME,
            self::CODE_USER_EMAIL,
            self::CODE_USER_FB_USER_ID,
            self::CODE_USER_PUSH_TOKEN,
            self::CODE_USER_DEVICE_ID,
            self::CODE_USER_STATUS,
            self::CODE_USER_FIRST_NAME,
            self::CODE_USER_LAST_NAME,
            self::CODE_USER_PHONE,
            self::CODE_USER_ADDRESS,
            self::CODE_USER_DESCRIPTION,
            self::CODE_USER_BIRTHDAY,
            self::CODE_USER_CREATED_AT,
        ],

        self::EVENT_CUSTOMER_REJECTED_FROM_CLUB => [
            self::CODE_CLUB_NAME,
            self::CODE_CLUB_DESCRIPTION,
            self::CODE_CLUB_ADDRESS,
            self::CODE_CLUB_CREATED_AT,

            self::CODE_USER_USERNAME,
            self::CODE_USER_EMAIL,
            self::CODE_USER_FB_USER_ID,
            self::CODE_USER_PUSH_TOKEN,
            self::CODE_USER_DEVICE_ID,
            self::CODE_USER_STATUS,
            self::CODE_USER_FIRST_NAME,
            self::CODE_USER_LAST_NAME,
            self::CODE_USER_PHONE,
            self::CODE_USER_ADDRESS,
            self::CODE_USER_DESCRIPTION,
            self::CODE_USER_BIRTHDAY,
            self::CODE_USER_CREATED_AT,
        ],

        self::EVENT_CHANGE_EVENT_DATE => [

            self::CODE_COACHING_NAME,
            self::CODE_COACHING_CLUBS,

            self::CODE_EVENT_START,
            self::CODE_EVENT_END,
            self::CODE_EVENT_OLD_START,
            self::CODE_EVENT_OLD_END,
        ],

        self::EVENT_CHANGE_TRAINER => [

            self::CODE_COACHING_NAME,
            self::CODE_COACHING_CLUBS,
        ],

        self::EVENT_CANCELED => [

            self::CODE_COACHING_NAME,
            self::CODE_COACHING_CLUBS,
        ],

        self::EVENT_REQUEST_CUSTOMER_TO_CLUB => [

            self::CODE_CLUB_NAME,
            self::CODE_CLUB_DESCRIPTION,
            self::CODE_CLUB_ADDRESS,
            self::CODE_CLUB_CREATED_AT,

            self::CODE_USER_USERNAME,
            self::CODE_USER_EMAIL,
            self::CODE_USER_FB_USER_ID,
            self::CODE_USER_PUSH_TOKEN,
            self::CODE_USER_DEVICE_ID,
            self::CODE_USER_STATUS,
            self::CODE_USER_FIRST_NAME,
            self::CODE_USER_LAST_NAME,
            self::CODE_USER_PHONE,
            self::CODE_USER_ADDRESS,
            self::CODE_USER_DESCRIPTION,
            self::CODE_USER_BIRTHDAY,
            self::CODE_USER_CREATED_AT,
        ],

    ];

    const ONESIGNAL_TEMPLATES = [
//        self::EVENT_REQUEST_CUSTOMER_TO_CLUB => '',
        self::EVENT_CUSTOMER_ACCEPTED_TO_CLUB => 'aad5f334-1dec-4f8a-b7a1-4d8f31c8252c',
//        self::EVENT_CUSTOMER_REJECTED_FROM_CLUB => '',
//        self::EVENT_CHANGE_EVENT_DATE => '',
//        self::EVENT_CHANGE_TRAINER => '',
//        self::EVENT_CANCELED => '',
    ];

    const ADVANCED_FILTERS_ACTIVE = true;

    public $places;

    // filter relations
    public $to_user_type;
    public $positions;
    public $roles;
    public $users;
    public $types;
    public $clubsIds;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trigger';
    }

    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => TriggerI18n::class,
            ]
        ];
    }

    public static function getTranslateEventLabels(): array
    {
        $labels = [];
        foreach (self::EVENT_LABELS as $id => $label) {
            $labels[$id] = Yii::t('app', $label);
        }

        return $labels;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'event', 'created_by', 'template', 'clubsIds', 'types'], 'required'],
            [['sender_type'], 'required', 'when' => function () {
                $result = false;
                foreach ($this->getTypes() as $type) {
                    $result = $type === self::TYPE_EMAIL_NOTIFICATION;
                }
                return $result;
            }],
            [['event', 'created_by', 'updated_by', 'sender_type'], 'integer'],
            [['name', 'template', 'sender_email', 'title'], 'string'],
            [['created_at', 'updated_at', 'types', 'places', 'to_user_type', 'to_staff', 'to_customers', 'positions', 'roles', 'users', 'advanced_filters', 'is_newsletter', 'clubsIds'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'event' => Yii::t('app', 'Event'),
            'to_staff' => Yii::t('app', 'To staff'),
            'to_customer' => Yii::t('app', 'To customer'),
            'template' => Yii::t('app', 'Template'),
            'sender_type' => Yii::t('app', 'Sender type'),
            'sender_email' => Yii::t('app', 'Sender email'),
            'title' => Yii::t('app', 'Title'),
            'advanced_filters' => Yii::t('app', 'Activate filters'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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

    public static function getUsersByTrigger(Trigger $trigger)
    {
        $query = (new Query())
            ->from(['u' => User::tableName()])

            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'spp.user_id = u.id')

            ->leftJoin(['t_place' => TriggerPlace::tableName()], 't_place.place_id = spp.place_id and t_place.type != ' . TriggerPlace::TYPE_PARENT_CLUB)
            ->leftJoin(['t_user' => TriggerUser::tableName()], 't_user.user_id = u.id')
            ->leftJoin(['t_position' => TriggerPosition::tableName()], 't_position.position_id = spp.position_id')
            ->leftJoin(['t_role' => TriggerRole::tableName()], 't_role.role_id = u.role_id')

            ->orWhere(['or',
                ['t_place.trigger_id' => $trigger->id],
                ['t_user.trigger_id' => $trigger->id],
                ['t_position.trigger_id' => $trigger->id],
                ['t_role.trigger_id' => $trigger->id],
            ])

            ->groupBy('u.id')
            ->select([
                'u.*',
                'name_label' => 'COALESCE(u.username, u.email, u.first_name, u.fb_user_id)',
            ]);


        if (!AccessChecker::isSuperAdmin()) {
            $clubsIds = ArrayHelper::map($trigger->places, 'id', 'id');
            $rolesIds = ArrayHelper::map($trigger->roles, 'id', 'id');
            $positionsIds = ArrayHelper::map($trigger->positions, 'id', 'id');

            if ($trigger->to_customers && $trigger->to_staff) {
                $query->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.user_id = u.id')
                    ->andWhere(['or',
                    ['cp.place_id' => $clubsIds],
                    ['and', ['spp.place_id' => $clubsIds], ['spp.position_id' => $positionsIds]]
                ])
                    ->andWhere(['u.role_id' => $rolesIds]);

            } elseif ($trigger->to_customers) {
                $query->orWhere(['spp.position_id' => null])
                    ->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.user_id = u.id')
                    ->andWhere(['cp.place_id' => $clubsIds])
                    ->andWhere(['u.role_id' => $rolesIds]);
            } elseif ($trigger->to_staff) {
                $query
                    ->andWhere(['spp.place_id' => $clubsIds])
                    ->andWhere(['spp.position_id' => $positionsIds]);
            } else {
                $query->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.user_id = u.id')
                    ->orWhere(['or',
                        ['cp.place_id' => $clubsIds],
                        ['spp.place_id' => $clubsIds]
                    ]);
            }
        }

        if ($trigger->users) {
            $usersId = ArrayHelper::map($trigger->users, 'id', 'id');
            $query->orWhere(['u.id' => $usersId]);
        }

        return $query->all();
    }

    public function getPlaces()
    {
        return Place::find()
            ->alias('p')
            ->leftJoin(['tp' => TriggerPlace::tableName()], 'tp.place_id = p.id')
            ->where(['tp.trigger_id' => $this->id])
            ->andWhere(['!=', 'tp.type', TriggerPlace::TYPE_PARENT_CLUB])
            ->all();
    }

    public function getClubs()
    {
        return Place::find()
            ->alias('p')
            ->leftJoin(['tp' => TriggerPlace::tableName()], 'tp.place_id = p.id')
            ->where(['tp.trigger_id' => $this->id])
            ->andWhere(['tp.type' => TriggerPlace::TYPE_PARENT_CLUB])
            ->all();
    }

    public function getPositions()
    {
        return Position::find()
            ->alias('p')
            ->leftJoin(['tp' => TriggerPosition::tableName()], 'tp.position_id = p.id')
            ->where(['tp.trigger_id' => $this->id])
            ->all();
    }

    public function getRoles()
    {
        return AccessRole::find()
            ->alias('r')
            ->leftJoin(['tr' => TriggerRole::tableName()], 'tr.role_id = r.id')
            ->where(['tr.trigger_id' => $this->id])
            ->all();
    }

    public function getUsers()
    {
        return User::find()
            ->alias('u')
            ->leftJoin(['tu' => TriggerUser::tableName()], 'tu.user_id = u.id')
            ->where(['tu.trigger_id' => $this->id])
            ->all();
    }

    public function getTypes()
    {
        return TriggerType::findAll(['trigger_id' => $this->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTriggerI18ns()
    {
        return $this->hasMany(TriggerI18n::class, ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::class, ['language' => 'language'])->viaTable('trigger_i18n', ['id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->places = $this->getPlaces();
        $this->clubsIds = $this->getClubs();

        $this->positions = $this->getPositions();
        $this->roles = $this->getRoles();
        $this->users = $this->getUsers();
        $this->types = $this->getTypes();

        $this->to_user_type = [];

        if ($this->to_staff) {
            $this->to_user_type[] = 'to_staff';
        }
        if ($this->to_customers) {
            $this->to_user_type[] = 'to_customers';
        }
    }

    public function beforeSave($insert)
    {
        $this->to_user_type = array_flip(array_filter((array) $this->to_user_type));

        $this->to_staff = isset($this->to_user_type['to_staff']);
        $this->to_customers = isset($this->to_user_type['to_customers']);

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $triggerService = new TriggerService();

        $this->places = array_filter((array) $this->places);
        $triggerService->setPlaces($this->id, $this->places);

        $this->positions = array_filter((array) $this->positions);
        $triggerService->setPositions($this->id, $this->positions);

        $this->roles = array_filter((array) $this->roles);
        $triggerService->setRoles($this->id, $this->roles);

        $this->users = array_filter((array) $this->users);
        $triggerService->setUsers($this->id, $this->users);

        $triggerService->setTypes($this->id, $this->types);
        $triggerService->setClubs($this->id, $this->clubsIds);
    }
}