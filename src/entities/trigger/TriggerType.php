<?php
namespace app\src\entities\trigger;

use app\src\entities\AbstractModel;

/**
 * Class TriggerType
 * @package app\src\entities\notification
 *
 * @property int $trigger_id
 * @property int $type
 * @property int $priority
 */
class TriggerType extends AbstractModel
{

    public static function tableName()
    {
        return 'trigger_type';
    }

    public function rules()
    {
        return [
            [['trigger_id', 'type', 'priority'], 'required'],
            [['trigger_id', 'type', 'priority'], 'integer'],
        ];
    }

}