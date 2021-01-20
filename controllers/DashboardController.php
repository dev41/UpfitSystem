<?php
namespace app\controllers;

use app\src\entities\user\User;
use app\src\library\BaseController;

class DashboardController extends BaseController
{
    public function actionIndex()
    {
        $customers = User::findAll([

            'status' => User::STATUS_ACTIVE,
        ]);

    }
}