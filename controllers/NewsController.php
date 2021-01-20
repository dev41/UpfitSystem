<?php
namespace app\controllers;

use app\src\entities\AbstractModel;
use app\src\entities\news\News;
use app\src\entities\news\NewsSearch;
use app\src\entities\place\Club;
use app\src\library\AccessChecker;
use app\src\library\BaseController;
use app\src\service\NewsService;

/**
 * Class NewsController
 */
class NewsController extends BaseController
{
    /**
     * Lists all News models.
     */
    public function actionIndex()
    {
        $news = new NewsSearch();
        $params = \Yii::$app->request->queryParams;
        $dataProvider = $news->getSearchDataProvider($params);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $news,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $newsId = (int) $this->getParam('id');

        $news = News::findOne($newsId);

        $this->appendEntryPoint('view');

        return $this->render('view', [
            'news' => $news,
        ]);
    }

    public function actionCreate()
    {
        $attributes = $this->getParams();

        if (\Yii::$app->request->isPost) {

            $newsService = new NewsService();
            try {
                $newsService->createNewsByData($attributes);
                return $this->redirect('/news/index');
            } catch (\Exception $e) {
            }
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        return $this->render('create', [
            'news' => new News(),
            'clubs' => $clubs
        ]);
    }

    public function actionDelete()
    {
        $newsId = $this->getParam('id');
        $isAjax = $this->getParam('isAjax');

        $newsService = new NewsService();
        $newsService->deleteById($newsId);

        if ($isAjax) {
            return $this->responseListing(new NewsSearch(), '/news/index');
        }

        return $this->redirect('/news/index');
    }

    public function actionUpdate($id)
    {
        $attributes = \Yii::$app->request->post();
        $newsService = new NewsService();

        if ($attributes) {
            $newsService->updateNewsByData($id, $attributes);
            return $this->redirect('/news/index');
        }

        $clubs = AccessChecker::isSuperAdmin() ?
            Club::getAll() :
            Club::getClubsByUserId(\Yii::$app->user->getId());

        $news = News::findOne($id);

        $this->appendEntryPoint('input-file');

        return $this->render('create', [
            'news' => $news,
            'clubs' => $clubs
        ]);
    }

    /**
     * @param $id
     * @param $filename
     * @param $extPath
     * @param AbstractModel $modelClass
     * @return array
     */
    public function actionDeleteImage($id, $filename, $extPath, AbstractModel $modelClass = null)
    {
        return parent::actionDeleteImage($id, $filename, $extPath, new News());
    }
}