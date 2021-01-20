import {Snackbar, SnackbarType} from "../Snackbar";
import {TriggerForm} from "./TriggerForm";

class NewsletterForm extends TriggerForm
{
    constructor(container)
    {
        super(container);
    }

    initElements()
    {
        this.elements.buttonSend = this.container.find('.js-button-send');
        this.elements.typeSortableInput = this.container.find('.js-trigger-type-sortable-input');
        this.elements.typeSortableInputAvailable = this.container.find('.js-trigger-type-sortable-input-available');
        this.elements.senderInput = this.container.find('.js-sender-email');
    }

    initEvents()
    {
        let self = this;

        this.elements.buttonSend.on('click', function () {
            let button = $(this),
                url = button.attr('data-url'),
                notificationMessage = JSON.parse(button.attr('data-notification-message')),
                newsletterId = button.attr('data-model-id'),
                data = {'id': newsletterId};

            if(!newsletterId) {
                data = self.container.serialize();
            }

            self.lock();

            $.ajax({
                type: 'POST',
                url: url,
                data: data
            }).done(function () {
                if (notificationMessage) {
                    Snackbar.show({
                        type: SnackbarType.SUCCESS,
                        message: notificationMessage.success,
                    });
                }
            }).fail(function () {
                if (notificationMessage) {
                    Snackbar.show({
                        type: SnackbarType.ERROR,
                        message: notificationMessage.error,
                    });
                }
            }).always(function () {
                self.unlock();
            });
        });

        this.elements.typeSortableInput.on('change', function (e) {
            e.preventDefault();
            self.refreshEmailField();
        });

        this.elements.typeSortableInputAvailable.on('change', function (e) {
            e.preventDefault();
            self.refreshEmailField();
        });
    }
}

export {NewsletterForm}