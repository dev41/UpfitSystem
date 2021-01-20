<?php
namespace app\src\service;

use app\src\entities\access\AccessControl;
use app\src\entities\access\AccessPermission;

/**
 * Class AccessPermissionService
 */
class AccessPermissionService extends AbstractService
{
    public function createForControl(AccessControl $control): array
    {
        $permissions = [];

        switch ($control->type) {
            case AccessControl::TYPE_ACTION:
            case AccessControl::TYPE_CUSTOM:
                $permission = new AccessPermission();
                $permission->control_id = $control->id;
                $permission->type = AccessPermission::TYPE_CUSTOM;
                $permission->save();

                $permissions[] = $permission;
                break;
            default:
                $types = array_values(AccessPermission::$actionIdTypes);

                foreach ($types as $type) {
                    $permission = new AccessPermission();
                    $permission->control_id = $control->id;
                    $permission->type = $type;
                    $permission->save();

                    $permissions[] = $permission;
                }
        }

        return $permissions;
    }
}