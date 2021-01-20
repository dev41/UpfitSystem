<?php
namespace app\src\entities\user;

use app\src\entities\AbstractModel;

/**
 * Class UserEvent
 * @package app\src\entities\
 *
 * @property int $user_id
 * @property int $event_id
 * @property int $status
 */
class UserEvent extends AbstractModel
{
    const STATUS_PENDING_FOR_PAYMENT = 0;
    const STATUS_CONFIRMED = 1;

    public static function tableName()
    {
        return 'user_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'event_id', 'status'], 'required'],
            [['user_id', 'event_id', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'event_id' => 'Event ID',
            'status' => 'Status',
        ];
    }
}