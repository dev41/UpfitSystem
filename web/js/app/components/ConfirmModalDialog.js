import {TemplateComponent} from "./TemplateComponent";

class ConfirmModalDialog extends TemplateComponent
{
    constructor(options)
    {
        let template = options.template,
            data = options.data || {};
        super(template, data);

        this.options = options;

        this.title = options.title || 'title';
        this.autoOpen = options.autoOpen || true;
        this.removeOnHide = options.removeOnHide || true;
        this.message = options.message || 'message';
        this.message = options.message || 'message';
        this.btnCallback = options.button || {};
        this.buttons = this.container.find('.js-button');

        this.initEvents();
        $('body').append(this.container);

        if (this.autoOpen) {
            this.show();
        }
    }

    show()
    {
        this.container.show();
    }

    hide()
    {
        this.container.hide();
    }

    initEvents()
    {
        let self = this;

        this.buttons.on('click', function() {
            self.hide();
            self.container.remove();
        });

        this.container.on('click', function(e) {
            if (e.target === self.container[0]) {
                self.hide();
                self.container.remove();
            }
        });

        this.buttons.on('click', function() {
            let btnId = this.dataset['btn'];
            if (self.btnCallback[btnId] instanceof Function) {
                self.btnCallback[btnId](this);
            }
        });

        // if worked by jquery modal
        // if (this.removeOnHide) {
        //     this.container.on('hidden.bs.modal', function (e) {
        //         self.container.remove();
        //     });
        // }
    }
}

export {ConfirmModalDialog}