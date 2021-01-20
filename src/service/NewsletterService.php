<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\trigger\Newsletter;
use app\src\entities\trigger\Trigger;

class NewsletterService extends AbstractService
{

    public function createByData(array $data, string $scope = null, int $userId = null): Newsletter
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $newsletter = new Newsletter();
            $newsletter->load($data, $scope);

            $newsletter->is_newsletter = Newsletter::IS_NEWSLETTER;
            $newsletter->advanced_filters = Trigger::ADVANCED_FILTERS_ACTIVE;
            $newsletter->created_by = $newsletter->created_by ?? $userId ?? \Yii::$app->user->id;
            $newsletter->created_at = $newsletter->created_at ?? AbstractModel::getDateTimeNow();

            $newsletter->save();

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $newsletter;
    }

    public function updateByData(int $id, array $data, string $scope = null, int $userId = null): Newsletter
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $newsletter = Newsletter::findOne($id);
            $newsletter->load($data, $scope);

            $newsletter->updated_by = $newsletter->updated_by ?? $userId ?? \Yii::$app->user->id;
            $newsletter->updated_at = $newsletter->updated_at ?? AbstractModel::getDateTimeNow();

            $newsletter->save();

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $newsletter;
    }
}