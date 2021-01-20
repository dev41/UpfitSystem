<?php

namespace app\src\entities\place;

use Yii;
use app\src\entities\translate\Language;
use app\src\entities\AbstractModel;

/**
 * This is the model class for table "place_i18n".
 *
 * @property int $id
 * @property string $language
 * @property string $name
 * @property string $description
 * @property string $address
 *
 * @property Language $language0
 * @property Place $id0
 */
class PlaceI18n extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'place_i18n';
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
            [['address'], 'string', 'max' => 255],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['language'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language' => 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::class, 'targetAttribute' => ['id' => 'id']],
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
            'address' => Yii::t('app', 'Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['language' => 'language']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPlace()
    {
        return $this->hasOne(Place::class, ['id' => 'id']);
    }
}
