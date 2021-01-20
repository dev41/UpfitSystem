<?php

namespace common\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;

class FileBehavior extends Behavior
{
    public $fieldNames = ['file'];

    public $originalNames = [];

    public $inputFileName = null;

    public $storage = null;

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->storage = Yii::$app->fileStorage;
    }

    public function beforeValidate()
    {
        foreach ($this->fieldNames as $fieldName) {
            $inputName = $this->getInputName();
            $fileName = $this->storage->getFileName($this->owner, $fieldName, $inputName);
            $this->owner->setAttribute($fieldName, $fileName);
        }
    }

    /**
     * @param object $event
     */
    public function beforeSave($event)
    {
        foreach ($this->fieldNames as $fieldName) {
            $inputName = $this->getInputName();
            $fileName = $this->storage->getFileName($this->owner, $fieldName, $inputName);
            $this->owner->setAttribute($fieldName, $fileName);
        }
    }

    /**
     * @param object $event
     */
    public function afterSave($event)
    {
        foreach ($this->fieldNames as $fieldName) {
            $inputName = $this->getInputName();
            $this->storage->saveFile($this->owner, $fieldName, $inputName);
        }
    }

    /**
     * @param object $event
     */
    public function afterDelete($event)
    {
        $this->storage->deleteAllFiles($this->owner);
    }

    /**
     * @param null $fieldName
     * @return mixed
     */
    public function deleteFile($fieldName = null)
    {
        $this->storage->deleteFile($this->owner, $fieldName);
        $fieldName = $this->getFieldName($fieldName);
        $this->owner->setAttribute($fieldName, null);
        return $this->owner->save();
    }

    /**
     * @return string
     */
    public function getFileUrl($fieldName = null, $isThumbnail = false)
    {
        return $this->storage->getFileUrl($this->owner, $fieldName);
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->storage->getFilePath($this->owner, $this->fieldName);
    }

    /**
     * @return mixed|null
     */
    public function getInputName()
    {
        return $this->owner->inputName ?? $this->inputFileName;
    }

    /**
     * @param null $fieldName
     * @return mixed|null
     */
    public function getFieldName($fieldName = null)
    {
        return $fieldName ?? $this->fieldNames[0];
    }
}
