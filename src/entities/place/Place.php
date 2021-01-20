<?php
namespace app\src\entities\place;

use app\src\entities\AbstractModel;
use app\src\behaviors\I18nBehavior;
use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingPlace;
use app\src\entities\IImagesFieldModel;
use app\src\entities\image\Image;
use app\src\entities\translate\Language;
use app\src\entities\user\User;
use Yii;

/**
 * Class Place
 *
 * @property int $id
 * @property int $parent_id
 * @property int $type
 * @property string $name
 * @property float $lat
 * @property float $lng
 * @property string $description
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $images
 * @property int $active
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property Place $parent
 * @property Place[] $places
 * @property User $updatedBy
 * @property PlaceI18n[] $placeI18ns
 * @property Language[] $languages
 */
class Place extends AbstractModel implements IImagesFieldModel
{
    const TYPE_CLUB = 1;

    const TYPE_GYM = 2;
    const TYPE_OPEN_SPACE = 3;

    // abstract type
    const TYPE_SUB_PLACE = 100;

    const TYPE_NOT_CLUB = 100; // for coaching_place relation

    const TYPE_LABEL_CLUB = 'Club';

    const TYPE_LABEL_GYM = 'Gym';
    const TYPE_LABEL_OPEN_SPACE = 'Open Space';

    const IS_ACTIVE = true;

    public $images;
    public $deleteImages;

    public static function getTypeLabels(bool $withoutClub = true): array
    {
        $labels = [
            self::TYPE_GYM => \Yii::t('app', self::TYPE_LABEL_GYM),
            self::TYPE_OPEN_SPACE => \Yii::t('app', self::TYPE_LABEL_OPEN_SPACE),
        ];

        return $withoutClub ? $labels : $labels + [self::TYPE_CLUB => \Yii::t('app', self::TYPE_LABEL_CLUB)];
    }

    public static function getLabelByType($type): string
    {
        $labels = self::getTypeLabels(false);
        return $labels[$type];
    }

    public function getImageField()
    {
        return $this->images;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }

    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => PlaceI18n::class,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'type', 'created_by', 'updated_by'], 'integer'],
            [['name', 'type', 'created_by'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'images', 'active'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['lat', 'lng'], 'double'],
            [['country', 'city', 'address'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'type' => Yii::t('app', 'Type'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'name' => Yii::t('app', 'Name'),
            'logo' => Yii::t('app', 'Logo'),
            'image' => Yii::t('app', 'Image'),
            'description' => Yii::t('app', 'Description'),
            'country' => Yii::t('app', 'Country'),
            'city' => Yii::t('app', 'City'),
            'address' => Yii::t('app', 'Address'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            [['country', 'city', 'address', 'payment_url'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Place::class, ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::class, ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @todo check this
     */
    public function getCoaching()
    {
        return $this->hasMany(Coaching::class, ['id' => 'coaching_id'])
            ->viaTable(CoachingPlace::tableName(), ['place_id' => 'id']);
    }

    public function getImages()
    {
        return $this->hasMany(Image::class, ['parent_id' => 'id'])->where([ 'type' => Image::TYPE_PLACE_PHOTO])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public static function getAllByTypes(... $types)
    {
        return self::findAll([
            'type' => $types,
        ]);
    }

    public static function getAllByClubId(array $clubIds)
    {
        return self::findAll([
            'parent_id' => $clubIds,
        ]);
    }

    public function afterFind()
    {
        $this->images = $this->getImages();
        parent::afterFind();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceI18ns()
    {
        return $this->hasMany(PlaceI18n::class, ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::class, ['language' => 'language'])->viaTable('place_i18n', ['id' => 'id']);
    }
}