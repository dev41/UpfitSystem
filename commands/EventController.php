<?php
namespace app\commands;

use app\src\entities\AbstractModel;
use app\src\entities\purchase\Purchase;
use app\src\entities\user\UserEvent;
use app\src\library\BaseCommands;

class EventController extends BaseCommands
{
    public function actionUpdateUserEventByPurchase()
    {
        $confirmed = 0;
        $deleted = 0;


        $date = date('Y-m-d H:i:s');
        $this->consoleLog('Date : ' . $date);

        $purchases = Purchase::find()
            ->where(['<', 'expired_date', $date])
            ->andWhere(['product_type' => Purchase::PRODUCT_TYPE_TRAINING])
            ->all();

        if (!$purchases) {
            $this->consoleLog('purchases were not found!');
            return;
        }

        $this->consoleLog('are being processed - '. count($purchases) . ' purchases...');
        /**
         * @var Purchase $purchase
         */
        foreach ($purchases as $key => $purchase) {

            $this->consoleLog('is being processed - '. $key . ' purchases...');

            $userEvent = UserEvent::findOne([
                'user_id' => $purchase->user_id,
                'event_id' => $purchase->product_id
            ]);

            $purchase->expired_date = null;
            $purchase->updated_at = AbstractModel::getDateTimeNow();

            if (!$userEvent) {
                $this->consoleLog('userEvent was not found!');

                $purchase->save();
                continue;
            }

            if ($purchase->status === Purchase::STATUS_SUCCESS) {
                $userEvent->status = UserEvent::STATUS_CONFIRMED;
                $userEvent->save(false);

                $confirmed ++;
                $this->consoleLog('purchase was confirm');
            } else {
                UserEvent::deleteAll([
                    'user_id' => $purchase->user_id,
                    'event_id' => $purchase->product_id,
                    'status' => UserEvent::STATUS_PENDING_FOR_PAYMENT
                ]);

                $deleted ++;
                $this->consoleLog('purchase was`t confirm');
            }

            $purchase->save();
        }

        $this->consoleLog('was updated : ' . count($purchases) . ' purchases.');
        $this->consoleLog('confirmed : ' . $confirmed . ' purchases.');
        $this->consoleLog('deleted : ' . $deleted . ' purchases.');
    }
}