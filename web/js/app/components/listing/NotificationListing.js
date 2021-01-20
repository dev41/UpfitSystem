import {Listing} from "./Listing";

const selector = {
    BUTTON_DELETE: '.js-delete-notification',
    BUTTON_SET_STATUS: '.js-set-status',
};

class NotificationListing extends Listing
{
    constructor(container)
    {
        super(container);
        this.initEvents();
    }

    initEvents()
    {
        let self = this;

        this.initDeleteEvent(selector.BUTTON_DELETE);

        this.container.on('click', selector.BUTTON_SET_STATUS, function(e) {
            let icon = $(this),
                url = icon.data('url');

            e.preventDefault();

            self.lock();
            $.ajax({
                type: 'post',
                url: url
            }).done(function (response) {
                if (!response.success) {
                    return;
                }

                icon.toggleClass('fa-envelope-o fa-envelope text-yellow');
            }).always(function () {
                self.unlock();
            });
        });
    }
}

export {NotificationListing};