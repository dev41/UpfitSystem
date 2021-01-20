<?php
namespace app\src\library;

use api\helpers\MapToDbFieldHelper;
use yii\db\Query;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;
abstract class BaseApiController extends BaseController
{
    /** @var string */
    public $responseFormat = Response::FORMAT_JSON;

    public function response(array $data = [], bool $success = true): array
    {
        \Yii::$app->response->format = $this->responseFormat;
        return $data + [
                'success' => $success,
            ];
    }

    /**
     * @param Query $query
     * @param array $mapApiFields
     * @return SearchBuilder
     * @throws \app\src\exception\ApiException
     */
    public function getSearchBuilder(Query $query, array $mapApiFields): SearchBuilder
    {
        $conditions = $this->getParam('conditions', []);
        $sort = $this->getParam('sort', []);
        $pagination = $this->getParam('pagination', []);

        if ($conditions) {
            $conditions = MapToDbFieldHelper::map($mapApiFields, $conditions, true);
        }

        if ($sort) {
            $sort = MapToDbFieldHelper::map($mapApiFields, $sort);
        }

        return new SearchBuilder($query, $conditions, $pagination, $sort);
    }

    public function behaviors()
    {
        return array_merge([
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'except' => $this->actionAccessConfig->getAuthExpect(),
            ],
        ], parent::behaviors());
    }
}