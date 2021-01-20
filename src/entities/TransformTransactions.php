<?php
namespace app\src\entities;

use Yii;

/**
 * This is the model class for table "transform_transactions".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $apply_time
 */
class TransformTransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transform_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'apply_time'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'apply_time' => Yii::t('app', 'Apply Time'),
        ];
    }


    public static function getFindArrayName(int $type, int $limit)
    {

        $find = self::find()
            ->select('name')
            ->where(['type' => $type])
            ->orderBy(['id' => SORT_DESC]);

        if ($limit > 0) {
            $find->limit($limit);
        }

        return $find->asArray()->all();
    }
}
