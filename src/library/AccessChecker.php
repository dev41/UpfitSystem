<?php
namespace app\src\library;

use app\src\entities\access\AccessControl;
use app\src\entities\access\AccessPermission;
use app\src\entities\access\AccessRole;
use app\src\entities\access\AccessRolePermission;
use app\src\entities\user\User;
use yii\helpers\ArrayHelper;

/**
 * Class AccessControl
 */
class AccessChecker
{
    public static $controllerActionMap = [];

    public static function loadAliasMaps()
    {
        self::$controllerActionMap = \Yii::$app->params['accessControllerActionMap'] ?? [];
    }

    public static function checkRole(int $slug, $user = null): bool
    {
        $role = AccessRole::getRoleBySlug($slug);
        /** @var User $user */
        $user = $user ?? \Yii::$app->user;

        if (!$user || !$role) {
            return false;
        }

        return $user->role_id === $role->id;
    }

    /**
     * @param User|null $user
     * @return AccessRole|null
     * @throws \Exception
     * @throws \Throwable
     */
    public static function getRole(User $user = null)
    {
        $user = $user ?? \Yii::$app->user->getIdentity();
        return $user ? $user->role : null;
    }

    public static function isSuperAdmin(User $user = null): bool
    {
        $role = self::getRole($user);
        return $role ? $role->slug === AccessRole::ROLE_SUPER_ADMIN : false;
    }

    public static function hasAccess(
        string $controlSlug,
        string $controlType = AccessControl::TYPE_CONTROLLER,
        int $permissionType = AccessPermission::TYPE_READ,
        User $user = null
    ) {
        $control = AccessControl::findOne([
            'slug' => $controlSlug,
            'type' => $controlType,
        ]);

        // if you need to check access to control - then create this control
        if (!$control) {
            return true;
        }

        // public control does not need to be checked
        if ($control->access_type === AccessControl::ACCESS_TYPE_PUBLIC) {
            return true;
        }

        $role = self::getRole($user);

        // a user without a role cannot access to an exist control
        if (!$role) {
            return false;
        }

        // the role super admin can do anything
        if ($role->slug === AccessRole::ROLE_SUPER_ADMIN) {
            return true;
        }

        $permissionWhere = ['control_id' => $control->id];
        if ($permissionType !== AccessPermission::TYPE_ANY) {
            $permissionWhere = $permissionWhere + ['type' => $permissionType];
        }
        $permissions = AccessPermission::findAll($permissionWhere);

        // each control must have at least one permission
        if (empty($permissions)) {
            if ($control->parent_id) {
                $parentControl = $control->parent;
                // check access to parent control with any permissions
                return self::hasAccess($parentControl->slug, $parentControl->type, AccessPermission::TYPE_ANY);
            }
            return false;
        }

        $permissionIds = ArrayHelper::map($permissions, 'id', 'id');

        $rolePermission = AccessRolePermission::find()
            ->where(['role_id' => $role->id])
            ->andWhere(['in', 'permission_id', $permissionIds])
            ->one();

        // is there a permission for this role?
        return (bool) $rolePermission;
    }

    public static function hasActionAccess(string $slug, User $user = null): bool
    {
        if (isset(self::$controllerActionMap[$slug])) {
            return self::hasPermission(self::$controllerActionMap[$slug], $user);
        }

        return self::hasAccess($slug, AccessControl::TYPE_ACTION, AccessPermission::TYPE_CUSTOM, $user);
    }

    public static function hasControllerAccess(string $slug, int $permissionType, User $user = null): bool
    {
        return self::hasAccess($slug, AccessControl::TYPE_CONTROLLER, $permissionType, $user);
    }

    public static function hasControllerActionAccess(string $controllerId, string $actionId, User $user = null): bool
    {
        if (isset(self::$controllerActionMap[$controllerId . '.' . $actionId])) {
            return self::hasPermission(self::$controllerActionMap[$controllerId . '.' . $actionId], $user);
        }

        // try to get permission type
        if (!isset(AccessPermission::$actionIdTypes[$actionId])) {

            /** @var AccessControl $controllerControl */
            $controllerControl = AccessControl::find()
                ->where([
                    'slug' => $controllerId,
                    'type' => AccessControl::TYPE_CONTROLLER,
                ])->one();

            // action cannot be without the controller control.
            // need to create controller control first!
            if (!$controllerControl) {
                return true;
            }

            $actionControl = AccessControl::find()
                ->where([
                    'slug' => $actionId,
                    'type' => AccessControl::TYPE_ACTION,
                    'parent_id' => $controllerControl->id,
                ])->one();

            // special action was not created for this controller control.
            if (!$actionControl) {
                // set default permission type
                if ($actionId === AccessPermission::ACTION_ID_VIEW) {
                    $permissionType = AccessPermission::TYPE_READ;
                } else {
                    $permissionType = AccessPermission::TYPE_UPDATE;
                }
                // check access to the $controllerId with permission UPDATE
                return self::hasControllerAccess($controllerId, $permissionType, $user);
            }

            // check access to special action
            return self::hasActionAccess($actionId, $user);
        }

        // check access to standard action by $controllerId and permission type
        $permissionType = AccessPermission::$actionIdTypes[$actionId];
        return self::hasControllerAccess($controllerId, $permissionType, $user);
    }

    public static function hasPermission(string $permission, User $user = null): bool
    {
        $permission = explode('.', $permission);

        if (count($permission) >= 2) {
            return self::hasControllerActionAccess($permission[0], $permission[1], $user);
        }

        return self::hasActionAccess($permission[0], $user);
    }

}

AccessChecker::loadAliasMaps();