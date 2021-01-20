<?php
namespace app\controllers;

use app\src\entities\access\AccessControl;
use app\src\entities\access\AccessControlSearch;
use app\src\entities\access\AccessRole;
use app\src\entities\access\AccessRoleSearch;
use app\src\library\BaseController;
use app\src\service\RoleService;

/**
 * Class RoleController
 */
class RoleController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new AccessRoleSearch();
        $dataProvider = $searchModel->getSearchDataProvider();

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        if (\Yii::$app->request->isPost) {
            $roleService = new RoleService();

            $roleData = \Yii::$app->request->post();
            $permissions = \Yii::$app->request->post('Permissions', []);

            try {
                $roleService->createByData($roleData, $permissions);
                $this->redirect('/role/index');
            } catch (\Exception $e) {
            }
        }

        $accessControlSearchModel = new AccessControlSearch();
        $accessToControllersDataProvider = $accessControlSearchModel->getSearchDataProvider();
        $accessToActionsDataProvider = $accessControlSearchModel->getSearchDataProvider([], AccessControl::TYPE_ACTION);

        return $this->render('create', [
            'role' => new AccessRole(),
            'controlsWithTypes' => [],
            'accessToControllersDataProvider' => $accessToControllersDataProvider,
            'accessToActionsDataProvider' => $accessToActionsDataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $role = AccessRole::findOne($id);

        if ($role->slug === AccessRole::ROLE_SUPER_ADMIN) {
            return $this->redirect('/role/index');
        }

        $roleService = new RoleService();

        if (\Yii::$app->request->isPost) {
            $roleData = \Yii::$app->request->post();
            $permissions = \Yii::$app->request->post('Permissions', []);

            $roleService->updateByData($role, $roleData, $permissions);
            $this->redirect('/role/index');
        }

        $controlsWithTypes = $roleService->getControlsWithTypesByRole($role);

        $accessControlSearchModel = new AccessControlSearch();
        $accessToControllersDataProvider = $accessControlSearchModel->getSearchDataProvider();
        $accessToActionsDataProvider = $accessControlSearchModel->getSearchDataProvider([], AccessControl::TYPE_ACTION);

        return $this->render('create', [
            'role' => $role,
            'controlsWithTypes' => $controlsWithTypes,
            'accessToControllersDataProvider' => $accessToControllersDataProvider,
            'accessToActionsDataProvider' => $accessToActionsDataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $role = AccessRole::findOne($id);
        $isAjax = $this->getParam('isAjax');

        if ($role->type === AccessRole::TYPE_SYSTEM) {
            return $this->redirect('/role/index');
        }

        AccessRole::deleteAll(['id' => $id]);

        if ($isAjax) {
            return $this->responseListing(new AccessRoleSearch(), '/role/index');
        }

        return $this->redirect('/role/index');
    }
}