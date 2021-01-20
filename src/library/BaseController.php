<?php
namespace app\src\library;

use app\src\entities\AbstractModel;
use app\src\service\ImageService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

/**
 * Class BaseController
 */
abstract class BaseController extends Controller
{
    /** @var Request */
    public $request;
    /** @var array */
    private $requestParams = [];
    /** @var ActionAccessConfig */
    public $actionAccessConfig;

    const JS_ROUTES_PATH = 'js/dist/routes';
    const JS_ENTRY_PATH_PARAM = 'jsEntryPath';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
//                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function () {
                            return $this->accessRuleMatch();
                        }
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $language = Yii::$app->session->get('language', 'uk');
        Yii::$app->language = $language;

        return parent::beforeAction($action);
    }

    public function init()
    {
        parent::init();

        $this->actionAccessConfig = new ActionAccessConfig();
        $this->request = Yii::$app->request;
        $this->requestParams = (array) $this->request->get() + (array) $this->request->post();

        if ($this->request->isPost && empty($this->request->post())) {
            $rawParams = file_get_contents('php://input');
            $params = [];
            parse_str($rawParams, $params);
            $this->requestParams = $this->requestParams + $params;
        }
    }

    public function responseJson(array $data = [], bool $success = true): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $data + [
            'success' => $success,
        ];
    }

    public function responseListing(AbstractModel $abstractSearch, string $view): array
    {
        $params = \Yii::$app->request->queryParams;
        $dataProvider = $abstractSearch->getSearchDataProvider($params);

        $list = $this->renderAjax($view, [
            'searchModel' => $abstractSearch,
            'dataProvider' => $dataProvider,
        ]);

        return $this->responseJson([
            'html' => $list,
        ]);
    }

    public function getParam(string $name, $default = null)
    {
        return $this->requestParams[$name] ?? $default;
    }

    public function getParams()
    {
        return $this->requestParams;
    }

    protected function accessRuleMatch()
    {
        $controllerId = Yii::$app->controller->id;
        $actionId = Yii::$app->controller->action->id;

        if (in_array($actionId, $this->actionAccessConfig->getAuthExpect())) {
            return true;
        }

        if (Yii::$app->user->isGuest) {
            Yii::$app->user->loginRequired();
        }

        $controllerActionAccess = AccessChecker::hasControllerActionAccess($controllerId, $actionId);
//                            $actionAccess = AccessChecker::hasActionAccess($controllerId, $actionId);

        return $controllerActionAccess;
    }

    /**
     * @param array|string $key
     * @param [$value]
     * @return $this
     */
    public function viewAssign($key, $value)
    {
        if (is_array($key)) {
            $this->view->params = array_merge($this->view->params, $key);
        } else {
            $this->view->params[$key] = $value;
        }

        return $this;
    }

    protected function getJsEntryPath($routeName = null)
    {
        $routeName = $routeName ?? Yii::$app->controller->id . '.' . Yii::$app->controller->action->id;
        return self::JS_ROUTES_PATH . '/' . $routeName . '.js';
    }

    public function appendEntryPoint($routeName = null)
    {
        $this->viewAssign(self::JS_ENTRY_PATH_PARAM, $this->getJsEntryPath($routeName));
    }

    /**
     * @param $id
     * @param $filename
     * @param $extPath
     * @param AbstractModel $model
     * @return array
     */
    public function actionDeleteImage($id, $filename, $extPath, AbstractModel $model)
    {
        $model = $model::findOne(['id' => $id]);

        $imageService = new ImageService();
        $imageService->deleteImagesByFilenames($model, [$filename], $extPath);

        return $this->responseJson([
            'success' => true,
        ]);
    }

}