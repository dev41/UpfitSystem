<?php
namespace app\src\entities\trigger;

use app\src\entities\AbstractModel;

/**
 * Class TriggerRole
 * @package app\src\entities\notification
 *
 * @property int $trigger_id
 * @property int $role_id
 */
class TriggerRole extends AbstractModel
{

    public static function tableName()
    {
        return 'trigger_role';
    }

    public function rules()
    {
        return [
            [['trigger_id', 'role_id'], 'required'],
            [['trigger_id', 'role_id'], 'integer'],
        ];
    }

}