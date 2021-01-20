<?php
namespace app\src\entities\trigger;

use app\src\entities\AbstractModel;

/**
 * Class TriggerPlace
 * @package app\src\entities\notification
 *
 * @property int $trigger_id
 * @property int $place_id
 * @property int $type
 */
class TriggerPlace extends AbstractModel
{
    const TYPE_FILTER_CLUB = 0;
    const TYPE_PARENT_CLUB = 1;

    public static function tableName()
    {
        return 'trigger_place';
    }

    public function rules()
    {
        return [
            [['trigger_id', 'place_id'], 'required'],
            [['trigger_id', 'place_id', 'type'], 'integer'],
        ];
    }

}