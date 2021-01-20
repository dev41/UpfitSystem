<?php
namespace app\src\helpers;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;

class ImageHelper
{
    public static function getUrl(AbstractModel $model, string $image = null, string $extPath = '')
    {
        $modelName = lcfirst($model::getShortClassName());
        $image = Image::BASE_URL . $modelName . '/' . $model['id'] . $extPath . '/' . $image;
        return $image;
    }

    public static function getImagesInfo(AbstractModel $model, array $images = null, string $extPath = ''): array
    {
        if (!isset($images)) {
            return [];
        }
        $images = array_filter($images);

        $fullPath = [];
        foreach ($images as $image) {
            $fullPath['url'][] = ImageHelper::getUrl($model, $image->file_name, $extPath);
            $fullPath['info'][] = ['caption' => $image->name, 'filename' => $image->file_name, 'size' => $image->size];
        }
        return $fullPath;
    }
}