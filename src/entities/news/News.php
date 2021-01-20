<?php
namespace app\src\entities\news;

use app\src\behaviors\I18nBehavior;
use app\src\entities\AbstractModel;
use app\src\entities\IImagesFieldModel;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use Yii;

/**
 * @property int $id
 * @property int $club_id
 * @property string $name
 * @property string $description
 * @property int $active
 * @property string $images
 * @property int $created_at
 * @property int $created_by
 *
 */
class News extends AbstractModel implements IImagesFieldModel
{
    private $_i18nAttributes = [
        'name',
        'description',
    ];

    public $images;
    public $deleteImages;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'news';
    }

    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => NewsI18n::class,
            ]
        ];
    }

    public function getImageField()
    {
        return [$this->images];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'club_id', 'created_by', 'active'], 'integer'],
            [['created_at'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['created_at', 'images'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public function getClub()
    {
        return $this->hasOne(Place::class, ['id' => 'club_id'])->one();
    }

    public function getImage()
    {
        return $this->hasMany(Image::class, ['parent_id' => 'id'])->where([ 'type' => Image::TYPE_NEWS_IMAGE])->one();
    }

    public function afterFind()
    {
        $this->images = $this->getImage();
        parent::afterFind();
    }
}
