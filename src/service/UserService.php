<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\access\AccessRole;
use app\src\entities\customer\Customer;
use app\src\entities\customer\CustomerPlace;
use app\src\entities\image\Image;
use app\src\entities\staff\Staff;
use app\src\entities\user\FBUser;
use app\src\entities\user\User;
use app\src\exception\ApiException;
use app\src\exception\ModelValidateException;
use app\src\helpers\UrlHelper;
use app\src\library\AccessChecker;
use yii\helpers\ArrayHelper;

/**
 * Class UserService
 */
class UserService extends AbstractService
{
    public function getOwnerListOptions(): array
    {
        $owners = User::getOwnerList();
        return ArrayHelper::map($owners, 'id', 'username');
    }

    public function getModelByType(int $modelType): User
    {
        switch ($modelType) {
            case User::MODEL_STAFF: return new Staff();
            case User::MODEL_CUSTOMER: return new Customer();
            case User::MODEL_FBUser: return new FBUser();
        }

        return new User();
    }

    public function getModelByData(array $data): User
    {
        switch (true) {
            case array_key_exists(Staff::getShortClassName(), $data): return new Staff();
            case array_key_exists(Customer::getShortClassName(), $data): return new Customer();
            case array_key_exists(FBUser::getShortClassName(), $data): return new FBUser();
        }

        return new User();
    }

    public function createCustomerByData(array $data, int $modelType = null, $dataScope = null): Customer
    {
        /** @var Customer $customer */
        $customer = $this->createByData($data, $modelType, $dataScope);

        $customerPlace = new CustomerPlace();
        $customerPlace->load($data);
        $customerPlace->user_id = $customer->id;
        $customerPlace->status = CustomerPlace::STATUS_CONFIRMED;

        $customerPlace->validateOnlyDBFields = true;
        $customerPlace->save();

        return $customer;
    }

    public function createByData(array $data, int $modelType = null, $dataScope = null): User
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $user = $modelType === null ? $this->getModelByData($data) : $this->getModelByType($modelType);

            switch ($modelType) {
                case User::MODEL_FBUser:
                    $user->validateBehaviour = User::VALIDATE_CREATE_BY_FB;
                    break;
                default: $user->validateBehaviour = User::VALIDATE_CREATE;
            }

            $user->load($data, $dataScope);

            if (isset($user->username) && User::isUsernameExist($user->username)) {
                throw new ApiException(ApiException::PARAM_MUST_BE_UNIQUE, ['param' => 'username']);
            }

            if (isset($user->email) && User::isEmailExist($user->email)) {
                throw new ApiException(ApiException::PARAM_MUST_BE_UNIQUE, ['param' => 'email']);
            }

            $role = $user->getRole()->one();
            if (empty($role)) {
                $roleAppUser = AccessRole::getRoleBySlug(AccessRole::ROLE_APP_USER);
                $user->role_id = $roleAppUser->id;
            } elseif ($role['slug'] === AccessRole::ROLE_SUPER_ADMIN && !AccessChecker::isSuperAdmin()) {
                throw new \InvalidArgumentException('role_id');
            }

            if (!$user->email) {
                $user->email = null;
            }

            if (!empty($user->password)) {
                $user->setPassword($user->password);
            }
            $user->generateAuthKey();
            $user->generateApiAuthKey();

            if (!$user->created_at) {
                $user->created_at = AbstractModel::getDateTimeNow();
            }

            if (!$user->created_by && isset(\Yii::$app->user->id)) {
                $user->created_by = \Yii::$app->user->getId();
            } else {
                $user->created_by = 1;
            }

            if (!$user->status) {
                $user->status = User::STATUS_ACTIVE;
            }

