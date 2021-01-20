<?php
namespace app\controllers;

use app\src\entities\AbstractModel;
use app\src\entities\access\AccessRole;
use app\src\entities\place\Club;
use app\src\entities\translate\Language;
use app\src\entities\trigger\Newsletter;
use app\src\entities\trigger\NewsletterSearch;
use app\src\entities\trigger\Trigger;
use app\src\entities\trigger\TriggerI18n;
use app\src\entities\user\Position;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\library\EventManager;
use app\src\service\NewsletterService;
use yii\helpers\ArrayHelper;

class NewsletterController extends BaseController
{
    public function actionIndex()
    {
        $newsletterSearch = new NewsletterSearch();
        $params = $this->getParams();

        $dataProvider = $newsletterSearch->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $newsletterSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $triggerId = (int) $this->getParam('id');

        $trigger = Trigger::findOne($triggerId);

        $this->appendEntryPoint('view');

        return $this->render('view', [
            'newsletter' => $trigger,
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
            $newsletterService = new NewsletterService();
            $newsletterService->createByData($attributes);
            return $this->redirect('/newsletter/index');
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

        $newsletter = new Newsletter();
        $newsletter->clubsIds = $clubsIds;

        $this->appendEntryPoint();

        return $this->render('create', [
            'newsletter' => $newsletter,
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
        $newsletterId = (int) $this->getParam('id');

        if (\Yii::$app->request->isPost) {

            $newsletterService = new NewsletterService();
            $newsletterService->updateByData($newsletterId, $attributes);
            return $this->redirect('/newsletter/index');
        }

        $newsletter = Newsletter::findOne($newsletterId);

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

        $this->appendEntryPoint('newsletter.create');

        return $this->render('create', [
            'newsletter' => $newsletter,
            'clubs' => $clubs,

            'positions' => Position::getAll(),
            'roles' => AccessRole::getAll(),
            'users' => $users,

            'receivers' => Trigger::getUsersByTrigger($newsletter),
        ]);
    }

    public function actionDelete($id)
    {
        $isAjax = $this->getParam('isAjax');

        Newsletter::deleteAll([
            'id' => $id,
        ]);

        if ($isAjax) {
            return $this->responseListing(new NewsletterSearch(), '/newsletter/index');
        }

        return $this->redirect('/newsletter/index');
    }

    public function actionGetReceiversList()
    {
        $newsletterId = (int) $this->getParam('id');
        $newsletter = Newsletter::findOne($newsletterId);

        $receiversList = $this->renderAjax('/newsletter/partial/receivers', [
            'receivers' => Trigger::getUsersByTrigger($newsletter),
        ]);

        return $this->responseJson([
            'html' => $receiversList,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function actionSend()
    {
        $newsletterId = $this->getParam('id');

        if (!$newsletterId) {
            $params = $this->getParams();
            $newsletter = new Newsletter();
            $newsletter->load($params);
            $newsletter->created_at = AbstractModel::getDateTimeNow();
            $newsletter->created_by = \Yii::$app->user->getId();
            $newsletter->save();
            $receivers = User::find()->where(['id' => $newsletter->users])->asArray()->all();
        } else {
            $newsletter = Newsletter::findOne(['id' => $newsletterId]);
            $receivers = Trigger::getUsersByTrigger($newsletter);
        }

        $messages['uk'] = $newsletter->template;
        $translationRu = TriggerI18n::findOne(['id' => $newsletter->id, 'language' => Language::LANGUAGE_RU]);
        $translationEn = TriggerI18n::findOne(['id' => $newsletter->id, 'language' => Language::LANGUAGE_EN]);

        if ($translationRu) {
            $messages['ru'] = $translationRu->template;
        }
        if ($translationEn) {
            $messages['en'] = $translationEn->template;
        }

        /** @var array $receiver */
        foreach ($receivers as $receiver) {
            EventManager::sendNotification($newsletter, $receiver, $messages);
        }

        if (!$newsletterId) {
            Newsletter::deleteAll(['id' => $newsletter->id]);
        }
    }
}