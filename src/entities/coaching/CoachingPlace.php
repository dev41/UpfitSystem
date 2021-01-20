<?php
namespace app\src\entities\coaching;

use app\src\entities\AbstractModel;
use app\src\entities\place\Place;

/**
 * Class CoachingPlace
 * @package app\src\entities
 *
 * @property int $id
 * @property int $coaching_id
 * @property int $place_id
 * @property int $place_type - it is necessary to make the updating of clubs / places easy.
 */
class CoachingPlace extends AbstractModel
{

    public static function tableName()
    {
        return 'coaching_place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coaching_id', 'place_id', 'place_type'], 'required'],
            [['coaching_id', 'place_id', 'place_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coaching_id' => 'Coaching ID',
            'place_id' => 'Place ID',
            'place_type' => 'Place Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoaching()
    {
        return $this->hasOne(Coaching::class, ['id' => 'coaching_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::class, ['id' => 'place_id']);
    }
}