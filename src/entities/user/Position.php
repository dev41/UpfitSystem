<?php
namespace app\src\entities\user;

use app\src\entities\AbstractModel;
use app\src\entities\staff\Staff;
use app\src\entities\staff\StaffPositionPlace;
use yii\helpers\ArrayHelper;

/**
 * Class Position
 * @package app\src\entities\
 *
 * @property int $id
 * @property string $name
 * @property string $type
 */
class Position extends AbstractModel
{
    const POSITION_OWNER = 'owner';
    const POSITION_ADMIN = 'admin';
    const POSITION_MANAGER = 'manager';
    const POSITION_TRAINER = 'trainer';

    const TYPE_STAFF = 1;
    const TYPE_CUSTOMER = 2;

    private static $positions = [];
    private static $positionNames = [];

    public static function tableName()
    {
        return 'position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'type'], 'required'],
            [['id', 'type'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
        ];
    }

    protected static function loadPositions()
    {
        if (self::$positions) {
            return;
        }

        self::$positions = self::find()->all();
        self::$positionNames = ArrayHelper::map(self::$positions, 'id', 'name');
    }

    public static function getPositionNames(): array
    {
        self::loadPositions();
        return self::$positionNames;
    }

    public static function getIdByName(string $name): int
    {
        $positions = array_flip(self::getPositionNames());
        return $positions[$name];
    }

    /**
     * @param string $name
     * @return null|static
     */
    public static function findByName(string $name)
    {
        return self::findOne(['name' => $name]);
    }

    public static function getPositionsByStaffId(int $staffId)
    {
        return self::find()
            ->alias('p')
            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'spp.position_id = p.id')
            ->leftJoin(['u' => Staff::tableName()], 'spp.user_id = u.id')
            ->where(['u.id' => $staffId])
            ->all()
        ;
    }

}