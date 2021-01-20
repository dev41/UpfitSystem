<?php
namespace app\controllers;

use app\src\entities\AbstractModel;
use app\src\entities\attribute\AttributeClubSearch;
use app\src\entities\customer\CustomerPlaceSearch;
use app\src\entities\image\Image;
use app\src\entities\place\Club;
use app\src\entities\place\ClubSearch;
use app\src\entities\staff\StaffPositionPlaceSearch;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\ClubService;
use app\src\service\ImageService;
use app\src\service\PlaceService;

/**
 * Class ClubController
 */
class ClubController extends BaseController
{
    /**
     * Lists all Club models.
     */
    public function actionIndex()
    {
        $place = new ClubSearch();
        $params = \Yii::$app->request->queryParams;
        $dataProvider = $place->getSearchDataProvider($params);

        $this->appendEntryPoint();

        return $this->render('index', [
            'searchModel' => $place,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();

        $clubService = new ClubService();
        $club = $clubService->createEmptyClub($attributes);

        $redirectUrl = '/club/update?id=' . $club->id;
        if (!AccessChecker::hasPermission('club.update')) {
            $redirectUrl = '/club/index';
        }

        return $this->responseJson([
            'redirectUrl' => $redirectUrl,
        ]);
    }

    public function actionGetCreateClubForm()
    {
        $form = $this->renderAjax('/club/partial/create-club-form', [
            'club' => new Club(),
        ]);

        return $this->responseJson([
            'html' => $form,
        ]);
    }

    public function actionDelete()
    {
        $clubId = $this->getParam('id');
        $isAjax = $this->getParam('isAjax');

        $clubService = new PlaceService();
        $clubService->deleteById($clubId);

        if ($isAjax) {
            return $this->responseListing(new ClubSearch(), '/club/index');
        }

        return $this->redirect('/club/index');
    }

    public function actionUpdate($id)
    {
        $owners = User::getOwnerList();
        $club = Club::findOne($id);
        $params = $this->getParams();

        $staffPositionPlaceSearch = new StaffPositionPlaceSearch($club->id);
        $customerPlaceSearch = new CustomerPlaceSearch($club->id);
        $attributePlaceSearch = new AttributeClubSearch($club->id);

        $this->appendEntryPoint();

        return $this->render('create', [
            'club' => $club,
            'owners' => $owners,
            'staffPositionPlaceSearch' => $staffPositionPlaceSearch,
            'staffDataProvider' => $staffPositionPlaceSearch->getSearchDataProvider($params),
            'clientsDataProvider' => $customerPlaceSearch->getSearchDataProvider(),
            'attributesDataProvider' => $attributePlaceSearch->getSearchDataProvider(),
        ]);
    }

    /**
     * @param $id
     * @throws \app\src\exception\ModelValidateException
     */
    public function actionUpdateInfo($id)
    {
        $attributes = \Yii::$app->request->post();

        $club = Club::findOne($id);
        $club->load($attributes);
        $club->updated_at = $club->updated_at ?? AbstractModel::getDateTimeNow();
        $club->updated_by = $club->updated_by ?? \Yii::$app->user->getId();
        $club->throwExceptionIfNotValid();
        $club->save();
        $this->appendEntryPoint('club.update');
    }

    public function actionUploadImages($id)
    {
        $club = Club::findOne($id);

        $imageService = new ImageService();
        $imageService->uploadImages($club, $_FILES, Image::TYPE_PLACE_PHOTO);
        $imageService->uploadLogo($club, $_FILES, Image::TYPE_PLACE_LOGO);
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
        return parent::actionDeleteImage($id, $filename, $extPath, new Club());
    }
}