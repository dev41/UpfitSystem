<?php
namespace app\src\entities\sale;

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
 * @property string $images
 * @property string $start
 * @property string $end
 * @property int $created_at
 * @property int $created_by
 *
 */
class Sale extends AbstractModel implements IImagesFieldModel
{
    public $images;
    public $deleteImages;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sale';
    }

    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => SaleI18n::class,
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
            [['id', 'club_id', 'created_by'], 'integer'],
            [['name', 'created_at', 'created_by', 'start', 'end'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['created_at', 'start', 'end', 'images'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'club_id' => Yii::t('app', 'Club Id'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
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
        return $this->hasMany(Image::class, ['parent_id' => 'id'])->where([ 'type' => Image::TYPE_SALE_IMAGE])->one();
    }

    public function afterFind()
    {
        $this->images = $this->getImage();
        parent::afterFind();
    }
}