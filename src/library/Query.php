<?php

namespace app\src\library;

class Query extends \yii\db\Query
{
    protected function isEmpty($value)
    {
        $value = trim(str_replace('%', '', $value));
        return parent::isEmpty($value);
    }
}