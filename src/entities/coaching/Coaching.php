<?php
namespace app\src\entities\coaching;

use app\src\behaviors\I18nBehavior;
use app\src\entities\AbstractModel;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use app\src\entities\user\UserCoaching;
use app\src\service\CoachingService;
use Yii;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property int $capacity
 * @property string $images
 * @property string $color
 * @property int $coaching_level_id
 * @property int $parent_id
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoachingLevel $level
 * @property Event[] $events
 */
class Coaching extends AbstractModel
{
    const DEFAULT_CAPACITY = 30;

    public $trainers;
    public $clubs;
    public $places;
    public $level;
    public $images;
    public $deleteImages;

    public $isCopy = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coaching';
    }

    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => CoachingI18n::class,
            ]
        ];
    }

    public function getImageField()
    {
        return [$this->images];
    }

    public function init()
    {
        $this->capacity = self::DEFAULT_CAPACITY;
        $this->coaching_level_id = CoachingLevel::getFirstEntityId();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'coaching_level_id', 'price', 'capacity'], 'required'],
            [['clubs', 'places'], 'required', 'when' => function() { return !$this->isCopy; }],
            [['description'], 'string'],
            [['trainers', 'level', 'clubs', 'places', 'images'], 'safe'],
            [['price'], 'number'],
            [['parent_id', 'capacity', 'created_by', 'updated_by', 'coaching_level_id'], 'integer', 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 10],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
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
            'level' => Yii::t('app', 'Level'),
            'trainers' => Yii::t('app', 'Trainers'),
            'clubs' => Yii::t('app', 'Clubs'),
            'places' => Yii::t('app', 'Places'),
            'users' => Yii::t('app', 'Users'),
            'coaching_level_id' => Yii::t('app', 'Coaching Level ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'price' => Yii::t('app', 'Price'),
            'capacity' => Yii::t('app', 'Capacity'),
            'image' => Yii::t('app', 'Image'),
            'color' => Yii::t('app', 'Color'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return array
     */
    public function savingFields()
    {
        return [
            'name',
            'description',
            'price',
            'capacity',
            'color',
            'coaching_level_id',
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
    public function getLevel()
    {
        return $this->hasOne(CoachingLevel::class, ['id' => 'coaching_level_id']);
    }

    public function getImage()
    {
        return $this->hasMany(Image::class, ['parent_id' => 'id'])->where([ 'type' => Image::TYPE_COACHING_IMAGE])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['coaching_id' => 'id']);
    }

    /**
     * @return User[]
     */
    public function getTrainers(): array
    {
        return User::find()
            ->from(['u' => User::tableName()])
            ->leftJoin(['uc' => UserCoaching::tableName()], 'uc.user_id = u.id')
            ->leftJoin(['c' => Coaching::tableName()], 'uc.coaching_id = c.id')
            ->where([
                'c.id' => $this->id,
            ])
            ->all();
    }

    public function getPlaces()
    {
        return $this->hasMany(Place::class, ['id' => 'place_id'])
            ->viaTable(CoachingPlace::tableName(), ['coaching_id' => 'id'])
            ->where('place.type != :club', [
                'club' => Place::TYPE_CLUB,
            ])->all();
    }

    public function getClubs()
    {
        return $this->hasMany(Place::class, ['id' => 'place_id'])
            ->viaTable(CoachingPlace::tableName(), ['coaching_id' => 'id'])
            ->where(['place.type' => Place::TYPE_CLUB])->all();
    }

    public function afterFind()
    {
        $this->trainers = $this->getTrainers();
        $this->clubs = $this->getClubs();
        $this->places = $this->getPlaces();
        $this->images = $this->getImage();
        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $coachingService = new CoachingService();

        $trainers = array_filter((array) $this->trainers);
        $coachingService->setTrainers($this->id, $trainers);

        $clubs = array_filter((array) $this->clubs);
        $coachingService->setPlaces($this->id, Place::TYPE_CLUB, $clubs);

        $places = array_filter((array) $this->places);
        $coachingService->setPlaces($this->id, Place::TYPE_NOT_CLUB, $places);
    }
}