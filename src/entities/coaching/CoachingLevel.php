<?php
namespace app\src\entities\coaching;

use app\src\entities\AbstractModel;

/**
 * Class CoachingLevel
 * @package app\src\entities
 *
 * @property int $id
 * @property string $name
 */
class CoachingLevel extends AbstractModel
{
    public static function tableName()
    {
        return 'coaching_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}