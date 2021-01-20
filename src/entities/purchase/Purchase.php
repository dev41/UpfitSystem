<?php
namespace app\src\entities\purchase;

use app\src\entities\AbstractModel;

/**
 * Class Purchase
 *
 * @property int $id
 * @property int $user_id
 * @property int $place_id
 * @property int $type
 * @property int $pay_type
 * @property string $currency
 * @property int $status
 * @property int $expired_date
 * @property string $action
 * @property string $response
 * @property int $product_type
 * @property int $product_id
 * @property int $created_at
 * @property int $updated_at
 * @property number $amount
 */
class Purchase extends AbstractModel
{
    const TYPE_BUY = 1;
    const TYPE_SUBSCRIBE = 2;

    const TYPE_BUY_LABEL = 'pay';
    const TYPE_SUBSCRIBE_LABEL = 'subscribe';

    const PAY_TYPE_LIQPAY = 1;
    const PAY_TYPE_CARD = 2;
    const PAY_TYPE_PHONE = 3;
    const PAY_TYPE_CASH = 4;

    const PRODUCT_TYPE_TRAINING = 0;

    const STATUS_CREATE = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;

    const STATUS_CREATE_LABEL = 'create';
    const STATUS_SUCCESS_LABEL = 'success';
    const STATUS_FAILURE_LABEL = 'failure';

    const STATUS_LABELS = [
        self::STATUS_CREATE => self::STATUS_CREATE_LABEL,
        self::STATUS_SUCCESS => self::STATUS_SUCCESS_LABEL,
        self::STATUS_FAILURE => self::STATUS_FAILURE_LABEL,
    ];

    public static function tableName()
    {
        return 'purchase';
    }

    public function rules()
    {
        return [
            [['user_id', 'place_id', 'created_at'], 'required'],
            [['id', 'type', 'status', 'user_id', 'place_id', 'pay_type', 'product_type', 'product_id'], 'integer'],
            [['amount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            [['currency'], 'string', 'max' => 10],
            [['action', 'response'], 'string', 'max' => 255],
            [['updated_at', 'expired_date'], 'safe'],
        ];
    }
}