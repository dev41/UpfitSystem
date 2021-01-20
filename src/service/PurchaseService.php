<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\place\Club;
use app\src\entities\purchase\Purchase;
use app\src\library\LiqPay;

/**
 * Class PurchaseService
 */
class PurchaseService extends AbstractService
{
    const ID_HASH = 'upfitsystem';

    public function updateByData($resultPayment): Purchase
    {
        $orderId = array_pop(explode('_', $resultPayment->order_id));
        $purchase = Purchase::findOne(['id' => $orderId]);
        $purchase->status = ($resultPayment->status === Purchase::STATUS_SUCCESS_LABEL) ? Purchase::STATUS_SUCCESS : Purchase::STATUS_FAILURE;
        $purchase->response = implode(', ', $resultPayment);
        $purchase->pay_type = $resultPayment->pay_type;
        $purchase->updated_at = AbstractModel::getDateTimeNow();

        switch ($resultPayment->paytype) {
            case 'card':
                $purchase->pay_type = Purchase::PAY_TYPE_CARD;
                break;
            case 'liqpay':
                $purchase->pay_type = Purchase::PAY_TYPE_LIQPAY;
                break;
            case 'phone':
                $purchase->pay_type = Purchase::PAY_TYPE_PHONE;
                break;
            case 'cash':
                $purchase->pay_type = Purchase::PAY_TYPE_CASH;
                break;
        }

        $purchase->save();
        return $purchase;
    }

    /**
     * @param int $clubId
     * @param int $customerId
     * @param array $data
     * @return array
     * @throws \app\src\exception\ModelValidateException
     */
    public function getPaymentUrl(int $clubId, int $customerId, array $data): array
    {
        $club = Club::findOne(['id' => $clubId]);

        $purchase = new Purchase();
        $purchase->user_id = $customerId;
        $purchase->place_id = $clubId;
        $purchase->expired_date = $data['expired_date'];
        $purchase->product_type = $data['product_type'];
        $purchase->product_id = $data['product_id'];
        $purchase->currency = LiqPay::CURRENCY_UAH;
        $purchase->type = Purchase::TYPE_BUY;
        $purchase->amount = $data['amount'];
        $purchase->action = Purchase::TYPE_BUY_LABEL;
        $purchase->created_at = AbstractModel::getDateTimeNow();
        $purchase->save();

        $liqPay = new LiqPay($club->public_key, $club->private_key);
        $params = [
            'public_key' => $club->public_key,
            'server_url' =>  self::getPostBackUrl() . 'place_id=' . $club->id,
            'result_url' =>  'upfit://club',
            'version' => '3',
            'action' => Purchase::TYPE_BUY_LABEL,
            'amount' => $data['amount'],
            'sandbox' => true,
            'currency' => LiqPay::CURRENCY_UAH,
            'description' => 'Pay for training',
            'order_id' => self::getLiqPayOrderId($purchase->id),
            'expired_date' => $data['expired_date']
        ];

        return [
            'url' => $liqPay->getPaymentUrl($params)
        ];
    }

    public static function getPostBackUrl()
    {
        $liqPayParams = \Yii::$app->params['liqPay'];
        return YII_ENV === 'dev' ? $liqPayParams['post_back.dev'] : $liqPayParams['post_back.prod'];
    }

    public static function getLiqPayOrderId(int $id)
    {
        return self::ID_HASH . time() . '_' . $id;
    }
}