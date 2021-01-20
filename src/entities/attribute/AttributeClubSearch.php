<?php
namespace app\src\entities\attribute;

use app\src\entities\ISearchModel;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class AttributeClubSearch
 * @inheritdoc
 */
class AttributeClubSearch extends Attribute implements ISearchModel
{
    private $parent_id;

    public function __construct(int $clubId, array $config = [])
    {
        $this->parent_id = $clubId;
        parent::__construct($config);
    }

    public function getSearchDataProvider(array $params = []): ActiveDataProvider
    {
        $query = (new Query())
            ->from(['a' => self::tableName()])
            ->andWhere([
                'a.parent_id' => $this->parent_id,
            ])
            ->select([
                'id' => 'a.id',
                'parent_id' => 'a.parent_id',
                'name' => 'a.name',
                'type' => 'a.type',
                'value' => 'a.value',
            ])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}