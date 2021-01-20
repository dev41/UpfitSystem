<?php
namespace app\controllers;

use app\src\entities\place\Club;
use app\src\library\BaseController;
use app\src\library\LiqPay;
use app\src\service\PurchaseService;

class LiqPayController extends BaseController
{
    public function behaviors()
    {
        $this->actionAccessConfig
            ->setAuthExpect(['post-back'])
            ->setControlExpect(['post-back']);

        return parent::behaviors();
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionPostBack()
    {
        $params = $this->getParams();

        $club = Club::findOne(['id' => $params['place_id']]);

        $validate = false;
        $sign = base64_encode(sha1(
            $club->private_key .
            $params['data'] .
            $club->private_key,
            true));
        if ($params['signature'] === $sign) {
            $validate = true;
        };

        $liqPay = new LiqPay($club->public_key, $club->private_key);

        if ($validate) {
            $resultPayment = $liqPay->decode_params($params['data']);

            $purchaseService = new PurchaseService();
            $purchaseService->updateByData($resultPayment);
        }
    }
}
