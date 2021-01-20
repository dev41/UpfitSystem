<?php
namespace app\src\entities\place;

use app\src\entities\IImagesFieldModel;
use app\src\entities\image\Image;
use app\src\entities\news\News;
use app\src\entities\staff\StaffPositionPlace;
use app\src\behaviors\I18nBehavior;
use app\src\library\Query;
use Yii;

/**
 * Class Club
 *
 * @property string $phone_number
 * @property string $email
 * @property string $site
 * @property string $facebook_id
 * @property string $instagram_id
 * @property string $public_key
 * @property string $private_key
 *
 * @inheritdoc
 */
class Club extends Place implements IImagesFieldModel
{
    public $logo;
    public $deleteLogo;

    public static function getAll(): array
    {
        return self::findAll([
            'type' => self::TYPE_CLUB,
        ]);
    }

    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => ClubI18n::class,
            ]
        ];
    }

    public function rules()
    {
        $rules = array_merge(parent::rules(), [
            [['phone_number', 'facebook_id', 'instagram_id', 'public_key', 'private_key'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['site'], 'string', 'max' => 255],
        ]);

        return $this->filterRulesByWhenCondition($rules);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::rules(), [
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email' => Yii::t('app', 'Email'),
            'site' => Yii::t('app', 'Site'),
            'facebook_id' => Yii::t('app', 'Facebook id'),
            'instagram_id' => Yii::t('app', 'Instagram id'),
            'public_key' => Yii::t('app', 'Public key'),
            'private_key' => Yii::t('app', 'Private key'),
        ]);
    }

    public static function getClubsByUserId(int $userId = null)
    {
        $userId = $userId ?? \Yii::$app->user->id;

        $query = new Query();
        $query
            ->from(['p' => Place::tableName()])
            ->leftJoin(['spp' => StaffPositionPlace::tableName()], 'spp.place_id = p.id')
            ->andWhere([
                'p.type' => Place::TYPE_CLUB,
            ])
            ->andWhere(['or',
                ['spp.user_id' => $userId],
                ['p.created_by' => $userId],
            ])
            ->select([
                'id' => 'p.id',
                'name' => 'p.name',
            ])
            ->groupBy('p.id')
        ;

        return $query->all();
    }

    public function getLogo()
    {
        return $this->hasOne(Image::class, ['parent_id' => 'id'])->where(['type' => Image::TYPE_PLACE_LOGO])->one();
    }

    public function getNews()
    {
        return $this->hasMany(News::class, ['id' => 'club_id'])->all();
    }

    public function getImageField()
    {
        return [$this->logo];
    }

    public function afterFind()
    {
        $this->logo = $this->getLogo();
        parent::afterFind();
    }
}