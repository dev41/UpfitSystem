<?php
namespace app\controllers;

use app\src\library\BaseController;
use app\src\service\UserService;
use Yii;
use app\src\form\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class SiteController
 */
class SiteController extends BaseController
{
    public $layout = 'main-login';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'login', 'error', 'auth', 'language',
                            'send-reset-password-link', 'reset-link-sent', 'restore-password', 'set-new-password',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect('/club/');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $this->appendEntryPoint('site.login');

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionLanguage($language)
    {
        Yii::$app->session->set('language', $language);

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSendResetPasswordLink()
    {
        $userIdentifier = $this->getParam('user_identifier');

        $userService = new UserService();
        if (!$userService->sendResetPasswordLink($userIdentifier)) {
            return '';
        }

        return $this->redirect('/site/reset-link-sent');
    }

    public function actionResetLinkSent()
    {
        return $this->render('reset-link-sent');
    }

    public function actionRestorePassword()
    {
        $passwordResetToken = $this->getParam('t');

        $userService = new UserService();

        if (!$userService->checkPasswordResetToken($passwordResetToken)) {
            return $this->render('link-has-expired');
        } else {
            return $this->render('set-new-password', [
                'token' => $passwordResetToken,
            ]);
        }
    }

    public function actionSetNewPassword()
    {
        $passwordResetToken = $this->getParam('t');
        $newPassword = $this->getParam('password');

        $userService = new UserService();

        if (!$userService->setNewPasswordByToken($passwordResetToken, $newPassword)) {
            return $this->render('link-has-expired');
        } else {
            return $this->redirect('/site/login');
        }
    }
}
