<?php
namespace api\controllers;

use api\formatter\IdToEntityFormatter;
use api\formatter\ImagePathFormatter;
use api\models\ApiClub;
use app\src\entities\coaching\Coaching;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\exception\ApiException;
use app\src\library\BaseApiController;
use app\src\library\Query;
use app\src\library\SearchBuilder;
use app\src\service\PlaceService;
use yii\helpers\ArrayHelper;

class ClubController extends BaseApiController
{
    public function behaviors()
    {
        $this->actionAccessConfig
            ->setAuthExpect(['index', 'details'])
            ->setControlExpect(['index', 'details'])
        ;

        return parent::behaviors();
    }

    public function actionIndex()
    {
        $apiClub = new ApiClub();
        $language = $this->getParam('language', null);

        /** @var Query $query */
        $query = $apiClub->getClubsQuery($language);

        $searchBuilder = $this->getSearchBuilder($query, ApiClub::mapApiToDbFields());
        $clubData = $searchBuilder->getModels();

        $clubData = IdToEntityFormatter::format($clubData, Coaching::class, 'coaching');

        $clubData = ImagePathFormatter::format($clubData, Club::class, [
            'extPath' => ImagePathFormatter::LOGO_SUB_DIR,
        ]);

        return $this->response([
            'clubs' => $clubData,
        ]);
    }

    public function actionDetails()
    {
        $clubId = $this->getParam('club_id');
        $language = $this->getParam('language', null);

        if (!$clubId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'club_id']);
        }

        $apiClub = new ApiClub();
        $info = $apiClub->getDetailsInfo($clubId, $language);

        return $this->response($info);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();

        $placeService = new PlaceService();
        $club = $placeService->createClubByData($attributes);

        return $this->response([
            'club' => $club->toArray(),
        ], (bool) $club);
    }

    public function actionUpdate()
    {
        $conditions = $this->getParam('conditions', []);
        $attributes = $this->getParams();

        $query = Place::find()->where(['type' => Place::TYPE_CLUB]);
        $searchBuilder = new SearchBuilder($query, $conditions);

        $clubs = $searchBuilder->getModels();

        if (empty($clubs)) {
            return $this->response([
                'message' => 'Clubs by this criteria not found.',
            ], false);
        }

        $placeService = new PlaceService();
        $ids = ArrayHelper::map($clubs, 'id', 'id');

        foreach ($ids as $id) {
            $placeService->updateClubByData((int) $id, $attributes);
        }

        $clubs = Club::findAll(['id' => $ids]);

        return $this->response([
            'clubs' => $clubs,
        ]);
    }

    public function actionDelete()
    {
        $conditions = $this->getParam('conditions', []);
        return $this->response([], Club::deleteAll($conditions));
    }
}