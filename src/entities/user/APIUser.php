<?php
namespace app\src\entities\user;

class APIUser extends User
{
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->api_auth_key;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['api_auth_key' => $token]);
    }
}