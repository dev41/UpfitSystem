import {Listing} from "./Listing";

const selector = {
    BUTTON_ADD: '.js-button-add-attribute',
    BUTTON_UPDATE: '.js-button-update-attribute',
    BUTTON_DELETE: '.js-delete-attribute',
    BUTTON_CREATE_NAME: '.js-button-create-attribute-name',

    INPUT_CREATE_NAME: '.js-create-attribute-input',
    SELECT_NAMES: '.js-select-names',

    VALUE_INPUT_STRING: '.js-value-attribute-string',
    VALUE_INPUT_NUMBER: '.js-value-attribute-number',
    VALUE_INPUT_BOOL: '.js-value-attribute-bool'
};

const actionUrl = {
    ADD_ATTRIBUTES_FORM: '/attribute-club/get-create-form',
    ADD_ATTRIBUTE: '/attribute-club/add-attribute'
};

const refreshData = {
    SELECT: '.js-type-select',
};

const types = {
    STRING: 1,
    NUMBER: 2,
    BOOLEAN: 3
};

class AttributeClubListing extends Listing
{
    constructor(container, clubId)
    {
        super(container);

        this.clubId = clubId;

        this.initEvents();
    }

    initEvents()
    {
        let self = this;

        this.container.on('click', selector.BUTTON_ADD, function () {
            let button = $(this),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            $.ajax({
                url: actionUrl.ADD_ATTRIBUTES_FORM,
                data: {
                    'clubId': self.clubId,
                }
            }).done(function (response) {
                let form = self.showResponseForm(
                    response,
                    actionUrl.ADD_ATTRIBUTE + '?clubId=' + self.clubId,
                    title,
                    null,
                    message
                );
                self.processResponseForm(form);
            });
        });

        this.container.on('click', selector.BUTTON_UPDATE, function (e) {
            let button = $(this),
                url = button.attr('data-url'),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            e.preventDefault();

            self.lock();
            $.ajax({
                type:'post',
                url: url,
            }).done(function (response) {
                let form = self.showResponseForm(response,
                    actionUrl.ADD_ATTRIBUTE + '?clubId=' + self.clubId,
                    title,
                    null,
                    message
                );
                self.processResponseForm(form);
            }).always(function () {
                self.unlock();
            });
        });

        this.initDeleteEvent(selector.BUTTON_DELETE);
    }

    processResponseForm(form)
    {
        let select = form.find(refreshData.SELECT),
            buttonCreate = form.find(selector.BUTTON_CREATE_NAME),
            selectNames = form.find(selector.SELECT_NAMES),
            inputCreateNames = form.find(selector.INPUT_CREATE_NAME);

        AttributeClubListing.refreshTypeField(form, select);

        buttonCreate.on('click', function () {
            if (selectNames.prop('disabled')) {
                selectNames.prop('disabled', false);
                inputCreateNames.prop('disabled', true);
                $(this).text('+');
            } else {
                selectNames.prop('disabled', true);
                inputCreateNames.prop('disabled', false);
                $(this).text('-');
            }
            selectNames.toggleClass('hidden');
            inputCreateNames.toggleClass('hidden');
        });

        select.on('change', function () {
            AttributeClubListing.refreshTypeField(form, select);
        });
    }

    static refreshTypeField(form, select) {
        let stringForm = form.find(selector.VALUE_INPUT_STRING),
            numberForm = form.find(selector.VALUE_INPUT_NUMBER),
            boolForm = form.find(selector.VALUE_INPUT_BOOL),
            stringInput = stringForm.find('input'),
            numberInput = numberForm.find('input'),
            boolInput = boolForm.find('input'),
            typeId = parseInt(select.val());

        stringForm.addClass('hidden');
        stringInput.prop('disabled', true);
        numberForm.addClass('hidden');
        numberInput.prop('disabled', true);
        boolForm.addClass('hidden');
        boolInput.prop('disabled', true);

        switch (typeId) {
            case types.STRING:
                stringForm.removeClass('hidden');
                stringInput.prop('disabled', false);
                break;
            case types.NUMBER:
                numberForm.removeClass('hidden');
                numberInput.prop('disabled', false);
                break;
            case types.BOOLEAN:
                boolForm.removeClass('hidden');
                boolInput.prop('disabled', false);
                break;
            default:
                stringForm.removeClass('hidden');
                stringInput.prop('disabled', false);
                break;
        }
    }

    refreshListByResponse(response)
    {
        let html = $(response.html);
        this.container.find('.grid-view').html(html.find('.grid-view').html());
    }
}

export {AttributeClubListing};