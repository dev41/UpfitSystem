<?php
namespace app\src\entities\trigger;

/**
 * Class Newsletter
 */
class Newsletter extends Trigger
{
    const IS_NEWSLETTER = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_by', 'template', 'clubsIds', 'types'], 'required'],
            [['sender_type'], 'required', 'when' => function () {
                $result = false;
                foreach ($this->getTypes() as $type) {
                    $result = $type === self::TYPE_EMAIL_NOTIFICATION;
                }
                return $result;
            }],
            [['created_by', 'updated_by', 'sender_type'], 'integer'],
            [['name', 'template', 'sender_email', 'title'], 'string'],
            [['created_at', 'updated_at', 'types', 'to_user_type', 'to_staff', 'to_customers', 'positions', 'roles', 'users', 'advanced_filters', 'is_newsletter', 'clubsIds'], 'safe'],
        ];
    }
}