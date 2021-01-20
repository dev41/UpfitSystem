const JS_NAMESPACE = 'upfit';

const JS_MAP_TRANSLATE = 'translate';

class TranslateHelper
{
    static map;

    static getMap()
    {
        if (!TranslateHelper.map) {
            let source = window[JS_NAMESPACE] || {};
            TranslateHelper.map = source[JS_MAP_TRANSLATE] || {};
        }

        return TranslateHelper.map;
    }

    static t(label, defaultValue)
    {
        let map = TranslateHelper.getMap(),
            res = map[label];

        return res ? res : defaultValue;
    }
}

export {TranslateHelper};