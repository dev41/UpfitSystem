<?php
namespace api\controllers;

use api\formatter\ImagePathFormatter;
use api\models\ApiSale;
use app\src\entities\sale\Sale;
use app\src\library\BaseApiController;
use yii\db\Query;

class SaleController extends BaseApiController
{
    public function actionIndex()
    {
        $userId = \Yii::$app->user->getId();
        $language = $this->getParam('language', null);

        $apiSale = new ApiSale();

        /** @var Query $query */
        $query = $apiSale->getSaleQuery($userId, $language);

        $searchBuilder = $this->getSearchBuilder($query, ApiSale::mapApiToDbFields());
        $responseData = $searchBuilder->getModels();

        return $this->response([
            'sale' => ImagePathFormatter::format($responseData, Sale::class, [
                'fieldName' => 'image'
            ]),
        ]);
    }
}