<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\news\News;
use app\src\exception\ModelValidateException;

/**
 * Class NewsService
 */
class NewsService extends AbstractService
{
    /**
     * @param array $data
     * @param string|null $scope
     * @param int|null $userId
     * @return News
     * @throws ModelValidateException
     */
    public function createNewsByData(array $data, string $scope = null, int $userId = null): News
    {
        $news = new News();
        $news->load($data, $scope);

        $news->created_by = $news->created_by ?? $userId ?? \Yii::$app->user->id;
        $news->created_at = $news->created_at ?? AbstractModel::getDateTimeNow();

        $news->save();

        $imageService = new ImageService();
        $imageService->uploadImages($news, $_FILES, Image::TYPE_NEWS_IMAGE);

        return $news;
    }

    public function updateNewsByData(int $id, array $data): News
    {
        $news = News::findOne($id);
        $news->load($data);

        $imageService = new ImageService();
        $imageService->uploadImages($news, $_FILES, Image::TYPE_NEWS_IMAGE, true);

        $news->save();

        return $news;
    }

    public function deleteById(int $newsId)
    {
        News::deleteAll([
            'id' => $newsId
        ]);
    }
}