<?php
namespace app\src\entities\access;

use app\src\entities\AbstractModel;

/**
 * Class AccessPermission
 *
 * @property int $id
 * @property int $type
 * @property int $control_id
 *
 * @property AccessControl $control
 * @property AccessRole[] $roles
 */
class AccessPermission extends AbstractModel
{
    const TYPE_READ = 1;
    const TYPE_CREATE = 2;
    const TYPE_UPDATE = 3;
    const TYPE_DELETE = 4;
    const TYPE_CUSTOM = 5;

    // special type: not used to save to the database
    const TYPE_ANY = 100;

    const TYPE_READ_LABEL = 'index';
    const TYPE_CREATE_LABEL = 'create';
    const TYPE_UPDATE_LABEL = 'update';
    const TYPE_DELETE_LABEL = 'delete';
    const TYPE_CUSTOM_LABEL = 'active';

    public static $typeLabels = [
        self::TYPE_READ => self::TYPE_READ_LABEL,
        self::TYPE_CREATE => self::TYPE_CREATE_LABEL,
        self::TYPE_UPDATE => self::TYPE_UPDATE_LABEL,
        self::TYPE_DELETE => self::TYPE_DELETE_LABEL,
        self::TYPE_CUSTOM => self::TYPE_CUSTOM_LABEL,
    ];

    const ACTION_ID_READ = 'index';
    const ACTION_ID_VIEW = 'view';
    const ACTION_ID_CREATE = 'create';
    const ACTION_ID_UPDATE = 'update';
    const ACTION_ID_DELETE = 'delete';

    public static $actionIdTypes = [
        self::ACTION_ID_READ => self::TYPE_READ,
        self::ACTION_ID_CREATE => self::TYPE_CREATE,
        self::ACTION_ID_UPDATE => self::TYPE_UPDATE,
        self::ACTION_ID_DELETE => self::TYPE_DELETE,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_permission';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'control_id' => 'Control ID',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'control_id'], 'integer'],
            [['type', 'type', 'control_id'], 'required'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getControl()
    {
        return $this->hasOne(AccessControl::class, ['id' => 'control_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(AccessRole::class, ['id' => 'role_id'])->viaTable('{{%access_role_permission}}', ['permission_id' => 'id']);
    }
}