<?php
namespace api\formatter;

class StringToArrayFormatter
{
    /**
     * @param array $modelsData
     * @param string $fieldName
     * @return array
     */
    public static function format(array $modelsData, string $fieldName): array
    {
        foreach ($modelsData as $key => $data) {
            if ($data[$fieldName]) {
                $fields = explode(', ', $data[$fieldName]);
                $modelsData[$key][$fieldName] = $fields;
            }
        }

        return $modelsData;
    }
}
