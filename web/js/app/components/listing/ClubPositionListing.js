import {Listing} from "./Listing";

const actionUrl = {
    ADD_CLUBS_FORM: '/staff-position-place/add-clubs-form',
    ADD_POSITION: '/staff-position-place/add-position',
};

const selector = {
    BUTTON_ADD: '.js-button-add-position',
    BUTTON_UPDATE: '.js-button-update-position',
    BUTTON_DELETE: '.js-button-delete-position',
};

class ClubPositionsListing extends Listing
{
    constructor(container)
    {
        super(container);
        this.staffId = container.data('staff_id');
        this.initEvents();
    }

    initEvents()
    {
        let self = this;
        // add staff with positions
        this.container.on('click', selector.BUTTON_ADD, function () {
            let button = $(this),
                title = button.attr('data-title'),
                url = button.attr('data-url'),
                message = JSON.parse(button.attr('data-message'));

            $.ajax({
                url: actionUrl.ADD_CLUBS_FORM,
            }).done(function (response) {
                self.showResponseForm(
                    response,
                    actionUrl.ADD_POSITION + '?staffId=' + self.staffId,
                    title,
                    null,
                    message
                );
            });

        });

        this.initDeleteEvent(selector.BUTTON_DELETE);

        // update staff positions
        this.container.on('click', selector.BUTTON_UPDATE, function(e) {
            let button = $(this),
                title = button.attr('data-title'),
                url = button.attr('data-url'),
                message = JSON.parse(button.attr('data-message'));

            e.preventDefault();

            $.ajax({
                type:'post',
                url: url,
            }).done(function(response) {
                self.showResponseForm(
                    response,
                    actionUrl.ADD_POSITION + '?staffId=' + self.staffId,
                    title,
                    null,
                    message
                );
            });
        });
    }

    refreshListByResponse(response)
    {
        let html = $(response.html);
        this.container.find('.grid-view').html(html.find('.grid-view').html());
    }
}

export {ClubPositionsListing};