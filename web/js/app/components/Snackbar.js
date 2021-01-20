const SnackbarType = {
    INFO: 'info',
    SUCCESS: 'success',
    WARNING: 'warning',
    ERROR: 'error',
};

const SnackbarPosition = {
    TOP_LEFT: 'tl',
    TOP_RIGHT: 'tr',
    BOTTOM_RIGHT: 'br',
    BOTTOM_LEFT: 'bl',
};

const defaultOptions = {
    type: SnackbarType.INFO,
    position: SnackbarPosition.BOTTOM_RIGHT,
    message: 'info',
    visibleTime: 3000,
};

const classes = {
    template: 'js-template-uf-snackbar',
    baseType: '--uf-snackbar-type-',
    basePosition: '--uf-snackbar-position-',
    active: 'active',
    closeBtn: 'js-button-close',
};

let template;

class Snackbar
{
    static _getTemplate()
    {
        if (!template) {
            template = $('.' + classes.template);
        }
        return template;
    }

    static removeMessage(message)
    {
        message.removeClass('active');
        setTimeout(function () {
            message.remove();
        }, 10);
    }

    /**
     * @param {Object} [options]
     * @param {String} [options.type] SnackbarType
     * @param {String} [options.position] SnackbarPosition
     * @param {String} [options.message]
     * @param {Function} [options.onHide]
     * @param {Number} [options.visibleTime]
     */
    static show(options)
    {
        options = $.extend({}, defaultOptions, options || {});

        let extClass = '';
        if (options.type) {
            extClass += ' ' + classes.baseType + options.type;
        }
        if (options.position) {
            extClass += ' ' + classes.basePosition + options.position;
        }

        let template = Snackbar._getTemplate()
            .html()
            .replace(/{message}/g, options.message);

        let message = $(template);
        message.addClass(extClass);

        $('body').append(message);

        setTimeout(function () {
            message.addClass('active');
        }, 10);

        let hideMessage = function () {
            if (options.onHide instanceof Function) {
                options.onHide(message, options);
            } else {
                Snackbar.removeMessage(message);
            }
        };

        message.find('.' + classes.closeBtn).on('click', function() {
            hideMessage();
        });
        setTimeout(function () {
            hideMessage();
        }, options.visibleTime);
    }
}

export {Snackbar, SnackbarType, SnackbarPosition};