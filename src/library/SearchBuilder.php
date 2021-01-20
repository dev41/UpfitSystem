<?php
namespace app\src\library;

use yii\data\ActiveDataProvider;
use yii\db\Query;

class SearchBuilder
{
    /** @var Query */
    public $query;
    /** @var array */
    protected $conditions;
    /** @var array */
    protected $pagination;
    /** @var array */
    protected $sort;

    public function __construct(Query $query, array $conditions = [], array $pagination = [], array $sort = [])
    {
        $this->query = $query;
        $this->conditions = $conditions;
        $this->pagination = $pagination;
        $this->sort = $sort;
    }

    public function getDataProvider(): ActiveDataProvider
    {
        $this->query->andWhere($this->conditions);

        $this->query->orderBy($this->buildSort());

        return new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => $this->pagination,
        ]);
    }

    public function getModels()
    {
        return $this->getDataProvider()->getModels();
    }


    public function buildSort(): string
    {
        $sort = [];
        foreach ($this->sort as $key => $value) {
            $sort[] = $key . ' ' . $value;
        }
        return implode(', ', $sort);
    }

}