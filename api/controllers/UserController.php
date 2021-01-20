<?php
namespace api\controllers;

use app\src\entities\customer\CustomerPlace;
use app\src\entities\user\User;
use app\src\exception\ApiException;
use app\src\library\BaseApiController;
use app\src\service\CustomerPlaceService;
use app\src\service\UserEventService;
use app\src\service\UserService;

class UserController extends BaseApiController
{
    public function behaviors()
    {
        $exceptedActions = ['login', 'login-fb', 'register-fb', 'register'];

        $this->actionAccessConfig
            ->setAuthExpect($exceptedActions)
            ->setControlExpect(array_merge($exceptedActions, ['logout']))
        ;

        return parent::behaviors();
    }

    public function actionLogin()
    {
        if (!$this->getParam('username') && !$this->getParam('email')) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'email']);
        }

        if (!$this->getParam('password')) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'password']);
        }

        $identity = $this->getParam('email', $this->getParam('username', $this->getParam('identity')));
        $password = $this->getParam('password');

        if (!$password) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'password']);
        }

        $userService = new UserService();
        $user = $userService->login($identity, $password);

        if (!$user) {
            throw new ApiException(ApiException::IDENTITY_OR_PASSWORD_INCORRECT);
        }

        $userInfo = User::getUserInfoById($user->id);

        return $this->response([
            'user' => $user->toArray(),
            'info' => $userInfo,
            ], (bool) $user);
    }

    public function actionLogout()
    {
        $userService = new UserService();
        $userService->logout();

        return $this->response();
    }

    public function actionRegister()
    {
        $attributes = $this->getParams();

        if (!isset($attributes['username']) && !isset($attributes['email'])) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'email']);
        }

        if (!isset($attributes['password'])) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'password']);
        }

        $userService = new UserService();
        $user = $userService->register($attributes);

        return $this->response([
            'user' => $user->toArray(),
        ], (bool) $user);
    }

    public function actionLoginFb()
    {
        $fbUserId = $this->getParam('id', null);

        if (!$fbUserId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'id']);
        }

        $fbToken = $this->getParam('token', null);

        if (!$fbToken) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'token']);
        }

        $userService = new UserService();
        $user = $userService->loginFBUser($fbUserId, $fbToken);

        return $this->response([
            'user' => $user->toArray(),
        ], (bool) $user);
    }

    public function actionRegisterFb()
    {
        $fbUserId = $this->getParam('id', null);

        if (!$fbUserId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'id']);
        }

        $fbToken = $this->getParam('token', null);

        if (!$fbToken) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'token']);
        }

        $data = $this->getParam('data', []);

        $userService = new UserService();
        $user = $userService->registerFBUser($fbUserId, $fbToken, $data);

        return $this->response([
            'user' => $user->toArray(),
        ], (bool) $user);
    }

    public function actionUpdate()
    {
        $userId = $this->getParam('id');
        $data = $this->getParam('data');

        if (!$userId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'id']);
        }

        $user = User::findOne($userId);

        if (!$user) {
            throw new ApiException(ApiException::MODEL_NOT_FOUND, ['model' => 'user']);
        }

        if (!$data) {
            return $this->response([
                'user' => $user->toArray(),
            ]);
        }

        $userService = new UserService();
        $user = $userService->forceUpdateByData($user, $data);

        return $this->response([
            'user' => $user->toArray(),
        ]);
    }

    /**
     * @return array
     * @throws ApiException
     * @throws \app\src\exception\ModelValidateException
     */
    public function actionRequestCustomerToClub()
    {
        // @todo need to rework
        $clubId = $this->getParam('club_id', null);
        $cardNumber = $this->getParam('card_number', null);

        if (!$clubId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'club_id']);
        }

        $isCustomerExistInClub = CustomerPlace::findOne(['user_id' => \Yii::$app->user->getId(), 'place_id' => $clubId]);
        if ($isCustomerExistInClub) {
            throw new ApiException(ApiException::RELATION_ALREADY_EXIST, ['relation' => 'customer-club']);
        }

        $customerPlaceService = new CustomerPlaceService();
        $customerPlaceService->requestCustomerToClub(\Yii::$app->user->getId(), $clubId, $cardNumber);

        return $this->response();
    }


    /**
     * @return array
     * @throws ApiException
     */
    public function actionLeaveClub()
    {
        // @todo need to rework auto implement to likpay (refund)
        $clubId = $this->getParam('club_id', null);
        $leaveEvents = $this->getParam('leave_events', false);

        $userId = \Yii::$app->user->getId();

        if (!$clubId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'club_id']);
        }

        $isCustomerExistInClub = CustomerPlace::findOne(['user_id' => $userId, 'place_id' => $clubId]);
        if (!$isCustomerExistInClub) {
            throw new ApiException(ApiException::CUSTOMER_NOT_IN_CLUB);
        }

        $customerPlaceService = new CustomerPlaceService();
        $customerPlaceService->rejectCustomerFromClub($userId, $clubId);

        if ($leaveEvents) {
            $userEventService = new UserEventService();
            $userEventService->deleteUserEventByDate($userId);
        }

        return $this->response();
    }


    public function actionGetUserInfo()
    {
        $userId = $this->getParam('user_id');

        if (!$userId) {
            throw new ApiException(ApiException::REQUIRED_PARAMS_NOT_FOUND, ['param' => 'user_id']);
        }

        $user = User::findOne(['id' => $userId]);

        if (!$user) {
            throw new ApiException(ApiException::MODEL_NOT_FOUND, ['model' => 'user']);
        }

        $userInfo = User::getUserInfoById($userId);

        return $this->response([
            'user' => $user->toArray(),
            'info' => $userInfo,
        ], (bool) $user);
    }
}