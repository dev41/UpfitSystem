<?php

namespace app\src\entities\news;

use app\src\entities\translate\Language;
use app\src\entities\AbstractModel;
use Yii;
/**
 * This is the model class for table "news_i18n".
 *
 * @property int $id
 * @property string $language
 * @property string $name
 * @property string $description
 *
 * @property Language $language0
 * @property News $id0
 */
class NewsI18n extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_i18n';
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
            [['language'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language' => 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => News::class, 'targetAttribute' => ['id' => 'id']],
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
    public function getLanguage0()
    {
        return $this->hasOne(Language::class, ['language' => 'language']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdNews()
    {
        return $this->hasOne(News::class, ['id' => 'id']);
    }
}
