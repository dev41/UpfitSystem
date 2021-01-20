<?php
namespace app\src\entities\user;

use app\src\entities\AbstractModel;

/**
 * Class UserCoaching
 *
 * @property int $id
 * @property int $user_id
 * @property int $coaching_id
 *
 * @property User $user
 */
class UserCoaching extends AbstractModel
{
    public static function tableName()
    {
        return 'user_coaching';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'coaching_id',], 'required'],
            [['user_id', 'coaching_id',], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'coaching_id' => 'Coaching ID',
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