<?php
namespace app\src\entities\access;

use app\src\entities\AbstractModel;

/**
 * Class AccessRolePermission
 *
 * @property int $role_id
 * @property int $permission_id
 */
class AccessRolePermission extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_role_permission';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'permission_id' => 'Permission ID',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'permission_id'], 'integer'],
            [['role_id', 'permission_id'], 'required'],
        ];
    }
}