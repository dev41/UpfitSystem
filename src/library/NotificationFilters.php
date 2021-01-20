<?php
namespace app\src\library;

class NotificationFilters
{
    protected static $availablePhpFilter = [
        'ucfirst',
        'lcfirst',
    ];

    public static function implode($value, $glue): string
    {
        return implode($glue, $value);
    }

    public static function date($value, $format): string
    {
        $value = strtotime($value);
        return date($format, $value);
    }

    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, self::$availablePhpFilter)) {
            return call_user_func_array($name, $arguments);
        }

        throw new \Exception('Filter ' . $name . ' is not available here!');
    }
}