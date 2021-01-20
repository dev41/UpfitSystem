<?php
namespace app\src\helpers;

class ViewHelper
{
    const JS_NAMESPACE = 'upfit';

    const JS_MAP_TRANSLATE = 'translate';

    public static function getJSMap(string $map, array $data)
    {
        $jsNameSpace = self::JS_NAMESPACE;
        $data = json_encode($data);

        return <<< HTML
        <script>(function() {
            window.$jsNameSpace = window.$jsNameSpace || {},
            window.$jsNameSpace.$map = $data;
        })();
        </script>
HTML;
    }
}
