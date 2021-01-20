const highlightType = {
    AFTER_UPDATE: { options: {color: 'rgba(0, 166, 90, 0.2)'}, time: 400 },
};

class EffectHelper
{
    /**
     * @param {jQuery} target
     * @param {Object} [type=AFTER_UPDATE]
     * @constructor
     */
    static highlight(target, type)
    {
        type = type || highlightType.AFTER_UPDATE;
        target.effect('highlight', type.options, type.time);
    }
}

export {EffectHelper, highlightType}