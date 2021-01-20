<?php
namespace app\migrations\seeds;

use app\src\BaseMigration;
use app\src\entities\access\AccessControl;
use app\src\entities\access\AccessPermission;
use app\src\entities\access\AccessRole;
use app\src\entities\access\AccessRolePermission;
use app\src\service\AccessControlService;
use app\src\service\RoleService;
use yii\helpers\ArrayHelper;

class s181213_085949_Access extends BaseMigration implements ISeeder
{
    public function seed()
    {
        $this->consoleLog('create roles...');

        $this->batchInsert(AccessRole::tableName(), ['type', 'name', 'slug'], [
            [AccessRole::TYPE_SYSTEM, 'Super Admin', AccessRole::ROLE_SUPER_ADMIN],
            [AccessRole::TYPE_DEFAULT, 'Admin', 'admin'],
            [AccessRole::TYPE_DEFAULT, 'Manager', 'manager'],
            [AccessRole::TYPE_SYSTEM, 'Trainer', AccessRole::ROLE_TRAINER],
            [AccessRole::TYPE_DEFAULT, 'Client', 'client'],
            [AccessRole::TYPE_DEFAULT, 'App-user', AccessRole::ROLE_APP_USER],
        ]);

        $this->consoleLog('create access controls...');

        $this->batchInsert(AccessControl::tableName(), ['name', 'slug', 'type', 'access_type'], [
            ['Clubs', 'club', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['News', 'news', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Sale', 'sale', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Activity', 'activity', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Places', 'place', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Staff', 'staff', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Customers', 'customer', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Training sessions', 'coaching', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Schedule', 'schedule', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Triggers', 'trigger', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Roles and permissions', 'role', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
            ['Translation', 'translation', AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PERMISSION],
        ]);

        $this->consoleLog('create permissions...');

        $controls = AccessControl::findAll([
            'type' => AccessControl::TYPE_CONTROLLER,
        ]);

        $permissionControls = [];
        foreach ($controls as $control) {
            $permissionControls[] = [AccessPermission::TYPE_READ,   $control->primaryKey];
            $permissionControls[] = [AccessPermission::TYPE_CREATE, $control->primaryKey];
            $permissionControls[] = [AccessPermission::TYPE_UPDATE, $control->primaryKey];
            $permissionControls[] = [AccessPermission::TYPE_DELETE, $control->primaryKey];
        }

        $this->batchInsert(AccessPermission::tableName(), ['type', 'control_id'], $permissionControls);

        $trainerRole = AccessRole::findOne(['slug' => AccessRole::ROLE_TRAINER]);
        $clientRole = AccessRole::findOne(['slug' => AccessRole::ROLE_CLIENT]);
        $appUserRole = AccessRole::findOne(['slug' => AccessRole::ROLE_APP_USER]);
        $adminRole = AccessRole::findOne(['slug' => AccessRole::ROLE_ADMIN]);
        $coachingControl = AccessControl::findOne(['slug' => 'coaching']);
        $scheduleControl = AccessControl::findOne(['slug' => 'schedule']);
        $staffControl = AccessControl::findOne(['slug' => 'staff']);
        $roleControl = AccessControl::findOne(['slug' => 'role']);
        $triggerControl = AccessControl::findOne(['slug' => 'trigger']);
        $translationControl = AccessControl::findOne(['slug' => 'translation']);
        $customersControl = AccessControl::findOne(['slug' => 'customer']);

        //access for trainer
        $readPermissionsTrainer = AccessPermission::find()
            ->where(['type' => AccessPermission::TYPE_READ])
            ->andWhere(['not in', 'control_id', [
                $staffControl->id,
                $roleControl->id,
                $triggerControl->id,
                $translationControl->id,
                $customersControl->id
            ]])->all();
        $permissionIds = ArrayHelper::map($readPermissionsTrainer, 'id', 'id');

        $coachingPermissions = AccessPermission::find()
            ->where(['control_id' => $coachingControl->id])
            ->andWhere(['!=','type', AccessPermission::TYPE_READ])->all();
        $permissionCoachingIds = ArrayHelper::map($coachingPermissions, 'id', 'id');

        $schedulePermissions =AccessPermission::find()
            ->where(['control_id' => $scheduleControl->id])
            ->andWhere(['!=','type', AccessPermission::TYPE_READ])->all();
        $permissionScheduleIds = ArrayHelper::map($schedulePermissions, 'id', 'id');
        $permissionIds = array_merge($permissionIds, $permissionCoachingIds, $permissionScheduleIds);

        //access for app-user
        $permissionForAppUser = AccessPermission::find()
            ->where(['type' => AccessPermission::TYPE_READ])
            ->andWhere(['not in', 'control_id', [
                $staffControl->id,
                $roleControl->id,
                $triggerControl->id,
                $translationControl->id,
                $customersControl->id,
                $scheduleControl->id,
            ]])->all();
        $permissionAppUser= ArrayHelper::map($permissionForAppUser, 'id', 'id');

        $this->consoleLog('create special permissions...');

        $accessControlService = new AccessControlService();

        // staff position place group
        $staffPositionPlace = $accessControlService->create(
            'Staff Positions', 'staff-position-place',
            AccessControl::TYPE_CONTROLLER,AccessControl::ACCESS_TYPE_PRIVATE
        );
        $accessControlService->create(
            'Add/Change staff positions', 'change-staff-positions',
            AccessControl::TYPE_ACTION, true, $staffPositionPlace->id, AccessControl::ACCESS_TYPE_PERMISSION
        );
        $accessControlService->create(
            'Delete staff positions', 'delete-staff-positions',
            AccessControl::TYPE_ACTION, true, $staffPositionPlace->id, AccessControl::ACCESS_TYPE_PERMISSION
        );

        // customer place group
        $customerPlace = $accessControlService->create(
            'Customer Club', 'customer-place',
            AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PRIVATE
        );
        $accessControlService->create(
            'Add customer to club', 'add-customer',
            AccessControl::TYPE_ACTION, true, $customerPlace->id, AccessControl::ACCESS_TYPE_PERMISSION
        );
        $accessControlService->create(
            'Delete customer from club', 'delete-customer',
            AccessControl::TYPE_ACTION, true, $customerPlace->id, AccessControl::ACCESS_TYPE_PERMISSION
        );
        $accessControlService->create(
            'Change customer status', 'set-status',
            AccessControl::TYPE_ACTION, true, $customerPlace->id, AccessControl::ACCESS_TYPE_PERMISSION
        );

        // attribute club group
        $attributeClub = $accessControlService->create(
            'Clubs Attributes', 'attribute-club',
            AccessControl::TYPE_CONTROLLER, AccessControl::ACCESS_TYPE_PRIVATE
        );
        $accessControlService->create(
            'Add/Change clubs attributes', 'change-clubs-attributes',
            AccessControl::TYPE_ACTION, true, $attributeClub->id, AccessControl::ACCESS_TYPE_PERMISSION
        );
        $accessControlService->create(
            'Delete clubs attributes', 'delete-clubs-attributes',
            AccessControl::TYPE_ACTION, true, $attributeClub->id, AccessControl::ACCESS_TYPE_PERMISSION
        );

        $this->consoleLog('add permissions for role...');

        $roleService = new RoleService();
        $roleService->setPermissionsToRole($trainerRole, $permissionIds);
        $roleService->setPermissionsToRole($clientRole, $permissionAppUser);
        $roleService->setPermissionsToRole($appUserRole, $permissionAppUser);

        $allPermissions = AccessPermission::find()->all();
        $permissionIds = ArrayHelper::map($allPermissions, 'id', 'id');
        $roleService->setPermissionsToRole($adminRole, $permissionIds);
    }

    public function clean()
    {
        AccessRolePermission::deleteAll();
        $this->consoleLog('access_role_permission clean');
        AccessControl::deleteAll();
        $this->consoleLog('access_control clean');
        AccessPermission::deleteAll();
        $this->consoleLog('access_permission clean');
        AccessRole::deleteAll();
        $this->consoleLog('access_role clean');
    }
}