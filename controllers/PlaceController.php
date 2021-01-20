<?php
namespace app\controllers;

use app\src\entities\AbstractModel;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\place\Subplace;
use app\src\entities\place\SubplaceSearch;
use app\src\exception\ModelValidateException;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\PlaceService;


/**
 * Class PlaceController
 */
class PlaceController extends BaseController
{
    /**
     * Lists all Club models.
     */
    public function actionIndex()
    {
        $subplaceSearch = new SubplaceSearch();
        $params = $this->getParams();

        $dataProvider = $subplaceSearch->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $subplaceSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();

        if (\Yii::$app->request->isPost) {

            $placeService = new PlaceService();
            try {
                $placeService->createPlaceByData($attributes, Place::TYPE_SUB_PLACE);
                return $this->redirect('/place/index');
            } catch (\Exception $e) {
            }
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        return $this->render('create', [
            'place' => new Subplace(),
            'clubs' => $clubs,
            'types' => Place::getTypeLabels(),
        ]);
    }

    public function actionUpdate($id)
    {
        $attributes = \Yii::$app->request->post();
        $placeService = new PlaceService();

        if ($attributes) {
            try {
                $placeService->updatePlaceByData((int) $id, $attributes, Place::TYPE_SUB_PLACE);
                return $this->redirect('/place/index');
            } catch (\Exception $e) {
            }
        }

        $subPlace = Subplace::findOne($id);
        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $this->appendEntryPoint();

        return $this->render('create', [
            'place' => $subPlace,
            'clubs' => $clubs,
            'types' => Place::getTypeLabels(),
        ]);
    }

    public function actionDelete()
    {
        $placeId = $this->getParam('id');
        $isAjax = $this->getParam('isAjax');

        $placeService = new PlaceService();
        $placeService->deleteById($placeId);

        if ($isAjax) {
            return $this->responseListing(new SubplaceSearch(), '/place/index');
        }

        return $this->redirect('/place/index');
    }

    public function actionCopyPlace(int $id)
    {
        $placeService = new PlaceService();
        try {
            $placeInfo = $placeService->copySubplaceById($id);
        } catch (ModelValidateException $e) {
            return $this->redirect('/place/index');
        }
        return $this->responseJson([
            'url' => '/place/update/?id=' . $placeInfo->id,
        ]);
    }

    public function actionView()
    {
        $placeId = (int) $this->getParam('id');

        $place = Subplace::findOne($placeId);

        if (!$place) {
            throw new \InvalidArgumentException('id');
        }

        $this->appendEntryPoint('view');

        return $this->render('view', [
            'place' => $place,
            'types' => Place::getTypeLabels(),
        ]);
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
        return parent::actionDeleteImage($id, $filename, $extPath, new Place());
    }
}