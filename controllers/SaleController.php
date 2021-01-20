<?php
namespace app\controllers;

use app\src\entities\AbstractModel;
use app\src\entities\sale\Sale;
use app\src\entities\sale\SaleSearch;
use app\src\entities\place\Club;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\SaleService;

/**
 * Class SaleController
 */
class SaleController extends BaseController
{
    /**
     * Lists all Sale models.
     */
    public function actionIndex()
    {
        $sale = new SaleSearch();
        $params = \Yii::$app->request->queryParams;
        $dataProvider = $sale->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $sale,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $saleId = (int) $this->getParam('id');

        $sale = Sale::findOne($saleId);

        $this->appendEntryPoint('view');

        return $this->render('view', [
            'sale' => $sale,
        ]);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();

        if (\Yii::$app->request->isPost) {
            $saleService = new SaleService();
            $saleService->createSaleByData($attributes);
            return $this->redirect('/sale/index');
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        return $this->render('create', [
            'sale' => new Sale(),
            'clubs' => $clubs,
        ]);
    }

    public function actionDelete()
    {
        $saleId = (int) $this->getParam('id');
        $isAjax = $this->getParam('isAjax');

        $saleService = new SaleService();
        $saleService->deleteById($saleId);

        if ($isAjax) {
            return $this->responseListing(new SaleSearch(), '/sale/index');
        }

        return $this->redirect('/sale/index');
    }

    public function actionUpdate($id)
    {
        $attributes = \Yii::$app->request->post();
        $saleService = new SaleService();

        if ($attributes) {
            $saleService->updateSaleByData((int) $id, $attributes);
            return $this->redirect('/sale/index');
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $sale = Sale::findOne($id);

        $this->appendEntryPoint('input-file');

        return $this->render('create', [
            'sale' => $sale,
            'clubs' => $clubs,
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
        return parent::actionDeleteImage($id, $filename, $extPath, new Sale());
    }
}