<?php
namespace app\commands;

use app\src\entities\access\AccessControl;
use app\src\service\AccessControlService;
use yii\console\Controller;
use yii\console\ExitCode;

class AccessControlController extends Controller
{
    public static $accessNameTypes = [
        'custom' => AccessControl::TYPE_CUSTOM,
        'side' => AccessControl::TYPE_SIDE,
        'module' => AccessControl::TYPE_MODULE,
        'controller' => AccessControl::TYPE_CONTROLLER,
        'action' => AccessControl::TYPE_ACTION,
        'document' => AccessControl::TYPE_DOCUMENT,

        's' => AccessControl::TYPE_SIDE,
        'm' => AccessControl::TYPE_MODULE,
        'c' => AccessControl::TYPE_CONTROLLER,
        'a' => AccessControl::TYPE_ACTION,
        'd' => AccessControl::TYPE_DOCUMENT,
    ];

    public function actionCreate(string $type, string $slug, $name = null, bool $createPermissions = true, int $parentId = null)
    {
        $type = self::$accessNameTypes[$type] ?? AccessControl::TYPE_CONTROLLER;
        $name = $name ?? $slug;

        $accessControlService = new AccessControlService();
        $accessControlService->create($name, $slug, $type, $createPermissions, $parentId);

        echo "Control with name: '" . $name . "'" . ($createPermissions ? ' and permissions' : '') .  " were created. \n";
        return ExitCode::OK;
    }

    public function actionSearch(string $type, string $slug)
    {
        $type = self::$accessNameTypes[$type] ?? AccessControl::TYPE_CONTROLLER;

        $control = AccessControl::find()->where([
            'type' => $type,
            'slug' => $slug,
        ])->one();

        print_r($control->toArray());
        return ExitCode::OK;
    }

    public function actionDelete($type, $slug)
    {
        $type = self::$accessNameTypes[$type] ?? AccessControl::TYPE_CONTROLLER;

        $accessControlService = new AccessControlService();
        $delete = $accessControlService->delete($type, $slug) . "\n";

        if ($delete) {
            echo "Control with slug: '" . $slug . "' and permissions were deleted. \n";
        } else {
            echo "Control with slug: '" . $slug . "' cannot be deleted! \n";
        }

        return ExitCode::OK;
    }

    public function actionActionCreate(string $controllerSlug, string $slug, $name = null, bool $createPermissions = true)
    {
        if (!$controllerControl = $this->checkControllerBySlug($controllerSlug)) {
            return ExitCode::OK;
        }

        $name = $name ?? $slug;

        $accessControlService = new AccessControlService();
        $accessControlService->create($name, $slug, AccessControl::TYPE_ACTION, $createPermissions, $controllerControl->id);

        echo "Action with name: '" . $name . "'" . ($createPermissions ? ' and permissions' : '') .  " were created. \n";
        return ExitCode::OK;
    }

    public function actionActionSearch(string $controllerSlug, string $slug)
    {
        if (!$controllerControl = $this->checkControllerBySlug($controllerSlug)) {
            return ExitCode::OK;
        }

        $actionControl = AccessControl::find()->where([
            'type' => AccessControl::TYPE_ACTION,
            'slug' => $slug,
            'parent_id' => $controllerControl->id,
        ])->one();

        print_r($actionControl->toArray());
        return ExitCode::OK;
    }

    public function actionActionDelete(string $controllerSlug, string $slug)
    {
        if (!$controllerControl = $this->checkControllerBySlug($controllerSlug)) {
            return ExitCode::OK;
        }

        $accessControlService = new AccessControlService();
        $delete = $accessControlService->delete(AccessControl::TYPE_ACTION, $slug) . "\n";

        if ($delete) {
            echo "Action with slug: '" . $slug . "' and permissions were deleted. \n";
        } else {
            echo "Action with slug: '" . $slug . "' cannot be deleted! \n";
        }

        return ExitCode::OK;
    }

    private function checkControllerBySlug(string $controllerSlug)
    {
        $controllerControl = AccessControl::findOne(['slug' => $controllerSlug]);
        if (!$controllerControl) {
            echo "Controller control with slug: '" . $controllerSlug . "' cannot found! \n";
            return false;
        }

        return $controllerControl;
    }
}
