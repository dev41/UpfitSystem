<?php
namespace app\src\entities\place;

use app\src\behaviors\I18nBehavior;
/**
 * Class Subplace
 *
 * @inheritdoc
 */
class Subplace extends Place
{
    const COPY_FIELDS = [
        'parent_id',
        'type',
        'name',
        'description',
        'address',
        'active',
    ];


    public function behaviors()
    {
        return [
            [
                'class' => I18nBehavior::class,
                'i18nModelClass' => SubplaceI18n::class,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['parent_id'], 'required'],
        ]);
    }
}