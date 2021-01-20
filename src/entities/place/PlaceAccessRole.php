<?php
namespace app\src\entities\place;

use app\src\entities\AbstractModel;

/**
 * Class PlaceAccessRole
 * @package app\src\entities\place
 *
 * @property int $access_role_id
 * @property int $place_id
 */
class PlaceAccessRole extends AbstractModel
{
    public static function tableName()
    {
        return 'place_access_role';
    }

    public function rules()
    {
        return [
            [['access_role_id', 'place_id'], 'required'],
            [['access_role_id', 'place_id'], 'int'],
        ];
    }
}