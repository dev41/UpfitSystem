<?php
namespace app\src\entities\image;

use app\src\entities\AbstractModel;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use Yii;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $file_name
 * @property int $size
 * @property int $type
 * @property int $created_at
 * @property int $created_by
 *
 */
class Image extends AbstractModel
{
    const BASE_URL = '/storage/images/';

    const TYPE_PLACE_PHOTO = 0;
    const TYPE_PLACE_LOGO = 1;
    const TYPE_NEWS_IMAGE = 2;
    const TYPE_SALE_IMAGE = 3;
    const TYPE_ACTIVITY_IMAGE = 4;
    const TYPE_USER_AVATAR = 5;
    const TYPE_USER_PHOTO = 6;
    const TYPE_COACHING_IMAGE = 7;

    const MAX_LOGO_WIDTH = 200;
    const MAX_LOGO_HEIGHT = 200;

    const MAX_IMAGE_WIDTH = 1200;
    const MAX_IMAGE_HEIGHT = 1200;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'created_at', 'file_name'], 'required'],
            [['id', 'parent_id', 'type', 'size'], 'integer'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['name', 'file_name'], 'string', 'max' => 255],
            [['name', 'file_name'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent Id'),
            'name' => Yii::t('app', 'Name'),
            'file_name' => Yii::t('app', 'File Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'size' => Yii::t('app', 'Size'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::class, ['id' => 'parent_id']);
    }

    public function getClub()
    {
        return $this->hasOne(Club::class, ['id' => 'parent_id']);
    }
}
