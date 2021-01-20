import {VisualComponent} from "../VisualComponent";
import {Snackbar, SnackbarType} from "../Snackbar";

const selector = {
    BUTTON_COPY: '.js-button-copy',
};

class PlaceUpdate extends VisualComponent
{
    constructor(container)
    {
        super(container);

        this.initEvents();
    }

    initEvents() {
        let self = this;
        this.container.on('click', selector.BUTTON_COPY, function () {
            let button = $(this),
                url = button.attr('data-url'),
                notificationMessage = JSON.parse(button.attr('data-notification-message'));

            self.lock();

            $.ajax({
                url: url,
            }).done(function (response) {
                let redirectUrl = response.url;

                if (notificationMessage) {
                    Snackbar.show({
                        type: SnackbarType.SUCCESS,
                        message: notificationMessage.success,
                    });

                    setTimeout(function () {
                        window.location.assign(redirectUrl);
                        self.unlock();
                    }, 1000);
                } else {
                    window.location.assign(redirectUrl);
                }
            }).fail(function (xhr) {
                if (notificationMessage) {
                    Snackbar.show({
                        type: SnackbarType.ERROR,
                        message: notificationMessage.error,
                    });
                }
            });
        });
    }
}

export {PlaceUpdate}