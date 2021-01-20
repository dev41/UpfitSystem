<?php
namespace app\controllers;

use app\src\entities\place\Club;
use app\src\entities\staff\Staff;
use app\src\entities\staff\StaffPositionPlace;
use app\src\entities\staff\StaffPositionPlaceSearch;
use app\src\entities\staff\StaffPositionSearch;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\StaffPositionPlaceService;
use yii\helpers\ArrayHelper;

class StaffPositionPlaceController extends BaseController
{
    public function actionAddStaffForm()
    {
        $clubId = (int) $this->getParam('clubId');

        $staffPositionPlaceService = new StaffPositionPlaceService();
        $availableStaff = $staffPositionPlaceService->getAvailableNewStaffIdsByClubId($clubId);

        $staffForm = $this->renderAjax('/club/partial/staff-position-place-form', [
            'staffPositionPlace' => new StaffPositionPlace(),
            'availableStaff' => $availableStaff,
        ]);

        return $this->responseJson([
            'html' => $staffForm,
        ]);
    }

    public function actionAddClubsForm()
    {
        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $staffForm = $this->renderAjax('/staff/partial/position-place-form', [
            'staffPositionPlace' => new StaffPositionPlace(),
            'clubs' => $clubs,
        ]);

        return $this->responseJson([
            'html' => $staffForm,
        ]);
    }

    /**
     * @return array
     * @throws \app\src\exception\ModelValidateException
     */
    public function actionAddStaff()
    {
        $attributes = $this->getParams();
        $clubId = (int) $this->getParam('clubId');

        $staffPositionPlace = new StaffPositionPlace();
        $staffPositionPlace->load($attributes);
        $staffPositionPlace->place_id = $clubId;

        if ($staffPositionPlace->positions) {
            $staffPositionPlace->position_id = reset($staffPositionPlace->positions);
        }

        $staffPositionPlace->throwExceptionIfNotValid();

        $staffPositionPlaceService = new StaffPositionPlaceService();
        $staffPositionPlaceService->setPositionsByUserIdAndPlaceId(
            $staffPositionPlace->user_id,
            $clubId,
            $staffPositionPlace->positions
        );

        $staffPositionPlaceSearch = new StaffPositionPlaceSearch($clubId);

        $staffList = $this->renderAjax('/club/partial/staff-list', [
            'staffDataProvider' => $staffPositionPlaceSearch->getSearchDataProvider(),
            'staffPositionPlaceSearch' => $staffPositionPlaceSearch,
            'clubId' => $clubId,
        ]);

        return $this->responseJson([
            'html' => $staffList,
        ]);
    }

    public function actionAddPosition()
    {
        $attributes = $this->getParams();
        $userId = (int) $this->getParam('staffId');

        $staffPositionPlace = new StaffPositionPlace();
        $staffPositionPlace->load($attributes);
        $staffPositionPlace->user_id = $userId;
        if ($staffPositionPlace->positions) {
            $staffPositionPlace->position_id = reset($staffPositionPlace->positions);
        }
        $staffPositionPlace->throwExceptionIfNotValid();

        $staffPositionPlaceService = new StaffPositionPlaceService();
        $staffPositionPlaceService->setPositionsByUserIdAndPlaceId(
            $userId,
            $staffPositionPlace->place_id,
            $staffPositionPlace->positions
        );

        $staffSearch = new StaffPositionSearch($userId);
        $staff = Staff::findOne($userId);

        $staffList = $this->renderAjax('/staff/partial/position-list', [
            'staffDataProvider' => $staffSearch->getSearchDataProvider(),
            'staff' => $staff,
        ]);

        return $this->responseJson([
            'html' => $staffList,
        ]);
    }

    public function actionUpdateStaffForm()
    {
        $userId = (int) $this->getParam('user_id');
        $clubId = (int) $this->getParam('club_id');

        $staffPositionPlaces = StaffPositionPlace::findAll([
            'user_id' => $userId,
            'place_id' => $clubId,
        ]);

        $userPositionPlace = reset($staffPositionPlaces);
        $userPositionPlace->positions = ArrayHelper::map($staffPositionPlaces, 'id', 'position_id');

        $staffForm = $this->renderAjax('/club/partial/staff-position-place-form', [
            'staffPositionPlace' => $userPositionPlace,
        ]);

        return $this->responseJson([
            'html' => $staffForm,
        ]);
    }

    public function actionUpdatePositionClubsForm()
    {
        $userId = (int) $this->getParam('user_id');
        $clubId = (int) $this->getParam('club_id');

        $staffPositionPlaces = StaffPositionPlace::findAll([
            'user_id' => $userId,
            'place_id' => $clubId,
        ]);

        $userPositionPlace = reset($staffPositionPlaces);
        $userPositionPlace->positions = ArrayHelper::map($staffPositionPlaces, 'id', 'position_id');

        $staffForm = $this->renderAjax('/staff/partial/position-place-form', [
            'staffPositionPlace' => $userPositionPlace,
        ]);

        return $this->responseJson([
            'html' => $staffForm,
        ]);
    }

    public function actionDeleteStaff()
    {
        $userId = (int) $this->getParam('userId');
        $clubId = (int) $this->getParam('clubId');

        StaffPositionPlace::deleteAll(['user_id' => $userId, 'place_id' => $clubId]);

        $staffPositionPlaceSearch = new StaffPositionPlaceSearch($clubId);

        $staffForm = $this->renderAjax('/club/partial/staff-list', [
            'staffDataProvider' => $staffPositionPlaceSearch->getSearchDataProvider(),
            'staffPositionPlaceSearch' => $staffPositionPlaceSearch,
            'clubId' => $clubId,
        ]);

        return $this->responseJson([
            'html' => $staffForm,
        ]);
    }

    public function actionDeletePositionStaff()
    {
        $userId = (int) $this->getParam('userId');
        $clubId = (int) $this->getParam('clubId');

        StaffPositionPlace::deleteAll(['user_id' => $userId, 'place_id' => $clubId]);

        $staffSearch = new StaffPositionSearch($userId);
        $staff = Staff::findOne($userId);

        $staffForm = $this->renderAjax('/staff/partial/position-list', [
            'staffDataProvider' => $staffSearch->getSearchDataProvider(),
            'staff' => $staff,
        ]);

        return $this->responseJson([
            'html' => $staffForm,
        ]);
    }

}
