<?php
namespace app\src\library;

use app\src\entities\AbstractModel;

class ClubSearchBuilder extends SearchBuilder
{
    public function __construct(AbstractModel $model, array $conditions = [], array $pagination = [], array $sort = [])
    {
        parent::__construct($model, $conditions, $pagination, $sort);
    }
}