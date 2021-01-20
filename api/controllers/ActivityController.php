<?php
namespace api\controllers;

use api\formatter\IdToEntityFormatter;
use api\formatter\ImagePathFormatter;
use api\models\ApiActivity;
use app\src\entities\activity\Activity;
use app\src\entities\user\User;
use app\src\library\BaseApiController;
use yii\db\Query;

class ActivityController extends BaseApiController
{
    public function actionIndex()
    {
        $userId = \Yii::$app->user->getId();
        $language = $this->getParam('language', null);

        $apiActivity = new ApiActivity();

        /** @var Query $query */
        $query = $apiActivity->getActivitiesQuery($userId, $language);

        $searchBuilder = $this->getSearchBuilder($query, ApiActivity::mapApiToDbFields());
        $responseData = $searchBuilder->getModels();

        $responseData = IdToEntityFormatter::format($responseData, User::class, 'organizers');

        return $this->response([
            'activity' => ImagePathFormatter::format($responseData, Activity::class, [
                'fieldName' => 'image'
            ]),
        ]);
    }
}