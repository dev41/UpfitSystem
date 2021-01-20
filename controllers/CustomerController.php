<?php
namespace app\controllers;

use app\src\entities\access\AccessRole;
use app\src\entities\customer\Customer;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\customer\CustomerPlaceSearch;
use app\src\entities\customer\CustomerSearch;
use app\src\library\BaseController;
use app\src\service\UserService;

/**
 * Class CustomerController
 */
class CustomerController extends BaseController
{
    public function actionIndex()
    {
        $userSearch = new CustomerSearch();
        $params = $this->getParams();
        $dataProvider = $userSearch->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $userSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        /** @var Customer $customer */
        $attributes = $this->getParams();
        $userService = new UserService();

        $userService->createCustomerByData($attributes, Customer::MODEL_CUSTOMER);
        $customerPlace = new CustomerPlace();
        $customerPlace->load($attributes);

        $customerPlaceSearch = new CustomerPlaceSearch($customerPlace->place_id);

        $customerList = $this->renderAjax('/club/partial/customers-list', [
            'clientsDataProvider' => $customerPlaceSearch->getSearchDataProvider(),
            'clubId' => $customerPlace->place_id
        ]);

        return $this->responseJson([
            'html' => $customerList,
        ]);
    }

    public function actionUpdate($id)
    {
        $attributes = \Yii::$app->request->post();
        $customer = Customer::findOne($id);

        $userService = new UserService();

        if (\Yii::$app->request->isPost) {
            $userService->updateByData($customer, $attributes);
            return $this->redirect('/customer/index');
        }

        return $this->render('create', [
            'customer' => $customer,
            'roles' => AccessRole::getAll(),
        ]);
    }

    public function actionDelete($id)
    {
        $isAjax = $this->getParam('isAjax');

        $userService = new UserService();
        $userService->deleteById((int)$id);

        if ($isAjax) {
            return $this->responseListing(new CustomerSearch(), '/customer/index');
        }

        return $this->redirect('/customer/index');
    }
}