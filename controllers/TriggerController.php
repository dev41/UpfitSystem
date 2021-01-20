<?php
namespace app\controllers;

use app\src\entities\access\AccessRole;
use app\src\entities\place\Club;
use app\src\entities\trigger\Trigger;
use app\src\entities\trigger\TriggerSearch;
use app\src\entities\user\Position;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\TriggerService;
use yii\helpers\ArrayHelper;

class TriggerController extends BaseController
{
    public function actionIndex()
    {
        $triggerSearch = new TriggerSearch();
        $params = $this->getParams();

        $dataProvider = $triggerSearch->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $triggerSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $triggerId = (int) $this->getParam('id');

        $trigger = Trigger::findOne($triggerId);

        $this->appendEntryPoint('view');

        return $this->render('view', [
            'trigger' => $trigger,
            'receivers' => Trigger::getUsersByTrigger($trigger),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $attributes = $this->getParams();

        if (\Yii::$app->request->isPost) {

            $triggerService = new TriggerService();
            $triggerService->createByData($attributes);
            return $this->redirect('/trigger/index');
        }

        if (AccessChecker::isSuperAdmin()) {
            $users = User::getAll();
            $clubs = Club::getAll();
            $clubsIds = ArrayHelper::map($clubs, 'id', 'id');
        } else {
            $clubs = Club::getClubsByUserId(\Yii::$app->user->getId());
            $clubsIds = ArrayHelper::map($clubs, 'id', 'id');

            $customers = User::getCustomers($clubsIds);
            $staff = User::getStaff($clubsIds);
            $users = array_merge($customers, $staff);
        }

        $trigger = new Trigger();
        $trigger->clubsIds = $clubsIds;

        $this->appendEntryPoint();

        return $this->render('create', [
            'trigger' => $trigger,
            'clubs' => $clubs,

            'positions' => Position::getAll(),
            'roles' => AccessRole::getAll(),
            'users' => $users,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdate()
    {
        $attributes = $this->getParams();
        $triggerId = (int) $this->getParam('id');

        if (\Yii::$app->request->isPost) {

            $triggerService = new TriggerService();
            $triggerService->updateByData($triggerId, $attributes);
            return $this->redirect('/trigger/index');
        }

        $trigger = Trigger::findOne($triggerId);

        if (AccessChecker::isSuperAdmin()) {
            $users = User::getAll();
            $clubs = Club::getAll();
        } else {
            $clubs = Club::getClubsByUserId(\Yii::$app->user->getId());
            $clubsIds = ArrayHelper::map($clubs, 'id', 'id');

            $customers = User::getCustomers($clubsIds);
            $staff = User::getStaff($clubsIds);
            $users = array_merge($customers, $staff);
        }

        $this->appendEntryPoint('trigger.create');

        return $this->render('create', [
            'trigger' => $trigger,
            'clubs' => $clubs,

            'positions' => Position::getAll(),
            'roles' => AccessRole::getAll(),
            'users' => $users,

            'receivers' => Trigger::getUsersByTrigger($trigger),
        ]);
    }

    public function actionDelete($id)
    {
        $isAjax = $this->getParam('isAjax');

        Trigger::deleteAll([
            'id' => $id,
        ]);

        if ($isAjax) {
            return $this->responseListing(new TriggerSearch(), '/trigger/index');
        }

        return $this->redirect('/trigger/index');
    }

    public function actionGetReceiversList()
    {
        $triggerId = (int) $this->getParam('id');
        $trigger = Trigger::findOne($triggerId);

        $receiversList = $this->renderAjax('/trigger/partial/receivers', [
            'receivers' => Trigger::getUsersByTrigger($trigger),
        ]);

        return $this->responseJson([
            'html' => $receiversList,
        ]);
    }
}