import {VisualComponent} from "../VisualComponent";
import {ConfirmModalDialog} from "../ConfirmModalDialog";
import {ErrorHelper} from "../../helpers/ErrorHelper";
import {Snackbar, SnackbarType} from "../Snackbar";

class Listing extends VisualComponent
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

        this.container.on('click', deleteButtonClass, function(e) {
            let button = $(this),
                confirmMessage = button.attr('data-confirm-message'),
                title = button.attr('data-title'),
                url = button.attr('data-url') || link.closest('a').attr('href'),
                notificationMessage;

            if (button.attr('data-notification-message')) {
                notificationMessage = JSON.parse(button.attr('data-notification-message'));
            }

            e.preventDefault();

            let deleteAjax = function() {
                $.ajax({
                    type:'post',
                    url: url,
                    data: {isAjax: true}
                }).done(function(response) {
                    self.refreshListByResponse(response);
                    if (notificationMessage) {
                        Snackbar.show({
                            type: SnackbarType.SUCCESS,
                            message: notificationMessage.success,
                        });
                    }
                }).fail(function (xhr) {
                    if (notificationMessage) {
                        Snackbar.show({
                            type: SnackbarType.ERROR,
                            message: notificationMessage.error,
                        });
                    }
                }).always(function() {
                    self.unlock();
                });
            };

            if (!title) {
                deleteAjax();
            } else {
                new ConfirmModalDialog({
                    template: self.confirmDialogTemplate,
                    data: {
                        '{title}': title,
                        '{message}': confirmMessage,
                    },
                    button: {
                        'confirm': deleteAjax,
                    }
                });
            }
        });
    }

    /**
     * @param {Object} response
     * @param {String} processUrl
     * @param {String} title
     * @param {String} pjaxId
     * @param {String} message
     * @param {Object} [options]
     * @param {Function} [options.onDialogOpen]
     * @param {Object} [options.popupOptions]
     * @param {String} [options.width]
     * @returns {*}
     */
    showResponseForm(response, processUrl, title, pjaxId, message, options)
    {
        options = options || {};

        let self = this,
            formPopup = this.elements.formPopup,
            popupOptions = $.extend({title: title}, options.popupOptions || {}),
            newForm = response.html;

        formPopup.html(newForm);
        formPopup.dialog({
            autoOpen: false,
            width: 550,
            position: { my: "center center", at: "center center", of: window },
            modal: true,
            resizable: false
        });
        formPopup.dialog(popupOptions);
        formPopup.dialog('open');

        if (options.onDialogOpen instanceof Function) {
            options.onDialogOpen(formPopup);
        }

        newForm = formPopup.find('form');

        formPopup.find('.js-button-cancel').on('click', function () {
            formPopup.dialog('close');
        });

        formPopup.find('.js-button-process').on('click', function (e) {
            e.preventDefault();
            self.lock(newForm);

            $.ajax({
                url: processUrl,
                method: 'POST',
                data: newForm.serialize()

            }).done(function (response) {
                if (response['redirectUrl']) {
                    window.document.location.href = response['redirectUrl'];
                }
                if (message) {
                    Snackbar.show({
                        type: SnackbarType.SUCCESS,
                        message: message.success,
                    });
                }

                formPopup.dialog('close');

                if (pjaxId) {
                    $.pjax.defaults.timeout = 3000;
                    $.pjax.reload({container: '#' + pjaxId});
                } else {
                    self.refreshListByResponse(response);
                }

            }).fail(function (xhr) {
                if (message) {
                    Snackbar.show({
                        type: SnackbarType.ERROR,
                        message: message.error,
                    });
                }
                ErrorHelper.highlightErrorsByXhrAndForm(xhr, newForm);
            }).always(function () {
                self.unlock(newForm);
            });
        });

        return newForm;
    }

    refreshListByResponse(response)
    {
        let html = $(response.html);
        this.container.find('.grid-view').html(html.find('.grid-view').html());
    }

    static getFormPopup()
    {
        let formPopup = $('<div class="uf-popup" title="">');
        formPopup.dialog({
            autoOpen: false,
            width: 550,
            modal: true,
            resizable: false
        });

        return formPopup;
    }
}

export {Listing};