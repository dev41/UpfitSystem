import {Listing} from "./Listing";
import {Snackbar, SnackbarType} from "../Snackbar";

const selector = {
    BUTTON_ADD: '.js-button-add-customer',
    BUTTON_CREATE: '.js-button-create-customer',
    BUTTON_UPDATE: '.js-button-update-customer',
    BUTTON_DELETE: '.js-button-delete-customer',
    BUTTON_SET_STATUS: '.js-set-status',
};

const actionUrl = {
    ADD_CLIENTS_FORM: '/customer-place/add-customers-form',
    ADD_CLIENT: '/customer-place/add-customer',
    UPDATE_CUSTOMER: '/customer-place/update-customer',

    CREATE_CUSTOMER_FORM: '/customer-place/get-create-form',
    CREATE_CUSTOMER: '/customer/create',
};

class CustomerClubListing extends Listing
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

        this.container.on('click', selector.BUTTON_CREATE, function () {
            let button = $(this),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            $.ajax({
                url: actionUrl.CREATE_CUSTOMER_FORM,
                data: {
                    'clubId': self.clubId,
                }
            }).done(function (response) {
                self.showResponseForm(
                    response,
                    actionUrl.CREATE_CUSTOMER + '?clubId=' + self.clubId,
                    title,
                    null,
                    message
                );
            });
        });

        this.container.on('click', selector.BUTTON_UPDATE, function(e) {
            let button = $(this),
                url = button.attr('data-url'),
                title = button.attr('data-title'),
                customerId = button.attr('data-customer-id'),
                message = JSON.parse(button.attr('data-message'));

            e.preventDefault();

            $.ajax({
                type:'post',
                url: url
            }).done(function(response) {
                self.showResponseForm(
                    response,
                    actionUrl.UPDATE_CUSTOMER + '?id=' + customerId,
                    title,
                    'club-cp',
                    message
                );
            });
        });

        this.container.on('click', selector.BUTTON_ADD, function () {
            let button = $(this),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            $.ajax({
                url: actionUrl.ADD_CLIENTS_FORM,
                data: {
                    clubId: self.clubId,
                }
            }).done(function (response) {
                self.showResponseForm(response,
                    actionUrl.ADD_CLIENT + '?clubId=' + self.clubId,
                    title,
                    null,
                    message
                );
            });
        });

        this.initDeleteEvent(selector.BUTTON_DELETE);

        this.container.on('click', selector.BUTTON_SET_STATUS, function (e) {
            let icon = $(this),
                url = icon.data('url'),
                message = JSON.parse(icon.attr('data-message')),
                labels = JSON.parse(icon.attr('data-labels'));

            e.preventDefault();

            self.lock();
            $.ajax({
                type: 'post',
                url: url
            }).done(function (response) {
                if (!response.success) {
                    Snackbar.show({
                        type: SnackbarType.ERROR,
                        message: message.error,
                    });
                    return;
                }

                Snackbar.show({
                    type: SnackbarType.SUCCESS,
                    message: message.success,
                });

                icon.toggleClass('label-success label-danger');
                icon.text(icon.text() === labels.success ? labels.warning : labels.success);
            }).always(function () {
                self.unlock();
            });
        });
    }

    refreshListByResponse(response)
    {
        let html = $(response.html);
        this.container.find('.grid-view').html(html.find('.grid-view').html());
    }
}

export {CustomerClubListing};