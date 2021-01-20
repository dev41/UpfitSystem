<?php
namespace app\controllers;

use app\src\dataProviders\CoachingFormDataProvider;
use app\src\entities\AbstractModel;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingSearch;
use app\src\entities\place\Place;
use app\src\library\BaseController;
use app\src\service\CoachingService;
use yii\helpers\ArrayHelper;

/**
 * Class CoachingController
 */
class CoachingController extends BaseController
{
    public function actionIndex()
    {
        $coachingSearch = new CoachingSearch();
        $params = \Yii::$app->request->queryParams;

        $dataProvider = $coachingSearch->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $coachingSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $attributes = \Yii::$app->request->post();
        $coachingService = new CoachingService();

        if (\Yii::$app->request->isPost) {
            try {
                $coachingService->createByData($attributes);
                return $this->redirect('/coaching/index');
            } catch (\Exception $e) {
                throw $e;
            }
        } else {

            $coachingData = new CoachingFormDataProvider(new Coaching());

            $this->appendEntryPoint();

            return $this->render('create', $coachingData->getData());
        }
    }

    public function actionUpdate($id)
    {
        $attributes = \Yii::$app->request->post();
        $coaching = Coaching::findOne($id);
        $coachingService = new CoachingService();

        if (\Yii::$app->request->isPost) {
            try {
                $coachingService->updateByData($coaching, $attributes);
                return $this->redirect('/coaching/index');
            } catch (\Exception $e) {
                throw $e;
            }
        } else {
            $coachingData = new CoachingFormDataProvider($coaching);

            $this->appendEntryPoint('coaching.create');

            return $this->render('create', $coachingData->getData());
        }
    }

    public function actionDelete()
    {
        $id = (int) $this->getParam('id');
        $isAjax = $this->getParam('isAjax');

        Coaching::deleteAll(['id' => $id]);

        if ($isAjax) {
            return $this->responseListing(new CoachingSearch(), '/coaching/index');
        }

        return $this->redirect('/coaching/index');
    }

    public function actionGetPlaceByClubs()
    {
        $clubIds = $this->getParam('clubs');

        $places = '';
        if ($clubIds) {
            $places = Place::getAllByClubId($clubIds);
            $places = ArrayHelper::map($places, 'id', 'name');
        }

        return $this->responseJson([
            'places' => $places,
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
        return parent::actionDeleteImage($id, $filename, $extPath, new Coaching());
    }
}