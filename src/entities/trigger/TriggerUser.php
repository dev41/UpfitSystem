<?php
namespace app\src\entities\trigger;

use app\src\entities\AbstractModel;

/**
 * Class TriggerUser
 * @package app\src\entities\notification
 *
 * @property int $trigger_id
 * @property int $user_id
 */
class TriggerUser extends AbstractModel
{

    public static function tableName()
    {
        return 'trigger_user';
    }

    public function rules()
    {
        return [
            [['trigger_id', 'user_id'], 'required'],
            [['trigger_id', 'user_id'], 'integer'],
        ];
    }

}