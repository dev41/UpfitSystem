import {ConfirmModalDialog} from "./ConfirmModalDialog";
import {VisualComponent} from "./VisualComponent";
import {Listing} from "./listing/Listing";

class View extends VisualComponent
{
    constructor(container)
    {
        super(container);

        this.elements.formPopup = Listing.getFormPopup();
        this.confirmDialogTemplate = $('.js-template-confirm-modal-dialog').html();
    }

    /**
     * @param {String} [deleteButtonClass='.js-delete']
     */
    initDeleteEvent(deleteButtonClass)
    {
        let self = this;
        deleteButtonClass = deleteButtonClass || '.js-delete';

        this.container.on('click', deleteButtonClass, function (e) {
            let link = $(this),
                message = link.data('message'),
                title = link.data('title'),
                url = link.data('url') || link.closest('a').attr('href');

            e.preventDefault();

            new ConfirmModalDialog({
                template: self.confirmDialogTemplate,
                data: {
                    '{title}': title,
                    '{message}': message,
                },
                button: {
                    'confirm': function () { window.location.replace(url) },
                }
            });
        });
    }
}

export {View}