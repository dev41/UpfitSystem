<?php
namespace app\src\entities\access;

use app\src\entities\AbstractModel;
use app\src\entities\place\Club;
use app\src\entities\place\PlaceAccessRole;
use app\src\entities\user\User;
use app\src\service\PlaceAccessRoleService;
use app\src\service\RoleService;
use yii\db\Query;

/**
 * Class AccessRole
 *
 * @property int $id
 * @property int $type
 * @property string $slug - equivalent "user_type" for User entity!
 * @property string $name
 *
 * @property AccessPermission[] $permissions
 * @property User[] $users
 */
class AccessRole extends AbstractModel
{
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_TRAINER = 'trainer';
    const ROLE_APP_USER = 'app-user';
    const ROLE_CLIENT= 'client';
    const ROLE_ADMIN = 'admin';

    const TYPE_DEFAULT = 0;
    const TYPE_SYSTEM = 1;

    const TYPE_DEFAULT_LABEL = 'Default';
    const TYPE_SYSTEM_LABEL = 'System';

    static $types = [
        self::TYPE_DEFAULT => self::TYPE_DEFAULT_LABEL,
        self::TYPE_SYSTEM => self::TYPE_SYSTEM_LABEL,
    ];

    public $clubs;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_role';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type'], 'integer'],
            [['slug'], 'unique'],
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 50],
            [['clubs'], 'safe'],
        ];
    }

    public static function getRoleBySlug(string $slug): AccessRole
    {
        return self::findOne(['slug' => $slug]);
    }

    public static function getRoleById(int $id): AccessRole
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(AccessPermission::class, ['id' => 'permission_id'])->viaTable('{{%access_role_permission}}', ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['role_id' => 'id']);
    }

    public function getClubs()
    {
        return Club::find()
            ->alias('p')
            ->leftJoin(['par' => PlaceAccessRole::tableName()], 'par.place_id = p.id')
            ->where(['par.access_role_id' => $this->id])
            ->all()
        ;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->clubs = $this->getClubs();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $roleService = new RoleService();

        $this->clubs = array_filter((array) $this->clubs);
        $roleService->setClubsToRole($this->id, $this->clubs);
    }

    public function getControlsWithTypes(): array
    {
        $roleId = (int) $this->id;

        if (!$roleId) {
            return [];
        }

        $query = new Query();
        $query
            ->from(['ar' => AccessRole::tableName()])
            ->leftJoin(['arp' => AccessRolePermission::tableName()], 'ar.id = arp.role_id')
            ->leftJoin(['ap' => AccessPermission::tableName()], 'arp.permission_id = ap.id')
            ->where(['ar.id' => $roleId])
            ->select([
                'control_id' => 'ap.control_id',
                'type' => 'ap.type',
            ])
        ;

        return $query->all();
    }
}