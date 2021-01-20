<?php
namespace app\controllers;

use app\src\entities\customer\Customer;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\customer\CustomerPlaceSearch;
use app\src\exception\ModelValidateException;
use app\src\library\BaseController;
use app\src\service\CustomerPlaceService;
use app\src\service\UserService;

class CustomerPlaceController extends BaseController
{
    public function actionAddCustomersForm()
    {
        $clubId = (int) $this->getParam('clubId');

        $customerPlaceService = new CustomerPlaceService();
        $availableCustomers = $customerPlaceService->getAvailableNewCustomerIdsForClub($clubId);

        $customersForm = $this->renderAjax('/club/partial/customer-club-form', [
            'customerPlace' => new CustomerPlace(),
            'availableCustomers' => $availableCustomers,
        ]);

        return $this->responseJson([
            'html' => $customersForm,
        ]);
    }

    public function actionGetCreateForm()
    {
        $clubId = (int) $this->getParam('clubId');

        $customer = new Customer();
        $customerPlace = new CustomerPlace();
        $customerPlace->place_id = $clubId;

        $form = $this->renderAjax('/customer/partial/customer_form', [
            'customer' => $customer,
            'customerPlace' => $customerPlace,
            'renderAjax' => true,
        ]);

        return $this->responseJson([
            'html' => $form,
        ]);
    }

    public function actionGetUpdateForm()
    {
        $clubId = (int) $this->getParam('club_id');
        $customerId = (int) $this->getParam('customer_id');

        $customer = Customer::findOne(['id' => $customerId]);

        $customerPlace = CustomerPlace::findOne([
            'user_id' => $customerId,
            'place_id' => $clubId,
        ]);

        $form = $this->renderAjax('/customer/partial/customer_form', [
            'customer' => $customer,
            'customerPlace' => $customerPlace,
            'renderAjax' => true,
        ]);

        return $this->responseJson([
            'html' => $form,
        ]);
    }

    /**
     * @return array
     * @throws ModelValidateException
     */
    public function actionAddCustomer()
    {
        $attributes = $this->getParams();
        $clubId = (int) $this->getParam('clubId');

        $customerPlace = new CustomerPlace();
        $customerPlace->load($attributes);
        $customerPlace->place_id = $clubId;
        if ($customerPlace->users) {
            $customerPlace->user_id = reset($customerPlace->users);
        }
        $customerPlace->throwExceptionIfNotValid();

        $customerPlaceService = new CustomerPlaceService();
        $customerPlaceService->addCustomersToClub($clubId, $customerPlace->users);

        $clientsSearch = new CustomerPlaceSearch($clubId);

        $customersList = $this->renderAjax('/club/partial/customers-list', [
            'clientsDataProvider' => $clientsSearch->getSearchDataProvider(),
        ]);

        return $this->responseJson([
            'html' => $customersList,
        ]);
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function actionUpdateCustomer($id)
    {
        $attributes = \Yii::$app->request->post();

        $customer = Customer::findOne($id);
        $userService = new UserService();
        $userService->updateCustomerByData($customer, $attributes);

        $customerPlace = new CustomerPlace();
        $customerPlace->load($attributes);
        $clientsSearch = new CustomerPlaceSearch((int) $customerPlace->place_id);

        $customersList = $this->renderAjax('/club/partial/customers-list', [
            'clientsDataProvider' => $clientsSearch->getSearchDataProvider(),
        ]);

        return $this->responseJson([
            'html' => $customersList,
        ]);
    }

    public function actionDeleteCustomer()
    {
        $clubId = (int) $this->getParam('clubId');
        $userId = (int) $this->getParam('userId');

        $customerPlaceService = new CustomerPlaceService();
        $customerPlaceService->rejectCustomerFromClub($userId, $clubId);

        $clientsSearch = new CustomerPlaceSearch($clubId);

        $customersList = $this->renderAjax('/club/partial/customers-list', [
            'clientsDataProvider' => $clientsSearch->getSearchDataProvider(),
        ]);

        return $this->responseJson([
            'html' => $customersList,
        ]);
    }

    /**
     * @return array
     * @throws ModelValidateException
     */
    public function actionSetStatus()
    {
        $clubId = (int) $this->getParam('clubId');
        $userId = (int) $this->getParam('userId');

        $customerPlaceService = new CustomerPlaceService();
        $customerPlaceService->toggleStatusByUserIdAndPlaceId($userId, $clubId);
        return $this->responseJson();
    }
}