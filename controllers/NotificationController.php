<?php
namespace app\controllers;

use app\src\entities\notification\WebNotification;
use app\src\entities\notification\WebNotificationSearch;
use app\src\library\BaseController;

class NotificationController extends BaseController
{

    public function actionIndex()
    {
        $notificationSearch = new WebNotificationSearch();
        $params = $this->getParams();
        $dataProvider = $notificationSearch->getSearchDataProvider($params);

        $this->appendEntryPoint();

        return $this->render('index', [
            'searchModel' => $notificationSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $notificationId = (int) $this->getParam('id');

        $notification = WebNotification::findOne($notificationId);

        $notification->status = WebNotification::STATUS_ALREADY_READ;
        $notification->save(false);

        $this->appendEntryPoint('view');

        return $this->render('view', [
            'notification' => $notification,
        ]);
    }

    public function actionDelete($id)
    {
        $isAjax = $this->getParam('isAjax');

        WebNotification::deleteAll([
            'id' => (int) $id,
        ]);

        if ($isAjax) {
            return $this->responseListing(new WebNotificationSearch(), '/notification/index');
        }

        return $this->redirect('/notification/index');
    }

    public function actionSetStatus()
    {
        $notificationId = (int) $this->getParam('notificationId');
        $userId = (int) $this->getParam('userId');

        $webNotification = WebNotification::findOne(['id' => $notificationId, 'user_id' => $userId]);
        $webNotification->status = $webNotification->status === WebNotification::STATUS_SENT
            ? WebNotification::STATUS_ALREADY_READ
            : WebNotification::STATUS_SENT;
        $webNotification->save(false);

        return $this->responseJson();
    }

}