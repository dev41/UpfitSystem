<?php

namespace app\src\entities\activity;

use app\src\entities\translate\Language;
use app\src\entities\AbstractModel;
use Yii;
/**
 * This is the model class for table "activity_i18n".
 *
 * @property int $id
 * @property string $language
 * @property string $name
 * @property string $description
 *
 * @property Activity $id0
 * @property Language $language0
 */
class ActivityI18n extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_i18n';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 50],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['id' => 'id']],
            [['language'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language' => 'language']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdActivity()
    {
        return $this->hasOne(Activity::class, ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage0()
    {
        return $this->hasOne(Language::class, ['language' => 'language']);
    }
}
