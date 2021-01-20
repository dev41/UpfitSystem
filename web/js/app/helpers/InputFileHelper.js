import {Snackbar, SnackbarType} from "../components/Snackbar";

class InputFileHelper
{

    static init() {
        let images = $('.js-images'),
            logo = $('.js-logo');

        images.on("filepredelete", function() {
            let input = $(this),
                confirmMessage = input.attr('data-confirm-message'),
                abort = true;

            if (confirm(confirmMessage)) {
                abort = false;
            }
            return abort;
        });

        images.on('filedeleted', function () {
            let input = $(this),
                notificationMessage = input.attr('data-notification-message');

            if (!notificationMessage) {
                return;
            }

            Snackbar.show({
                type: SnackbarType.SUCCESS,
                message: notificationMessage,
            });
        });

        logo.on('filepredelete', function () {
            let input = $(this),
                confirmMessage = input.attr('data-confirm-message'),
                abort = true;

            if (confirm(confirmMessage)) {
                abort = false;
            }
            return abort;
        });

        logo.on('filedeleted', function () {
            let input = $(this),
                notificationMessage = input.attr('data-notification-message');

            if (!notificationMessage) {
                return;
            }

            Snackbar.show({
                type: SnackbarType.SUCCESS,
                message: notificationMessage,
            });
        });
    }
}

export {InputFileHelper};