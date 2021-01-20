<?php

namespace app\src\behaviors;

use app\src\entities\AbstractModel;
use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use app\src\entities\translate\Language;

class I18nBehavior extends Behavior
{
    public $i18nModelClass = null;

    public $language = 'uk';

    /**
     * @param null $config
     */
    public function __construct($config = null)
    {
        $this->language = Yii::$app->language;

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    public function afterSave()
    {
        $post = Yii::$app->request->post();
        if (empty($post)) {
            return;
        }

        $model = (new $this->i18nModelClass);
        /** @var AbstractModel $i18nModelName */
        $i18nModelName = $model::normalizeFormName($post);

        if (!$i18nModelName) {
            return;
        }

        $translations = $post[$i18nModelName];
        $this->saveTranslations($translations, $model);
    }

    /**
     * @param $translations
     * @param $model
     */
    public function saveTranslations($translations, $model)
    {
        $id = $this->owner->id;

        foreach ($translations as $language => $translation) {
            $modelI18n = $model->findOne(['id' => $id, 'language' => $language]);
            if (empty($modelI18n)) {
                $modelI18n = $this->createI18nModel($id, $language);
                foreach ($translation as $value) {
                    if (!empty($value)) {
                        $modelI18n->attributes = $translation;
                        $modelI18n->save();
                        break;
                    }
                }
            } else {
                $modelI18n->attributes = $translation;
                $modelI18n->save();
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinkedModelI18ns()
    {
        return $this->owner->hasMany($this->i18nModelClass, ['id' => 'id']);
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        $id = $this->owner->id;
        $translations = ArrayHelper::index($this->owner->linkedModelI18ns, 'language');
        $languages = Language::find()->all();

        foreach ($languages as $language) {
            if (empty($translations[$language->language]) && $language->language != Language::DEFAULT_LANGUAGE) {
                $translations[$language->language] = $this->createI18nModel($id, $language);
            }
        }

        return $translations;
    }

    /**
     * @param $id
     * @param $language
     * @return array
     */
    public function createI18nModel($id, $language)
    {
        $modelI18n = new $this->i18nModelClass();
        $modelI18n->loadDefaultValues();
        $modelI18n->id = $id;
        $modelI18n->language = $language;

        return $modelI18n;
    }

    /**
     * @param $attribute
     * @return string
     */
    public function getAttributeValue($attribute)
    {
        if ($this->owner->language == Language::DEFAULT_LANGUAGE) {
            return $this->owner->getAttribute($attribute);
        }

        $i18nsModel = ArrayHelper::index($this->owner->linkedModelI18ns, 'language');

        if (!empty($i18nsModel[$this->owner->language])) {
            return $i18nsModel[$this->owner->language]->getAttribute($attribute);
        }

        return $this->owner->getAttribute($attribute);
    }
}
