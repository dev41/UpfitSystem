<?php
namespace app\src\entities\customer;

use app\src\entities\AbstractModel;

/**
 * Class CustomerPlace
 * @package app\src\entities
 *
 * @property int $user_id
 * @property int $place_id
 * @property int $card_number
 * @property int $status
 * @property string $created_at
 */
class CustomerPlace extends AbstractModel
{
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;

    public $validateOnlyDBFields = false;
    public $users = [];

    public static function tableName()
    {
        return 'customer_place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'place_id',], 'required'],
            [['users'], 'required', 'when' => function () {
                return !$this->validateOnlyDBFields;
            }],
            [['user_id', 'place_id', 'status'], 'integer'],
            [['card_number'], 'string', 'max' => 50],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => \Yii::t('app', 'User ID'),
            'place_id' => \Yii::t('app', 'Place ID'),
            'card_number' => \Yii::t('app', 'Card Number'),
            'status' => \Yii::t('app', 'Status'),
            'created_at' => \Yii::t('app', 'Created At'),
        ];
    }
}