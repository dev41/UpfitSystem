<?php
namespace app\src\service;

use app\src\entities\access\AccessControl;

/**
 * Class AccessControlService
 */
class AccessControlService extends AbstractService
{
    public function create(
        string $name,
        string $slug,
        int $type,
        bool $createPermissions = true,
        int $parentId = null,
        int $accessType = null
    ): AccessControl
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $control = new AccessControl();
            $control->name = $name;
            $control->slug = $slug;
            $control->type = $type;
            $control->parent_id = $parentId;
            $control->access_type = $accessType;
            $control->save();

            if ($createPermissions) {
                /** @var AccessPermissionService $permissionService */
                $permissionService = self::getService(AccessPermissionService::class);
                $permissionService->createForControl($control);
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $control;
    }

    public function delete(int $type, string $slug)
    {
        return AccessControl::deleteAll([
            'type' => $type,
            'slug' => $slug,
        ]);
    }

    /**
     * @param array|string $slug
     * @return int
     */
    public function deleteControllerBySlug($slug)
    {
        return AccessControl::deleteAll([
            'type' => AccessControl::TYPE_CONTROLLER,
            'slug' => $slug,
        ]);
    }
}