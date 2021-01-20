<?php
namespace app\src\entities\activity;

use app\src\behaviors\I18nBehavior;
use app\src\entities\AbstractModel;
use app\src\entities\IImagesFieldModel;
use app\src\entities\image\Image;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use app\src\entities\user\UserActivity;
use app\src\service\ActivityService;
use Yii;

/**
 * @property int $id
 * @property int $club_id
 * @property string $name
 * @property string $description
 * @property string $images
 * @property float $price
 * @property int $capacity
 * @property string $start
 * @property string $end
 * @property int $created_at
 * @property int $created_by
 */
class Activity extends AbstractModel implements IImagesFieldModel
{
    const DEFAULT_CAPACITY = 30;
    const IS_STAFF = true;

    public $organizers;
    public $images;
    public $deleteImages;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'activity';
    }

    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => ActivityI18n::class,
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
            [['id', 'club_id', 'created_by', 'capacity'], 'integer'],
            [['name', 'created_at', 'created_by'], 'required'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['created_at', 'organizers', 'start', 'end', 'images'], 'safe'],
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
            'price' => Yii::t('app', 'Price'),
            'capacity' => Yii::t('app', 'Capacity'),
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

    /**
     * @return User[]
     */
    public function getOrganizers(): array
    {
        return User::find()
            ->from(['u' => User::tableName()])
            ->leftJoin(['ua' => UserActivity::tableName()], 'ua.user_id = u.id')
            ->leftJoin(['a' => Activity::tableName()], 'a.id = ua.activity_id')
            ->where([
                'a.id' => $this->id,
            ])
            ->all();
    }

    public function getImage()
    {
        return $this->hasMany(Image::class, ['parent_id' => 'id'])->where([ 'type' => Image::TYPE_ACTIVITY_IMAGE])->one();
    }

    public function afterFind()
    {
        $this->organizers = $this->getOrganizers();
        $this->images = $this->getImage();
        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $activityService = new ActivityService();

        $organizers = array_filter((array) $this->organizers);
        $activityService->setOrganizers($this->id, $organizers);
    }
}
