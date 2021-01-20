<?php

namespace app\src\entities;

use app\src\exception\ModelValidateException;
use app\src\widget\UFActiveForm;
use \yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class AbstractModel
 */
abstract class AbstractModel extends ActiveRecord
{
    const VALIDATE_DEFAULT = 0;
    const DATE_TIME_RANGE_PATTERN = '/^.+\s\-\s.+$/';
    const DATE_LISTING_FORMAT = '%d-%m-%Y';
    const DATE_LISTING_FILTER_FORMAT = '%Y-%m-%d';

    /** @var \ReflectionClass */
    protected static $reflection;

    public $saveWithoutDependencies = false;

    public $validateBehaviour = self::VALIDATE_DEFAULT;

    protected static function getReflection(): \ReflectionClass
    {
        return new \ReflectionClass(get_called_class());
    }

    public function filterRulesByWhenCondition(array $rules)
    {
        return array_filter($rules, function(array $rule) {
            if (!isset($rule['whenClient']) && isset($rule['when']) && is_callable($rule['when'])) {
                return $rule['when']($this);
            }
            return true;
        });
    }

    public function findInputOnClientSideByName(string $name): string
    {
        return 'attribute.$form.find("#' . lcfirst(self::getShortClassName()) . '_" + attribute.$form.data("hash") + "-' . $name . '");';
    }

    public function formName()
    {
        if ($this instanceof ISearchModel) {

            return parent::formName();

        } else {
            if (!UFActiveForm::$formNameHash) {

                return parent::formName();
            }
            return parent::formName() . '_' . UFActiveForm::$formNameHash;
        }

    }

    public static function getSQLUserTitlePriority(string $tableName): string
    {
        return strtr('COALESCE({table}.username, {table}.email, {table}.first_name, {table}.fb_user_id)', [
            '{table}' => $tableName,
        ]);
    }

    public function load($data, $formName = null)
    {
        return parent::load($data, self::normalizeFormName($data, $formName));
    }

    public static function normalizeFormName($data, $formName = null)
    {
        $modelName = static::getShortClassName();

        if (
            ($formName !== null && empty($data[$formName])) ||
            ($formName === null && (empty($data[$modelName]) || !is_array($data[$modelName])))
        ) {
            foreach ($data as $key => $value) {
                if (
                    strpos($key, $modelName) !== false &&
                    is_array($value) &&
                    strlen($key) === strlen($modelName) + 41
                ) {
                    $formName = $key;
                    break;
                }
            }
        }

        return $formName;
    }

    public static function exportData(array $data, string $scope = null): array
    {
        $scope = self::normalizeFormName($data, $scope);
        return $scope && array_key_exists($scope, $data) ? $data[$scope] : $data;
    }

    public static function getShortClassName(): string
    {
        return static::getReflection()->getShortName();
    }

    public static function getRawSql(Query $query): string
    {
        return $query->createCommand()->getRawSql();
    }

    public static function getDateTimeNow(): string
    {
        return date('Y-m-d H:i:s');
    }

    public static function getDateNow(): string
    {
        return date('Y-m-d');
    }

    public static function dateToDb($value): string
    {
        return date('Y-m-d', $value);
    }

    public static function getAll(): array
    {
        return self::find()->all();
    }

    public static function getFirstEntityId()
    {
        $entity = self::find()->one();
        return $entity ? $entity->id : null;
    }

    public function save($runValidation = true, $attributeNames = null, $validateException = true)
    {
        $saved = parent::save($runValidation, $attributeNames);

        if (!$saved && $validateException) {
            throw new ModelValidateException($this);
        }

        return $saved;
    }

    public function throwExceptionIfNotValid($attributeNames = null, $clearErrors = true)
    {
        if (!$this->validate($attributeNames, $clearErrors)) {
            throw new ModelValidateException($this);
        }
    }

    public function isChanged($identical = true): bool
    {
        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            if ($this->isAttributeChanged($name, $identical)) {
                return true;
            }
        }

        return false;
    }

    public static function batchInsertByModels(array $models, array $defaultAttributes = [])
    {
        if (empty($models)) {
            return;
        }

        /** @var ActiveRecord $model */
        $model = reset($models);
        $attributes = array_diff($model->attributes(), $defaultAttributes);

        $rows = array_map(function (ActiveRecord $model) use ($defaultAttributes) {
            return array_filter($model->attributes, function($k) use ($defaultAttributes) {
                return !in_array($k, $defaultAttributes);
            }, ARRAY_FILTER_USE_KEY);
        }, $models);

        \Yii::$app->db->createCommand()->batchInsert(
            $model::tableName(),
            $attributes,
            $rows
        )->execute();
    }

}