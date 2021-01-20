<?php
namespace api\helpers;

use app\src\exception\ApiException;

class MapToDbFieldHelper
{
    const API_FIELD_PATTERN = '/^#[a-zA-Z0-9_]{1,}$/';

    public static function getDbFieldByApi(array $mapFields, string $apiField): string
    {
        if (!isset($mapFields[$apiField])) {
            throw new ApiException(ApiException::INVALID_ARGUMENT, [
                'param' => $apiField,
            ]);
        }

        return $mapFields[$apiField];
    }

    /**
     * @param array $mapFields
     * @param array $items
     * @param bool $isConditions
     * @return array
     * @throws ApiException
     */
    public static function map(array $mapFields, array $items, bool $isConditions = false): array
    {
        foreach ($items as $key => $data) {
            if ($isConditions) {
                if (is_array($data)) {
                    $items[$key] = self::map($mapFields, $data, true);
                } elseif (preg_match(self::API_FIELD_PATTERN, $data)) {
                    $items[$key] = self::getDbFieldByApi($mapFields, $data);
                }
            } else {
                if (preg_match(self::API_FIELD_PATTERN, $key)) {
                    $items[self::getDbFieldByApi($mapFields, $key)] = $data;
                    unset($items[$key]);
                }
            }
        }

        return $items;
    }
}
