<?php
namespace app\src\entities\user;

use app\src\entities\AbstractModel;
use app\src\entities\access\AccessRole;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\Event;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\staff\StaffPositionPlace;
use app\src\library\ApiApplication;
use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $api_auth_key
 * @property string $fb_user_id
 * @property string $fb_token
 * @property string $push_token
 * @property string $device_id
 * @property int $status
 * @property int $role_id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $address
 * @property string $description
 * @property string $birthday
 * @property string $avatar
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Coaching[] $coachingsCreate
 * @property Coaching[] $coachingsUpdate
 * @property Event[] $eventsCreate
 * @property Event[] $eventsUpdate
 * @property Place[] $placesCreate
 * @property Place[] $placesUpdate
 * @property User $createdBy
 * @property User[] $users
 * @property AccessRole $role
 * @property User $updatedUser
 * @property User[] $usersUpdatedBy
 */
class User extends AbstractModel implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const MODEL_STAFF = 1;
    const MODEL_CUSTOMER = 2;
    const MODEL_FBUser = 3;

    public $password;
    public $confirm_password;

    const AUTH_EXPIRE = 3600*24*30;

    const VALIDATE_LOGIN = 1;
    const VALIDATE_CREATE = 2;
    const VALIDATE_LOGIN_BY_FB = 3;
    const VALIDATE_CREATE_BY_FB = 4;

    const VALIDATE_UPDATE_FORM = 5;

    public $images;
    public $deleteImages;
    public $logo;
    public $deleteLogo;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['username'], 'required',
                'message' => Yii::t('app', 'Fill out the email or name'),
                'when' => function () {
                    return (
                            $this->validateBehaviour === self::VALIDATE_UPDATE_FORM ||
                            $this->validateBehaviour === self::VALIDATE_CREATE ||
                            $this->validateBehaviour === self::VALIDATE_LOGIN
                        )
                        && empty($this->email);
                },
                'whenClient' => 'function(attribute, value) {
                    var emailInput = ' . $this->findInputOnClientSideByName('email') . '
                    return !value && !emailInput.val();
                }',
            ],
            [['email'], 'required',
                'message' => Yii::t('app', 'Fill out the email or name'),
                'when' => function () {
                    return (
                            $this->validateBehaviour === self::VALIDATE_UPDATE_FORM ||
                            $this->validateBehaviour === self::VALIDATE_CREATE ||
                            $this->validateBehaviour === self::VALIDATE_LOGIN
                        )
                        && empty($this->username);
                },
                'whenClient' => 'function(attribute, value) {
                    var usernameInput = ' . $this->findInputOnClientSideByName('username') . '
                    return !value && !usernameInput.val();
                }',
            ],
            [['password'], 'required', 'when' => function () {
                return $this->validateBehaviour === self::VALIDATE_LOGIN
                    || $this->validateBehaviour === self::VALIDATE_CREATE;
            }],
            [['confirm_password'], 'required', 'when' => function () {
                return !(Yii::$app instanceof ApiApplication)
                    && (
                        $this->validateBehaviour === self::VALIDATE_LOGIN
                        || $this->validateBehaviour === self::VALIDATE_CREATE
                    );
            }],
            [['confirm_password'], 'compare',
                'compareAttribute' => 'password',
                'message' => 'Passwords don\'t match',
                'when' => function () {
                    return !(Yii::$app instanceof ApiApplication)
                        && (
                            $this->validateBehaviour === self::VALIDATE_LOGIN
                            || $this->validateBehaviour === self::VALIDATE_CREATE
                        );
            }],
            [['password'], 'string', 'min' => 6,],
            [['fb_token'], 'required', 'when' => function () {
                return $this->validateBehaviour === self::VALIDATE_CREATE_BY_FB
                    || $this->validateBehaviour === self::VALIDATE_LOGIN_BY_FB;
            }],
            [['fb_user_id'], 'required', 'when' => function () {
                return $this->validateBehaviour === self::VALIDATE_CREATE_BY_FB
                    || $this->validateBehaviour === self::VALIDATE_LOGIN_BY_FB;
            }],
            [['created_at', 'created_by'], 'required'],
            [['first_name', 'last_name', 'fb_user_id'], 'string', 'max' => 50],
            [['push_token', 'device_id'], 'string'],// 'max' => 50], @todo e.p. check API or ask Eugeniy
            [['username', 'first_name', 'last_name', 'fb_user_id'], 'string', 'max' => 50],
            [['password_hash', 'password_reset_token', 'email', 'address'], 'string', 'max' => 100],
            [['auth_key', 'api_auth_key'], 'string', 'max' => 32],
            [['status', 'role_id', 'created_by', 'updated_by'], 'integer'],
            [['address', 'password_hash', 'password_reset_token', 'email', 'fb_token'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['birthday', 'created_at', 'updated_at', 'description', 'avatar', 'images'], 'safe'],
            [['username', 'email', 'password_reset_token'], 'unique'],
        ];
        return $rules;
    }

    public function setEmail($value)
    {
        $this->email = $value ?? null;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'fullname' => Yii::t('app', 'Fullname'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),

            'api_auth_key' => Yii::t('app', 'Api Auth Key'),
            'fb_user_id' => Yii::t('app', 'FB user ID'),
            'fb_token' => Yii::t('app', 'FB token'),
            'push_token' => Yii::t('app', 'Push token'),
            'device_id' => Yii::t('app', 'Device ID'),
            'status' => Yii::t('app', 'Status'),
            'role_id' => Yii::t('app', 'Role ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'phone' => Yii::t('app', 'Phone'),
            'address' => Yii::t('app', 'Address'),
            'description' => Yii::t('app', 'Description'),
            'birthday' => Yii::t('app', 'Birthday'),
            'avatar' => Yii::t('app', 'Avatar'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return static
     * @throws \yii\base\Exception
     */
    public static function create(string $username, string $email, string $password): User
    {
        $user = new static();

        $user->username = $username;
        $user->email = $email;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->status = self::STATUS_ACTIVE;

        $user->created_at = self::getDateTimeNow();
        $user->generateAuthKey();

        return $user;
    }

    public static function findByUsername(string $username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = str_replace('_', '', Yii::$app->security->generateRandomString())
            . '_' . base64_encode((string) microtime(true));
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function resetPassword(string $password)
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested.');
        }

        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateApiAuthKey()
    {
        $this->api_auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['auth_key' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoachingsCreate()
    {
        return $this->hasMany(Coaching::class, ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoachingsUpdate()
    {
        return $this->hasMany(Coaching::class, ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventsCreate()
    {
        return $this->hasMany(Event::class, ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventsUpdate()
    {
        return $this->hasMany(Event::class, ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacesCreate()
    {
        return $this->hasMany(Place::class, ['created_by' => 'id']);
    }

    public static function getNameById($id): string
    {
        $user = self::findOne(['id' => $id]);
        return $user ? $user->username : '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacesUpdate()
    {
        return $this->hasMany(Place::class, ['updated_by' => 'id']);
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
    public function getUsers()
    {
        return $this->hasMany(User::class, ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(AccessRole::class, ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedUser()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersUpdatedBy()
    {
        return $this->hasMany(User::class, ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['id' => 'event_id'])->viaTable('user_event', ['user_id' => 'id']);
    }

    /**
     * @return static[]
     */
    public static function getOwnerList()
    {
        return self::find()->all();
    }

    /**
     * @param string $clubId
     * @return array
     */
    public static function getTrainers($clubId = ''): array
    {
        /** @var Query $query */
        $query =  User::find()
            ->from(['u' => User::tableName()])
            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'spp.user_id = u.id')
            ->leftJoin(['p' => Position::tableName()], 'spp.position_id = p.id')
            ->andWhere([
                'p.name' => Position::POSITION_TRAINER,
                'u.status' => User::STATUS_ACTIVE,
            ]);
        if ($clubId) {
            $query = $query->andWhere([
                'place_id' => $clubId
            ]);
        }

        return $query->all();
    }

    public static function getCustomers($clubId = ''): array
    {
        $query = User::find()
            ->from(['u' => User::tableName()])
            ->leftJoin(['cp' => CustomerPlace::tableName()], 'cp.user_id = u.id')
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->select([
                'u.*',
                'username' => self::getSQLUserTitlePriority('u'),
            ]);
        if ($clubId) {
            $query = $query->andWhere([
                'cp.status' => CustomerPlace::STATUS_CONFIRMED,
                'place_id' => $clubId
            ]);
        }
        return  $query->all();
    }

    public static function getStaff($clubId = ''): array
    {
        $query = User::find()
            ->from(['u' => User::tableName()])
            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'spp.user_id = u.id')
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->select([
                'u.*',
                'username' => self::getSQLUserTitlePriority('u'),
            ]);
        if ($clubId) {
            $query = $query->andWhere([
                'place_id' => $clubId
            ]);
        }
        return  $query->all();
    }

    public function getImages()
    {
        return $this->hasMany(Image::class, ['parent_id' => 'id'])->where([ 'type' => Image::TYPE_USER_PHOTO])->all();
    }

    public function getAvatar()
    {
        return $this->hasOne(Image::class, ['parent_id' => 'id'])->where(['type' => Image::TYPE_USER_AVATAR])->one();
    }

    public function getImageField()
    {
        return [$this->logo];
    }

    /**
     * @param string $value
     * @return null|User
     */
    public static function findByUserNameOrEmail(string $value)
    {
        /** @var User $user */
        $user = self::find()
            ->where(['username' => $value])
            ->orWhere(['email' => $value])
            ->one();
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        if ($this->isNewRecord) {
            return $fields;
        }

        return $fields;
    }

    public function beforeSave($insert)
    {
        if ($this->email === '') {
            $this->email = null;
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

//        $userTypeService = new PositionService();
//
//        $positions = array_filter((array) $this->positions);
//        $userTypeService->setUserPositions($this->id, $positions);
    }

    public static function getUserInfoById(int $userId)
    {
        return [
            'clubs' => CustomerPlace::findAll(['user_id' => $userId]),
        ];
    }

    public static function isUsernameExist(string $username): bool
    {
        $user = User::findOne(['username' => $username]);
        return isset($user);
    }

    public static function isEmailExist($email): bool
    {
        $user = User::findOne(['email' => $email]);
        return isset($user);
    }

    public function afterFind()
    {
        $this->images = $this->getImages();
        $this->logo = $this->getAvatar();
        parent::afterFind();
    }
}
