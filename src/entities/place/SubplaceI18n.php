<?php
namespace app\src\entities\place;

/**
 * Class SubplaceI18n
 *
 * @inheritdoc
 */
class SubplaceI18n extends PlaceI18n
{
    const COPY_FIELDS = [
        'language',
        'name',
        'description',
        'address',
    ];
}