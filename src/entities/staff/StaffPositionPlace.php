<?php
namespace app\src\entities\staff;

use app\src\entities\AbstractModel;
use app\src\entities\user\Position;
use app\src\entities\user\User;

/**
 * Class StaffPositionPlace
 *
 * @property int $id
 * @property int $user_id
 * @property int $place_id
 * @property int $position_id
 *
 * @property User $user
 */
class StaffPositionPlace extends AbstractModel
{
    public $validateOnlyDBFields = false;
    public $positions;

    public static function tableName()
    {
        return 'staff_position_place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'place_id', 'position_id',], 'required'],
            [['user_id', 'place_id', 'position_id',], 'integer'],
            [['positions'], 'required', 'when' => function() { return !$this->validateOnlyDBFields; }],
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
            'place_id' => 'Place ID',
            'is_owner' => 'Is Owner',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getClubOwnerById($clubId)
    {
        return self::find()
            ->alias('upp')
            ->leftJoin(['p' => Position::tableName()], 'upp.position_id = p.id')
            ->where([
                'place_id' => $clubId,
                'p.name' => Position::POSITION_OWNER,
            ])->one();
    }

}