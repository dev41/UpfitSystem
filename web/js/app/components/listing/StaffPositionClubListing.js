import {Listing} from "./Listing";
import {ClubPositionsListing} from "./ClubPositionListing";

const event = {
    ADD_STAFF: 'OnAddStaff'
};

const actionUrl = {
    ADD_STAFF_FORM: '/staff-position-place/add-staff-form',
    ADD_STAFF: '/staff-position-place/add-staff',

    CREATE_STAFF_FORM: '/staff/get-create-form',
    CREATE_STAFF: '/staff/create',
    UPDATE_STAFF: '/staff/update',
    UPDATE_STAFF_FORM: '/staff/get-update-form',
};

const selector = {
    BUTTON_ADD_POSITION: '.js-button-add-staff', // ADD_STAFF_FORM
    BUTTON_UPDATE_POSITION: '.js-button-update-position', //ADD_STAFF
    BUTTON_CREATE: '.js-button-create-staff', //CREATE_STAFF_FORM
    BUTTON_UPDATE: '.js-button-update-staff', //ADD_STAFF
    BUTTON_DELETE: '.js-button-delete-staff',
};

class StaffPositionClubListing extends Listing
{
    constructor(container, clubId)
    {
        super(container);

        this.clubId = clubId;

        this.initElements();
        this.initEvents();
    }

    initElements()
    {
    }

    initEvents()
    {
        let self = this;

        // create new staff with this club and picked positions
        this.container.on('click', selector.BUTTON_CREATE, function () {
            let button = $(this),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            $.ajax({
                url: actionUrl.CREATE_STAFF_FORM,
                data: {
                    'clubId': self.clubId,
                }
            }).done(function (response) {
                self.showResponseForm(
                    response,
                    actionUrl.CREATE_STAFF + '?clubId=' + self.clubId,
                    title,
                    'club-spp',
                    message,
                    {
                        popupOptions: {
                            width: 'auto',
                            position: { my: "center top", at: "center top", of: window },
                        }
                    }
                );
            });
        });

        // add staff with positions
        this.container.on('click', selector.BUTTON_ADD_POSITION, function () {
            let button = $(this),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            $.ajax({
                url: actionUrl.ADD_STAFF_FORM,
                data: {
                    'clubId': self.clubId,
                }
            }).done(function (response) {
                self.showResponseForm(
                    response,
                    actionUrl.ADD_STAFF + '?clubId=' + self.clubId,
                    title,
                    'club-spp',
                    message
                );
            });
        });

        // update staff positions
        this.container.on('click', selector.BUTTON_UPDATE_POSITION, function(e) {
            let button = $(this),
                url = button.attr('data-url'),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            e.preventDefault();

            $.ajax({
                type:'post',
                url: url,
            }).done(function(response) {
                self.showResponseForm(response,
                    actionUrl.ADD_STAFF + '?clubId=' + self.clubId,
                    title,
                    'club-spp',
                    message,
                    {
                        onDialogOpen: function (popup) {
                            new ClubPositionsListing(popup.find('.js-club-position-container'));
                        }
                    }
                );

            });
        });

        // update staff
        this.container.on('click', selector.BUTTON_UPDATE, function(e) {
            let button = $(this),
                url = button.attr('data-url'),
                staffId = button.attr('data-staff_id'),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-message'));

            e.preventDefault();
            $.ajax({
                type:'post',
                url: url,
            }).done(function(response) {

                self.showResponseForm(response,
                    actionUrl.UPDATE_STAFF + '?id=' + staffId,
                    title,
                    'club-spp',
                    message,
                    {
                        onDialogOpen: function (popup) {
                            new ClubPositionsListing(popup.find('.js-club-position-container'));
                        },
                        popupOptions: {
                            width: 'auto',
                            position: { my: "center top", at: "center top", of: window }
                        }
                    }
                );

            });
        });
        this.initDeleteEvent(selector.BUTTON_DELETE);
    }
}

export {StaffPositionClubListing, event};