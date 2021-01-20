<?php

namespace app\src\entities\attribute;

use app\src\entities\AbstractModel;
use app\src\entities\place\Place;
use yii\db\Query;

/**
 * Class Attribute
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $type
 * @property string $value
 */
class Attribute extends AbstractModel
{
    const TYPE_STRING = 1;
    const TYPE_NUMBER = 2;
    const TYPE_BOOLEAN = 3;

    const TYPE_LABEL_STRING = 'String';
    const TYPE_LABEL_NUMBER = 'Number';
    const TYPE_LABEL_BOOLEAN = 'Boolean';

    public static function getTypeLabels(): array
    {
        return [
            self::TYPE_STRING => \Yii::t('app', self::TYPE_LABEL_STRING),
            self::TYPE_NUMBER => \Yii::t('app', self::TYPE_LABEL_NUMBER),
            self::TYPE_BOOLEAN => \Yii::t('app', self::TYPE_LABEL_BOOLEAN),
        ];
    }

    public static function getLabelByType($type): string
    {
        $labels = self::getTypeLabels();
        return $labels[$type];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attribute';
    }

    public static function getAvailableAttributesNames()
    {
        $query = new Query();
        $query
            ->from(['a' => Attribute::tableName()])
            ->select([
                'name' => 'a.name',
            ])->distinct();
        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'type'], 'integer'],
            [['name', 'parent_id', 'type', 'value'], 'required'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Place::class, ['id' => 'parent_id']);
    }
}