<?php
namespace app\src\entities\translate;

use app\src\entities\AbstractModel;
use Yii;

/**
 * This is the model class for table "{{%translation}}".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 *
 * @property Message $id0
 * @property Message message
 */
class Translation extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['translation'], 'filter', 'filter' => 'trim'],
            [['language'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'translation' => Yii::t('app', 'Translation'),
            'sourceMessage' => Yii::t('app', 'Source Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Message::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function setMessage($message)
    {
        return $this->message = $message;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['language' => 'language']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId()
    {
        return $this->hasOne(Message::className(), ['id' => 'id']);
    }

    /**
     * @return string
     */
    public function getSourceMessage()
    {
        return !empty($this->message) ? $this->message->message : null;
    }
}