            $user->save();

            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $user;
    }

    /**
     * @param Customer $customer
     * @param array $data
     * @return Customer
     * @throws ModelValidateException
     */
    public function updateCustomerByData(Customer $customer, array $data): Customer
    {
        /** @var Customer $customer */
        $customer = $this->updateByData($customer, $data);

        $customerPlace = new CustomerPlace();
        $customerPlace->load($data);

        $customerPlace = CustomerPlace::findOne([
            'place_id' => $customerPlace->place_id,
            'user_id' => $customerPlace->user_id,
        ]);

        if (!$customerPlace) {
            $customerPlace = new CustomerPlace();
        }

        $customerPlace->validateOnlyDBFields = true;
        $customerPlace->load($data);
        $customerPlace->save();

        return $customer;
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function updateByData(User $user, array $data): User
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $user->load($data);

            $role = $user->getRole()->one();
            if ($role['slug'] === AccessRole::ROLE_SUPER_ADMIN && !AccessChecker::isSuperAdmin()) {
                throw new \InvalidArgumentException('role_id');
            }

            $user->updated_by = \Yii::$app->user->getId();
            $user->updated_at = AbstractModel::getDateTimeNow();

            $imageService = new ImageService();
            $imageService->uploadImages($user, $_FILES, Image::TYPE_USER_PHOTO);
            $imageService->uploadLogo($user, $_FILES, Image::TYPE_USER_AVATAR);

            $user->save();
            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }

        return $user;
    }

    public function forceUpdateByData(User $user, array $data): User
    {
        $availableAttributes = [
            'username',
            'email',
            'fb_user_id',
            'push_token',
            'device_id',
            'role_id',
            'first_name',
            'last_name',
            'address',
            'description',
            'phone',
            'birthday',
            'avatar',
            'status',
            'pushToken',
        ];

        if (!empty($data['password'])) {
            $user->setPassword($data['password']);
        }

        if (!empty($data['device_id'])) {
            $oldUsersDevice = User::findOne(['device_id' => $data['device_id']]);
            if ($oldUsersDevice && $oldUsersDevice->id !== $user->id) {
                $oldUsersDevice->device_id = null;
                $oldUsersDevice->push_token = null;
                $oldUsersDevice->save();
            }
        }

        $data = array_intersect_key($data, array_flip($availableAttributes));

        $user->load($data, '');

        $user->updated_by = \Yii::$app->user->getId();
        $user->updated_at = AbstractModel::getDateTimeNow();

        $user->save(false);

        return $user;
    }

    public function deleteById(int $id): bool
    {
        $user = User::findOne($id);
        $user->status = User::STATUS_DELETED;
        return $user->save(false);
    }

    /**
     * @return User[] array
     */
    public function getTrainers(): array
    {
        return User::getTrainers();
    }

    /**
     * @param string $identity
     * @param string $password
     * @return null|User
     * @throws ModelValidateException
     */
    public function login(string $identity, string $password)
    {
        /** @var User $user */
        $user = User::findByUserNameOrEmail($identity);

        if (!$user) {
            return null;
        }

        if ($user->validatePassword($password)) {
            $user->generateApiAuthKey();
            $user->updated_at = AbstractModel::getDateNow();
            $user->save(false);
            \Yii::$app->user->login($user);
            return $user;
        }

        return null;
    }

    public function logout()
    {
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();

        if (!$user) {
            return;
        }

        $user->fb_token = null;
        $user->generateApiAuthKey();
        $user->save();

        \Yii::$app->user->logout();
    }

    /**
     * Try to get user FB ID by token using FB API
     *
     * @param string $token
     * @param string $userId
     * @return array
     * @throws ApiException
     */
    public function getFBUserByAPI(string $userId, string $token)
    {
        if (!$token || !$userId) {
            return null;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v3.0/' . $userId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        $data = (array) json_decode($ret, true);
        curl_close($ch);

        if (!array_key_exists('id', $data)) {
            throw new ApiException(ApiException::FB_API_BAD_RESPONSE);
        }

        return array_key_exists('id', $data) && array_key_exists('name', $data) ? $data : null;
    }

    public function register(array $userData): User
    {
        return $this->createByData($userData, null, '');
    }

    /**
     * @param string $userId
     * @param string $token
     * @return null|User
     * @throws ModelValidateException
     * @throws ApiException
     */
    public function loginFBUser(string $userId, string $token)
    {
        $user = FBUser::findOne([
            'fb_user_id' => $userId,
        ]);

        if (!$user) {
            throw new ApiException(ApiException::ID_OR_TOKEN_INCORRECT);
        }

        if ($user->fb_token === $token) {
            \Yii::$app->user->login($user);
            return $user;
        }

        if ($this->getFBUserByAPI($userId, $token)) {
            $user->fb_token = $token;
            $user->generateApiAuthKey();
            $user->validateBehaviour = User::VALIDATE_LOGIN_BY_FB;
            $user->save();
            \Yii::$app->user->login($user);
            return $user;
        }

        return null;
    }

    /**
     * @param string $userId
     * @param string $token
     * @param array $data
     * @return User|null
     * @throws \Exception
     */
    public function registerFBUser(string $userId, string $token, array $data)
    {
        if (!$this->getFBUserByAPI($userId, $token)) {
            throw new \Exception('Cannot get fb user by userId "' . $userId . '" and token "' . $token . '"');
        }

        $data = $data + [
            'fb_user_id' => $userId,
            'fb_token' => $token,
        ];

        return $this->createByData($data, User::MODEL_FBUser, '');
    }

    public function sendResetPasswordLink($userIdentifier): bool
    {
        /** @var User $user */
        $user = User::find()
            ->where([
                'username' => $userIdentifier,
            ])
            ->orWhere([
                'email' => $userIdentifier,
            ])
            ->one();

        if (!$user || !$user->email) {
            return false;
        }

        $user->generatePasswordResetToken();
        $user->save();

        $restoreUrl = UrlHelper::getAbsoluteBaseUrl() . '/site/restore-password?t=' . $user->password_reset_token;

        \Yii::$app->mailer->compose()
            ->setFrom(['mailer@gmail.com' => \Yii::$app->params['system_email']])
            ->setTo($user->email)
            ->setSubject('reset-password')
            ->setTextBody('restore url: ' . $restoreUrl)
            ->send();

        return true;
    }

    public function checkPasswordResetToken(string $token): bool
    {
        $user = User::find()->where(['password_reset_token' => $token])->one();

        if (!$user) {
            return false;
        }

        $tokenParts = explode('_', $token);

        if (count($tokenParts) < 2) {
            return false;
        }

        $tokenCreatedAt = base64_decode($tokenParts[1]);
        $tokenPeriod = (int) (microtime(true) - $tokenCreatedAt);

        if ($tokenPeriod > \Yii::$app->params['reset_password_token_expire_period']) {
            return false;
        }

        return true;
    }

    public function setNewPasswordByToken(string $token, string $password): bool
    {
        $tokenIsValid = $this->checkPasswordResetToken($token);

        if (!$tokenIsValid) {
            return false;
        }

        /** @var User $user */
        $user = User::find()->where(['password_reset_token' => $token])->one();
        $user->resetPassword($password);
        $user->save();

        return true;
    }
}