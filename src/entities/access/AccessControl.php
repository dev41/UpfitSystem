<?php
namespace app\src\entities\access;

use app\src\entities\AbstractModel;

/**
 * Class AccessControl
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $type
 * @property int $access_type
 * @property int $parent_id
 * @property AccessControl $parent
 */
class AccessControl extends AbstractModel
{
    const TYPE_CUSTOM = 0;
    const TYPE_SIDE = 1;
    const TYPE_MODULE = 2;
    const TYPE_CONTROLLER = 3;
    const TYPE_ACTION = 4;
    const TYPE_DOCUMENT = 5;

    const ACCESS_TYPE_PERMISSION = 1;
    const ACCESS_TYPE_PUBLIC = 2;
    const ACCESS_TYPE_PRIVATE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_control';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'name' => \Yii::t('app', 'Name'),
            'slug' => \Yii::t('app', 'Slug'),
            'type' => \Yii::t('app', 'Type'),
            'access_type' => \Yii::t('app', 'Access Type'),
            'parent_id' => \Yii::t('app', 'Parent ID'),
            'Training sessions' =>  \Yii::t('app', 'Training sessions'),
            'Roles and permissions' =>  \Yii::t('app', 'Roles and permissions'),
            'Staff Positions' =>  \Yii::t('app', 'Staff Positions'),
            'Add/Change staff positions' =>  \Yii::t('app', 'Add/Change staff positions'),
            'Delete staff positions' =>  \Yii::t('app', 'Delete staff positions'),
            'Customer Club' =>  \Yii::t('app', 'Customer Club'),
            'Add customer to club' =>  \Yii::t('app', 'Add customer to club'),
            'Change customer status' =>  \Yii::t('app', 'Change customer status'),
            'Clubs Attributes' =>  \Yii::t('app', 'Clubs Attributes'),
            'Add/Change clubs attributes' =>  \Yii::t('app', 'Add/Change clubs attributes'),
            'Delete clubs attributes' =>  \Yii::t('app', 'Delete clubs attributes'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'access_type', 'parent_id'], 'integer'],
            [['name', 'type'], 'required'],
            [['name', 'slug'], 'string', 'max' => 50],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(AccessControl::class, ['id' => 'parent_id']);
    }
}