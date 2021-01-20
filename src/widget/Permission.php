<?php
namespace app\src\widget;

use app\src\entities\access\AccessControl;
use app\src\entities\access\AccessPermission;
use app\src\entities\access\AccessRole;
use app\src\service\RoleService;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;

class Permission extends AbstractWidget
{
    const DEFAULT_INPUT_NAME = 'Permission';

    // required
    /** @var AccessRole */
    public $role;
    /** @var ActiveDataProvider */
    public $dataProvider;

    // optional
    /** @var array */
    public $permissionTypes = [AccessPermission::TYPE_CUSTOM];
    /** @var string  */
    public $inputName = self::DEFAULT_INPUT_NAME;
    /** @var string */
    public $controlTitle;

    // internal
    /** @var array */
    private $controlsWithTypes;

    protected static $shownParentRows = [];

    public function init()
    {
        parent::init();
        PermissionAsset::register($this->view);

        $this->validateParams();

        $roleService = new RoleService();
        $this->controlsWithTypes = $roleService->getControlsWithTypesByRole($this->role);

        $this->widgetHash = spl_object_hash($roleService);
    }

    private function validateParams(): bool
    {
        if (
            !($this->role instanceof AccessRole) ||
            !($this->dataProvider instanceof ActiveDataProvider)
        ) {
            throw new InvalidParamException('Widget ' . self::class . ' required params: role, dataProvider.');
        }

        return true;
    }

    public static function renderParentRow(AccessControl $accessControl, bool $oneTime = true)
    {
        $accessAttributeLabels = $accessControl->attributeLabels();
        if (!$accessControl->parent) {
            return;
        }

        if (isset(self::$shownParentRows[$accessControl->parent_id]) && $oneTime) {
            return;
        }

        self::$shownParentRows[$accessControl->parent_id] = true;

        echo '<tr><td colspan="100"><strong>' . $accessAttributeLabels[$accessControl->parent->name] . '</strong></td></tr>';
    }

    public function run()
    {
        return $this->render('permission', [
            'role' => $this->role,
            'dataProvider' => $this->dataProvider,
            'inputName' => $this->inputName,
            'permissionTypes' => $this->permissionTypes,
            'controlTitle' => $this->controlTitle,
            'controlsWithTypes' => $this->controlsWithTypes,
            'widgetHash' => $this->widgetHash,
        ]);
    }
}