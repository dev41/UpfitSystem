<?php
namespace app\src\helpers;

class UrlHelper
{
    public static function getAbsoluteBaseUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
    }
}