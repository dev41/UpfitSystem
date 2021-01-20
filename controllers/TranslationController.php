<?php
namespace app\controllers;

use app\src\entities\translate\Message;
use app\src\entities\translate\Translation;
use app\src\entities\translate\TranslationSearch;
use app\src\library\BaseController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Translation model.
 */
class TranslationController extends BaseController
{
    /**
     * Lists all Translation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TranslationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->appendEntryPoint('listing');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Translation model.
     * @param integer $id
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $language)
    {
        $this->appendEntryPoint('view');

        return $this->render('view', [
            'model' => $this->findModel($id, $language),
        ]);
    }

    /**
     * Creates a new Translation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \app\src\exception\ModelValidateException
     */
    public function actionCreate()
    {
        $translation = new Translation();
        $translation->loadDefaultValues();
        $translation->message = new Message();

        if ($translation->load(Yii::$app->request->post()) && $translation->message->load(Yii::$app->request->post()) && $translation->message->save()) {
            $translation->id = $translation->message->id;
            $translation->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $translation,
            ]);
        }
    }

    /**
     * Updates an existing Translation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \app\src\exception\ModelValidateException
     */
    public function actionUpdate($id, $language)
    {
        $model = $this->findModel($id, $language);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \app\src\exception\ModelValidateException
     */
    public function actionUpdateTranslation()
    {
        $post = Yii::$app->request->post();

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($post['hasEditable'])) {
            $key = json_decode($post['editableKey']);
            $attribute = $post['editableAttribute'];
            $index = $post['editableIndex'];
            $value = $post['Translation'][$index][$attribute];
            $model = $this->findModel($key->id, $key->language);
            $model->$attribute = $value;
            if ($model->save()) {
                return [
                    'output' => $value,
                ];
            }
        }

        return [
            'message' => Yii::t('app', 'Saving value error.'),
        ];
    }

    /**
     * Deletes an existing Translation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $language)
    {
        $isAjax = $this->getParam('isAjax');

        $this->findModel($id, $language)->delete();

        if ($isAjax) {
            $searchModel = new TranslationSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $list = $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

            return $this->responseJson([
                'html' => $list,
            ]);
        }

        return $this->redirect('/translation/index');
    }

    /**
     * Deletes existing User models.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteList()
    {
        $keys = Yii::$app->request->post('ids');
        $success = false;

        if (!empty($keys)) {
            foreach ($keys as $key) {
                $model = $this->findModel($key['id'], $key['language']);
                $success = $model->delete();
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'success' => $success,
        ];
    }

    /**
     * Finds the Translation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $language
     * @return Translation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $language)
    {
        if (($model = Translation::findOne(['id' => $id, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
