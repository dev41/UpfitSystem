<?php
namespace app\src\entities\user;

use app\src\entities\AbstractModel;

/**
 * Class UserActivity
 *
 * @property int $user_id
 * @property int $activity_id
 * @property int $is_staff
 *
 * @property User $user
 */
class UserActivity extends AbstractModel
{
    public static function tableName()
    {
        return 'user_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'activity_id', 'is_staff'], 'required'],
            [['user_id', 'activity_id', 'is_staff'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'activity_id' => 'Activity ID',
            'is_staff' => 'Is staff',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}