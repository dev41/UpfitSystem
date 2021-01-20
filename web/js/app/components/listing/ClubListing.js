import {Listing} from "./Listing";

const selector = {
    BUTTON_CREATE: '.js-button-create',
};

class ClubListing extends Listing
{
    constructor(container)
    {
        super(container);

        this.initElements();
        this.initEvents();
    }

    initElements()
    {
        this.elements.createClubButton = this.container.find(selector.BUTTON_CREATE);
    }

    initEvents()
    {
        let self = this;

        this.initDeleteEvent();

        this.elements.createClubButton.on('click', function() {
            let button = $(this),
                title = button.attr('data-title'),
                message = JSON.parse(button.attr('data-notification-message'));

            $.ajax({
                url: '/club/get-create-club-form',
            }).done(function(response) {
                self.showResponseForm(
                    response,
                    '/club/create',
                    title,
                    null,
                    message
                );
            });
        });
    }

}

export {ClubListing}