<?php
namespace api\controllers;

use api\formatter\IdToEntityFormatter;
use api\formatter\ImagePathFormatter;
use api\models\ApiCoaching;
use app\src\entities\coaching\Coaching;
use app\src\entities\user\User;
use app\src\library\BaseApiController;
use app\src\service\CoachingService;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class CoachingController extends BaseApiController
{
    public function behaviors()
    {
        $this->actionAccessConfig
            ->setAuthExpect(['index'])
            ->setControlExpect(['index'])
        ;

        return parent::behaviors();
    }

    public function actionIndex()
    {
        $language = $this->getParam('language', null);
        $apiCoaching = new ApiCoaching();

        /** @var Query $query */
        $query = $apiCoaching->getCoachingsQuery($language);

        $searchBuilder = $this->getSearchBuilder($query, ApiCoaching::mapApiToDbFields());
        $responseData = $searchBuilder->getModels();

        $responseData = IdToEntityFormatter::format($responseData, User::class, 'trainers');

        $responseData = ImagePathFormatter::format($responseData, Coaching::class, [
            'fieldId' => 'coaching_id',
            'fieldName' => 'image'
        ]);

        return $this->response([
            'coaching' => $responseData,
        ]);
    }

    public function actionCreate()
    {
        $coachingService = new CoachingService();
        $coaching = $coachingService->createByData($this->getParams());

        return $this->response([
            'coaching' => $coaching->toArray(),
        ], (bool) $coaching);
    }

    public function actionUpdate()
    {
        $query = Coaching::find();
        $searchBuilder = $this->getSearchBuilder($query);
        $coaching = $searchBuilder->getModels();

        if (empty($coaching)) {
            return $this->response([
                'message' => 'Coaching by this criteria not found.',
            ], false);
        }

        $coachingService = new CoachingService();
        $ids = ArrayHelper::map($coaching, 'id', 'id');

        foreach ($coaching as $training) {
            $coachingService->updateByData($training, $this->getParams());
        }

        $coaching = Coaching::findAll(['id' => $ids]);

        return $this->response([
            'coaching' => $coaching,
        ]);
    }

    public function actionDelete()
    {
        $conditions = $this->getParam('conditions', []);
        return $this->response([], Coaching::deleteAll($conditions));
    }
}