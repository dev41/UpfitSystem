<?php
namespace app\src\entities\purchase;

use app\src\entities\AbstractModel;

/**
 * Class Balance
 * @package app\src\entities\purchase
 *
 * @property int $id
 * @property int $user_id
 * @property int $place_id
 * @property number $amount
 */
class Balance extends AbstractModel
{
    public static function tableName()
    {
        return 'balance';
    }

    public function rules()
    {
        return [
            [['user_id', 'place_id'], 'required'],
            [['id', 'user_id', 'place_id'], 'integer'],
            [['amount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
        ];
    }
}