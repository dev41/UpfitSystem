<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\activity\Activity;
use app\src\entities\image\Image;
use app\src\entities\user\User;
use app\src\entities\user\UserActivity;
use app\src\exception\ModelValidateException;

/**
 * Class ActivityService
 */
class ActivityService extends AbstractService
{
    /**
     * @param array $data
     * @param string|null $scope
     * @param int|null $userId
     * @return Activity
     * @throws ModelValidateException
     */
    public function createActivityByData(array $data, string $scope = null, int $userId = null): Activity
    {
        $activity = new Activity();
        $activity->load($data, $scope);

        $activity->created_by = $activity->created_by ?? $userId ?? \Yii::$app->user->id;
        $activity->created_at = $activity->created_at ?? AbstractModel::getDateTimeNow();

        $activity->save();

        $imageService = new ImageService();
        $imageService->uploadImages($activity, $_FILES, Image::TYPE_ACTIVITY_IMAGE);

        return $activity;
    }

    public function updateActivityByData(int $id, array $data): Activity
    {
        $activity = Activity::findOne($id);
        $activity->load($data);

        $imageService = new ImageService();
        $imageService->uploadImages($activity, $_FILES, Image::TYPE_ACTIVITY_IMAGE, true);

        $activity->save();

        return $activity;
    }

    public function deleteById(int $activityId)
    {
        Activity::deleteAll([
            'id' => $activityId
        ]);
    }

    public function setOrganizers(int $activityId, array $organizersIds = [])
    {
        UserActivity::deleteAll([
            'activity_id' => $activityId,
        ]);

        if (empty($organizersIds)) {
            return;
        }

        $userOrganizers = [];
        foreach ($organizersIds as $organizerId) {
            $userActivity = new UserActivity();
            $userActivity->activity_id = $activityId;
            $userActivity->user_id = $organizerId instanceof User ? $organizerId->id : $organizerId;
            $userActivity->is_staff = true;

            $userOrganizers[] = $userActivity;
        }

        UserActivity::batchInsertByModels($userOrganizers);
    }
}