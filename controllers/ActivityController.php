<?php
namespace app\controllers;

use app\src\entities\AbstractModel;
use app\src\entities\activity\Activity;
use app\src\entities\activity\ActivitySearch;
use app\src\entities\place\Club;
use app\src\entities\staff\StaffSearch;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\ActivityService;

/**
 * Class ActivityController
 */
class ActivityController extends BaseController
{
    /**
     * Lists all Activity models.
     */
    public function actionIndex()
    {
        $activity = new ActivitySearch();
        $params = \Yii::$app->request->queryParams;
        $dataProvider = $activity->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $activity,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $activityId = (int) $this->getParam('id');

        $activity = Activity::findOne($activityId);

        $this->appendEntryPoint('view');

        return $this->render('view', [
            'activity' => $activity,
            'organizers' => $activity->organizers
        ]);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();

        if (\Yii::$app->request->isPost) {
            $activityService = new ActivityService();
            $activityService->createActivityByData($attributes);
            return $this->redirect('/activity/index');
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $staffSearch = new StaffSearch();
        $organizers = $staffSearch->getSearchQuery()->all();

        return $this->render('create', [
            'activity' => new Activity(),
            'clubs' => $clubs,
            'organizers' => $organizers
        ]);
    }

    public function actionDelete()
    {
        $activityId = (int) $this->getParam('id');

        $activityService = new ActivityService();
        $activityService->deleteById($activityId);

        return $this->responseListing(new ActivitySearch(), '/activity/index');
    }

    public function actionUpdate($id)
    {
        $attributes = \Yii::$app->request->post();
        $activityService = new ActivityService();

        if ($attributes) {
            $activityService->updateActivityByData((int) $id, $attributes);
            return $this->redirect('/activity/index');
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $activity = Activity::findOne($id);

        $this->appendEntryPoint('input-file');

        return $this->render('create', [
            'activity' => $activity,
            'clubs' => $clubs,
            'organizers' => $activity->organizers
        ]);
    }

    /**
     * @param $id
     * @param $filename
     * @param $extPath
     * @param AbstractModel|null $model
     * @return array
     */
    public function actionDeleteImage($id, $filename, $extPath, AbstractModel $model = null)
    {
        return parent::actionDeleteImage($id, $filename, $extPath, new Activity());
    }
}