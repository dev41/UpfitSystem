<?php
namespace app\src\service;
use app\src\library\ApiApplication;

/**
 * Class AbstractService
 */
abstract class AbstractService
{
    public static $services = [];

    /**
     * to support Unit Tests
     * @param string $name
     * @return AbstractService
     */
    public static function getService(string $name)
    {
        if (array_key_exists($name, self::$services)) {
            return self::$services[$name];
        }

        return self::$services[$name] = new $name();
    }

    /**
     * @param string $name
     * @param AbstractService $value
     */
    public static function setService(string $name, $value)
    {
        self::$services[$name] = $value;
    }

    public static function isApiApplication(): bool
    {
        return \Yii::$app instanceof ApiApplication;
    }

    public function getLoggedUserId(): int
    {
        return \Yii::$app->user->id;
    }

}