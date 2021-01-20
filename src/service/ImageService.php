<?php
namespace app\src\service;

use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use Yii;
use yii\web\UploadedFile;

class ImageService extends AbstractService
{
    public function uploadImages(AbstractModel $model, array $data, int $type, $deleteOld = false)
    {
        $modelName = $model::normalizeFormName($data);

        $images = $data[$modelName]['name']['images'] ?? [];
        $images = array_filter($images);

        if (!empty($images)) {
            if ($deleteOld) {
                $this->deleteOldImage($model, $type);
            }
            $this->loadImagesByData($model, $images, $modelName, $type);
        }
    }

    public function uploadLogo(AbstractModel $model, array $data, int $type)
    {
        $modelName = $model::normalizeFormName($data);

        $logo = $_FILES[$modelName]['name']['logo'][0] ?? '';

        if ($logo) {
            $this->loadLogoByData($model, $logo, $modelName, $type);
        }
    }

    public function deleteImagesByFilenames(AbstractModel $parent, array $filenames = null, string $extPath = '')
    {
        $filenames = array_filter($filenames);

        if (empty($filenames)) {
            return;
        }

        $imagePath = $this->getImagePathByParentModel($parent) . $extPath;

        Image::deleteAll(['file_name' => $filenames]);

        if (is_dir($imagePath)) {
            $objects = scandir($imagePath);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..' && in_array($object, $filenames)) {
                    unlink($imagePath . '/' . $object);
                }
            }
        }
    }

    public function getImagePathByParentModel(AbstractModel $model): string
    {
        $modelName = $model::getShortClassName();
        return Yii::getAlias('@imageStorage') . '/' . lcfirst($modelName) . '/' . $model['id'];
    }


    public function loadImagesByData(AbstractModel $parentModel, array $filenames = [], string $modelName, int $type)
    {
        $imagePath = $this->getImagePathByParentModel($parentModel);

        if (!is_dir($imagePath)) {
            mkdir($imagePath, 0777, true);
        }

        $filenames = array_values($filenames);
        $imagesInfo = [];

        foreach ($filenames as $key => $files) {
            $file = UploadedFile::getInstanceByName($modelName . '[images][' . $key . ']');

            if (!$this->isValidFileType($file->type)) {
                continue;
            }

            $fileName = time() . '_' . $files;
            $imagesInfo[$key]['name'] = $files;
            $imagesInfo[$key]['file_name'] = $fileName;
            $imagesInfo[$key]['size'] = $file->size;

            $name = $imagePath . '/' . $fileName;

            $image_d = getimagesize($file->tempName);
            if ($image_d[0] > Image::MAX_IMAGE_WIDTH || $image_d[1] > Image::MAX_IMAGE_HEIGHT || !$this->isValidFileType($file->type)) {
                $coefficient = ($image_d[0] > $image_d[1])
                    ? $image_d[0] / Image::MAX_IMAGE_WIDTH
                    : $image_d[1] / Image::MAX_IMAGE_WIDTH;
                $newWidth = $image_d[0] / $coefficient;
                $newHeight = $image_d[1] / $coefficient;

                $this->imageResize($file->tempName, $name, $newWidth, $newHeight);
                $imagesInfo[$key]['size'] = filesize($name);
            } else {
                $file->saveAs($name);
            }
        }

        $this->insertImagesByData($imagesInfo, (int) $parentModel['id'], $type);
    }

    public function loadLogoByData(AbstractModel $parentModel, string $fileName = '', string $modelName, int $type)
    {
        $imagePath = $this->getImagePathByParentModel($parentModel) . '/logo';

        $imagesInfo = [];

        $file = UploadedFile::getInstanceByName($modelName . '[logo][0]');

        if (!is_dir($imagePath)) {
            mkdir($imagePath, 0777, true);
        }

        $fullFileName = time() . 'logo_' . $fileName;
        $imagesInfo['name'] = $fileName;
        $imagesInfo['file_name'] = $fullFileName;
        $imagesInfo['size'] = $file->size;

        $name = $imagePath . '/' . $fullFileName;

        $image_d = getimagesize($file->tempName);
        if ($image_d[0] > Image::MAX_LOGO_WIDTH || $image_d[1] > Image::MAX_LOGO_HEIGHT || !$this->isValidFileType($file->type)) {
            $coefficient = ($image_d[0] > $image_d[1])
                ? $image_d[0] / Image::MAX_LOGO_WIDTH
                : $image_d[1] / Image::MAX_LOGO_HEIGHT;
            $newWidth = $image_d[0] / $coefficient;
            $newHeight = $image_d[1] / $coefficient;

            $this->imageResize($file->tempName, $name, $newWidth, $newHeight);
            $imagesInfo['size'] = filesize($name);
        } else {
            $file->saveAs($name);
        }

        $this->deleteOldImage($parentModel, $type);

        $this->insertImagesByData([$imagesInfo], (int) $parentModel['id'], $type);
    }

    public function insertImagesByData(array $data, int $parentModelId, int $type): array
    {
        $newImages = [];

        foreach ($data as $image) {
            $copyImage = new Image();
            $copyImage->name = $image['name'];
            $copyImage->file_name = $image['file_name'];
            $copyImage->parent_id = $parentModelId;
            $copyImage->size = $image['size'];
            $copyImage->created_by = \Yii::$app->user->id;
            $copyImage->created_at = AbstractModel::getDateTimeNow();
            $copyImage->type = $type;

            $newImages[] = $copyImage;
        }

        Image::batchInsertByModels($newImages);

        return $newImages;
    }

    public function isValidFileType($ext)
    {
        $ext = strtolower($ext);
        return in_array($ext, array('image/jpg', 'image/jpeg', 'image/png', 'image/gif'));
    }

    public function imageResize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100)
    {
        if (!file_exists($src)) {
            return false;
        }

        $size = getimagesize($src);

        if ($size === false) {
            return false;
        }

        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        $icfunc = "imagecreatefrom" . $format;

        if (!function_exists($icfunc)) {
            return false;
        }
        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];
        $ratio = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);

        $new_width = $use_x_ratio ? $width : floor($size[0] * $ratio);
        $new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);
        $new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
        $new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

        $isrc = $icfunc($src);
        $idest = imagecreatetruecolor($width, $height);

        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
            $new_width, $new_height, $size[0], $size[1]);

        imagejpeg($idest, $dest, $quality);

        imagedestroy($isrc);
        imagedestroy($idest);

        return true;
    }

    public function deleteOldImage($parentModel, $type)
    {
        $image = Image::findOne(['parent_id' => $parentModel['id'], 'type' => $type]);

        if ($image) {
            $this->deleteImagesByFilenames($parentModel, [$image->file_name]);
        }
    }
}