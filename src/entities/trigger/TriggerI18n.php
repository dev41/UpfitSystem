<?php
namespace app\src\entities\trigger;

use Yii;
use app\src\entities\translate\Language;
use app\src\entities\AbstractModel;

/**
 * This is the model class for table "trigger_i18n".
 *
 * @property int $id
 * @property string $language
 * @property string $title
 * @property string $template
 *
 * @property Language $language0
 * @property Trigger $id0
 */
class TriggerI18n extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trigger_i18n';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['template'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['title'], 'string', 'max' => 50],
            [['template'],'string', 'max' => 255],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['language'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language' => 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Trigger::class, 'targetAttribute' => ['id' => 'id']],
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
            'title' => Yii::t('app', 'Title'),
            'template' => Yii::t('app', 'Template'),
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
    public function getIdTrigger()
    {
        return $this->hasOne(Trigger::class, ['id' => 'id']);
    }
}
