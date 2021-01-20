'use strict';
import { VisualComponent } from './VisualComponent';

const MessageType = {
    SUCCESS: {
        style: 'alert alert-success alert-dismissible',
        template: '<div class="js-show-message"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>{message}</div>'
    },
    ERROR: {
        style: 'alert alert-danger alert-dismissible',
        template: '<div class="js-show-message"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>{message}</div>'
    },
    WARNING: {
        style: 'alert alert-warning alert-dismissible',
        template: '<div class="js-show-message"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>{message}</div>'
    }
};

const selector = {
    SHOW_MESSAGE: '.js-show-message',
};

class Messenger extends VisualComponent
{
    /**
     * @inheritDoc
     */
    constructor(container)
    {
        super(container);

        this.initElements();
    }

    initElements()
    {
        this.elements = {
           showMessage : this.container.find(selector.SHOW_MESSAGE),
        };
    }

    /**
     * @param {string} message
     * @param {{}} [type]
     * @param {string} [type.template]
     * @param {string} [type.style]
     * @param {number} [time]
     */
    addMessage(message, type, time = 3000)
    {
        let self = this;
        $(type.template.replace('{message}', message)).addClass(type.style).appendTo(self.container);

        if (time !== 0) {
            setTimeout(function () {
                $(selector.SHOW_MESSAGE).slideUp();
            }, time);
        }
    }

    clear()
    {
        this.container.remove(selector.SHOW_MESSAGE);
    }
}

export { Messenger, MessageType };