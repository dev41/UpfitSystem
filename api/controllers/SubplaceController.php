<?php
namespace api\controllers;

use api\formatter\ImagePathFormatter;
use api\models\ApiSubplace;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\place\Subplace;
use app\src\library\BaseApiController;
use app\src\library\Query;
use app\src\library\SearchBuilder;
use app\src\service\PlaceService;
use yii\helpers\ArrayHelper;

class SubplaceController extends BaseApiController
{
    public function actionIndex()
    {
        $language = $this->getParam('language', null);

        $apiSubplace = new ApiSubplace();

        /** @var Query $query */
        $query = $apiSubplace->getSubplacesQuery($language);

        $searchBuilder = $this->getSearchBuilder($query, ApiSubplace::mapApiToDbFields());
        $responseData = $searchBuilder->getModels();

        return $this->response([
            'places' => ImagePathFormatter::format($responseData, Subplace::class, [
                'fieldName' => 'images',
                'isDataTypeArray' => true
            ]),
        ]);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();

        $placeService = new PlaceService();
        $subPlace = $placeService->createPlaceByData($attributes, Place::TYPE_SUB_PLACE);

        return $this->response([
            'place' => $subPlace->toArray(),
        ]);
    }

    public function actionUpdate()
    {
        $conditions = $this->getParam('conditions', []);
        $attributes = $this->getParams();

        $query = Place::find()->where(['type' => Place::TYPE_SUB_PLACE]);
        $searchBuilder = new SearchBuilder($query, $conditions);

        $subPlaces = $searchBuilder->getModels();

        if (empty($subPlaces)) {
            return $this->response([
                'message' => 'Places by this criteria not found.',
            ], false);
        }

        $placeService = new PlaceService();

        $subPlaceIds = ArrayHelper::map($subPlaces, 'id', 'id');

        foreach ($subPlaceIds as $id) {
            $placeService->updatePlaceByData((int) $id, $attributes, Place::TYPE_SUB_PLACE);
        }

        $subPlaces = Subplace::findAll(['id' => $subPlaceIds]);

        return $this->response([
            'places' => $subPlaces,
        ]);
    }

    public function actionDelete()
    {
        $conditions = $this->getParam('conditions', []);
        return $this->response([], Club::deleteAll($conditions));
    }
}