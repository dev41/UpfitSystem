<?php
namespace app\src\entities\trigger;

use app\src\entities\AbstractModel;

/**
 * Class TriggerPosition
 * @package app\src\entities\notification
 *
 * @property int $trigger_id
 * @property int $position_id
 */
class TriggerPosition extends AbstractModel
{

    public static function tableName()
    {
        return 'trigger_position';
    }

    public function rules()
    {
        return [
            [['trigger_id', 'position_id'], 'required'],
            [['trigger_id', 'position_id'], 'integer'],
        ];
    }

}