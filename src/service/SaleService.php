<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\sale\Sale;
use app\src\exception\ModelValidateException;

/**
 * Class SaleService
 */
class SaleService extends AbstractService
{
    /**
     * @param array $data
     * @param string|null $scope
     * @param int|null $userId
     * @return Sale
     * @throws ModelValidateException
     */
    public function createSaleByData(array $data, string $scope = null, int $userId = null): Sale
    {
        $sale = new Sale();
        $sale->load($data, $scope);

        $sale->created_by = $sale->created_by ?? $userId ?? \Yii::$app->user->id;
        $sale->created_at = $sale->created_at ?? AbstractModel::getDateTimeNow();

        $sale->save();

        $imageService = new ImageService();
        $imageService->uploadImages($sale, $_FILES, Image::TYPE_SALE_IMAGE);

        return $sale;
    }

    public function updateSaleByData(int $id, array $data): Sale
    {
        $sale = Sale::findOne($id);
        $sale->load($data);

        $imageService = new ImageService();
        $imageService->uploadImages($sale, $_FILES, Image::TYPE_SALE_IMAGE, true);

        $sale->save();

        return $sale;
    }

    public function deleteById(int $saleId)
    {
        Sale::deleteAll([
            'id' => $saleId
        ]);
    }
}