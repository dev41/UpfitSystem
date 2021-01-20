<?php
namespace app\src\service;

use app\src\entities\purchase\Balance;
use app\src\entities\purchase\Purchase;

/**
 * Class BalanceService
 */
class BalanceService extends AbstractService
{
    public function updateBalanceByPurchase(Purchase $purchase, Balance $balance = null): Balance
    {
        if (!$balance) {
            $balance = new Balance();
        }

        if ($purchase->status === Purchase::STATUS_SUCCESS) {
            $balance->amount += $purchase->amount;
        }

        $balance->user_id = $purchase->user_id;
        $balance->place_id = $purchase->place_id;
        $balance->save();

        return $balance;
    }
}