<?php

namespace app\src\entities;

use yii\data\ActiveDataProvider;

interface ISearchModel
{
    const BASE_PAGINATION = 10;
    public function getSearchDataProvider(array $params = []): ActiveDataProvider;
}