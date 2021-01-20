<?php
namespace app\src\service;

use app\src\entities\access\ {
    AccessPermission,
    AccessRole,
    AccessRolePermission
};

use app\src\entities\place\PlaceAccessRole;
use app\src\library\AccessChecker;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class RoleService
 */
class RoleService extends AbstractService
{
    public function createByData(array $data, array $accessControlMatrix = []): AccessRole
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $role = new AccessRole();
            $role->load($data);

            $role->save();

            $permissions = $this->getPermissionsByAccessControlMatrix($accessControlMatrix);
            $permissionIds = ArrayHelper::map($permissions, 'id', 'id');

            $this->setPermissionsToRole($role, $permissionIds);

            $transaction->commit();

        } catch (Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $role;
    }

    public function updateByData(AccessRole $role, array $data, array $accessControlMatrix = []): AccessRole
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $role->load($data);

            $role->save();

            $permissions = $this->getPermissionsByAccessControlMatrix($accessControlMatrix);
            $permissionIds = ArrayHelper::map($permissions, 'id', 'id');

            $this->setPermissionsToRole($role, $permissionIds);

            $transaction->commit();

        } catch (Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $role;
    }

    public function getPermissionsByAccessControlMatrix(array $controls): array
    {
        if (empty($controls)) {
            return [];
        }

        $query = (new Query())->from(['ap' => AccessPermission::tableName()]);

        foreach ($controls as $controlId => $types) {
            $query->orWhere('control_id = ' . $controlId . ' AND type IN (' . implode(',', $types) . ')');
        }

        return $query->all();
    }

    public function setPermissionsToRole(AccessRole $role, array $permissionIds = [])
    {
        AccessRolePermission::deleteAll([
            'role_id' => $role->id,
        ]);

        $accessRoles = [];
        foreach ($permissionIds as $permissionId) {
            $accessRole = new AccessRolePermission();
            $accessRole->role_id = $role->id;
            $accessRole->permission_id = $permissionId;
            $accessRoles[] = $accessRole;
        }

        AccessRolePermission::batchInsertByModels($accessRoles);
    }

    public function setClubsToRole(int $roleId, array $clubIds = [])
    {
        PlaceAccessRole::deleteAll([
            'access_role_id' => $roleId,
        ]);

        if (!$clubIds) {
            return;
        }

        $placeAccessRoles = [];
        foreach ($clubIds as $clubId) {
            $placeAccessRole = new PlaceAccessRole();
            $placeAccessRole->access_role_id = $roleId;
            $placeAccessRole->place_id = $clubId;

            $placeAccessRoles[] = $placeAccessRole;
        }

        PlaceAccessRole::batchInsertByModels($placeAccessRoles);
    }

    public function getControlsWithTypesByRole(AccessRole $role): array
    {
        $controls = $role->getControlsWithTypes();

        $groupControls = [];
        foreach ($controls as $control) {
            $controlId = $control['control_id'];
            if (!array_key_exists($controlId, $groupControls)) {
                $groupControls[$controlId] = [];
            }
            $groupControls[$controlId][] = $control['type'];
        }

        return $groupControls;
    }

    public function getAvailableRolesForSetting()
    {
        return  AccessChecker::isSuperAdmin() ?
            AccessRole::getAll() :
            AccessRole::find()->where(['!=', 'slug', AccessRole::ROLE_SUPER_ADMIN])->all();
    }
}