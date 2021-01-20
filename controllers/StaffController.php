<?php
namespace app\controllers;

use app\src\entities\AbstractModel;
use app\src\entities\staff\Staff;
use app\src\entities\staff\StaffPositionPlaceSearch;
use app\src\entities\staff\StaffPositionSearch;
use app\src\entities\staff\StaffSearch;
use app\src\entities\user\Position;
use app\src\library\BaseController;
use app\src\service\RoleService;
use app\src\service\UserService;

/**
 * Class StaffController
 */
class StaffController extends BaseController
{
    public function actionIndex()
    {
        $userSearch = new StaffSearch();
        $params = $this->getParams();
        $dataProvider = $userSearch->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
           'searchModel' => $userSearch,
           'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetCreateForm()
    {
        $clubId = (int) $this->getParam('clubId');

        $staff = new Staff();
        $staff->clubId = $clubId;
        $roleService = new RoleService();

        $form = $this->renderAjax('/staff/partial/staff_form', [
            'staff' => $staff,
            'roles' => $roleService->getAvailableRolesForSetting(),
            'renderAjax' => true,
            'positions' => Position::getAll(),
        ]);

        return $this->responseJson([
            'html' => $form,
        ]);
    }

    public function actionGetUpdateForm()
    {
        $id = (int) $this->getParam('id');
        $staff = Staff::findOne($id);

        $staffPositionPlaceSearch = new StaffPositionSearch($id);

        $roleService = new RoleService();

        $form = $this->renderAjax('/staff/create', [
            'staff' => $staff,
            'roles' => $roleService->getAvailableRolesForSetting(),
            'renderAjax' => true,
            'positions' => Position::getAll(),
            'staffDataProvider' => $staffPositionPlaceSearch->getSearchDataProvider()
        ]);

        return $this->responseJson([
            'html' => $form,
        ]);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();
        $userService = new UserService();

        /** @var Staff $staff */
        $staff = $userService->createByData($attributes, Staff::MODEL_STAFF);

        $staffPositionPlaceSearch = new StaffPositionPlaceSearch($staff->clubId);

        $staffList = $this->renderAjax('/club/partial/staff-list', [
            'staffDataProvider' => $staffPositionPlaceSearch->getSearchDataProvider(),
            'staffPositionPlaceSearch' => $staffPositionPlaceSearch,
            'clubId' => $staff->clubId,
        ]);

        return $this->responseJson([
            'html' => $staffList,
        ]);
    }

    public function actionUpdate($id)
    {
        $staff = Staff::findOne($id);
        $staff->validateBehaviour = Staff::VALIDATE_UPDATE_FORM;

        $attributes = \Yii::$app->request->post();
        $userService = new UserService();
        $staffPositionPlaceSearch = new StaffPositionSearch($id);

        if (\Yii::$app->request->isPost) {
            try {
                $userService->updateByData($staff, $attributes);
            } catch (\Exception $e) {
            }
        }
        $roleService = new RoleService();

        $this->appendEntryPoint();

        return $this->render('create', [
            'staff' => $staff,
            'roles' => $roleService->getAvailableRolesForSetting(),
            'positions' => Position::getAll(),
            'staffDataProvider' => $staffPositionPlaceSearch->getSearchDataProvider(),
        ]);
    }

    public function actionDelete($id)
    {
        $isAjax = $this->getParam('isAjax');

        $userService = new UserService();
        $userService->deleteById((int) $id);

        if ($isAjax) {
            return $this->responseListing(new StaffSearch(), '/staff/index');
        }

        return $this->redirect('/staff/index');
    }

    /**
     * @param $id
     * @param $filename
     * @param $extPath
     * @param AbstractModel $modelClass
     * @return array
     */
    public function actionDeleteImage($id, $filename, $extPath, AbstractModel $modelClass = null)
    {
        return parent::actionDeleteImage($id, $filename, $extPath, new Staff());
    }
}