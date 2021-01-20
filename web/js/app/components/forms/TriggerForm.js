import {VisualComponent} from "../VisualComponent";

const typeEmailNotification = 3;

class TriggerForm extends VisualComponent
{
    constructor(container)
    {
        super(container);
        this.initElements();
        this.initEvents();
    }

    initElements()
    {
        this.elements.eventSelect = this.container.find('.js-event-select');
        this.elements.typeSortableInput = this.container.find('.js-trigger-type-sortable-input');
        this.elements.typeSortableInputAvailable = this.container.find('.js-trigger-type-sortable-input-available');
        this.elements.senderInput = this.container.find('.js-sender-email');
        this.elements.templateTextarea = this.container.find('.js-template textarea');
        this.elements.templateTranslateTextarea = this.container.find('.js-template-translate textarea');
        this.elements.templateHint = this.container.find('.js-template-hint');
        this.elements.templateTranslateHint = this.container.find('.js-template-translate-hint');
        this.elements.checkboxAdvancedFilters = this.container.find('.js-checkbox-advanced_filters');
        this.elements.accordionAdvancedFilters = this.container.find('.js-advanced_filters-accordion');
        this.triggerCodes = this.elements.eventSelect.data('codes');
        this.defaultCodes = this.elements.eventSelect.data('default-codes');
    }

    refreshCodes()
    {
        let trigger = parseInt(this.elements.eventSelect.val());

        if (!trigger) {
            return;
        }

        let codes = this.triggerCodes.hasOwnProperty(trigger) ? this.triggerCodes[trigger] : [],
            hint = '';

        codes = codes.concat(this.defaultCodes);

        codes.forEach(function(code) {
            hint += '<span>[' + code + ']</span>';
        });

        this.elements.templateHint.html(hint);
        this.elements.templateTranslateHint.html(hint);
    }

    refreshEmailField()
    {
        let typeIds = this.elements.typeSortableInput.val(),
            isEmailNotification = false;
            self = this;

        if (typeIds !== '') {
            typeIds = typeIds.split(',');
            typeIds.forEach(function (type) {
                type = parseInt(type);
                if (type === typeEmailNotification) {
                    isEmailNotification = true;
                    self.elements.senderInput.removeClass('hide');
                }
            });
        }

        if (!isEmailNotification) {
            self.elements.senderInput.addClass('hide');
            self.elements.senderInput.find('input').val('');
        }
    }

    refreshAdvancedFilters()
    {
        let inputs = this.elements.accordionAdvancedFilters.find('select'),
            checked = this.elements.checkboxAdvancedFilters.is(":checked");

        if (checked) {
            inputs.removeAttr('disabled');
        } else {
            inputs.prop('disabled', true);
        }
    }

    initEvents()
    {
        let self = this;

        this.elements.eventSelect.on('change', function () {
            self.refreshCodes();
        });

        this.elements.templateHint.on('click', 'span', function () {
            self.elements.templateTextarea.val(self.elements.templateTextarea.val() + $(this).html());
        });

        this.elements.templateTranslateHint.on('click', 'span', function () {
            self.elements.templateTranslateTextarea.val(self.elements.templateTranslateTextarea.val() + $(this).html());
        });

        this.refreshCodes();
        this.refreshAdvancedFilters();

        this.elements.typeSortableInput.on('change', function (e) {
            e.preventDefault();
            self.refreshEmailField();
        });

        this.elements.checkboxAdvancedFilters.on('change', function () {
            self.refreshAdvancedFilters();
        });

        this.elements.typeSortableInputAvailable.on('change', function (e) {
            e.preventDefault();
            self.refreshEmailField();
        });
    }
}

export {TriggerForm}