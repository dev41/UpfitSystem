<?php
namespace api\controllers;

use api\formatter\ImagePathFormatter;
use api\models\ApiNews;
use app\src\entities\news\News;
use app\src\library\BaseApiController;
use yii\db\Query;

class NewsController extends BaseApiController
{
    public function actionIndex()
    {
        $userId = \Yii::$app->user->getId();
        $language = $this->getParam('language', null);

        $apiNews = new ApiNews();

        /** @var Query $query */
        $query = $apiNews->getNewsQuery($userId, $language);

        $searchBuilder = $this->getSearchBuilder($query, ApiNews::mapApiToDbFields());
        $responseData = $searchBuilder->getModels();

        return $this->response([
            'news' => ImagePathFormatter::format($responseData, News::class, [
                'fieldName' => 'image'
            ]),
        ]);
    }
}