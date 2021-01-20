import {VisualComponent} from "../VisualComponent";

const actionUrl = {
    SCHEDULE: '/schedule/get-schedule-as-component',
};

class CoachingForm extends VisualComponent
{
    constructor(container)
    {
        super(container);
        this.initElements();
        this.initEvents();

        this.coaching = container.data('coaching');
    }

    initElements()
    {
        this.elements.buttonSchedule = '.js-button-schedule';
        this.elements.buttonClubSelect = '.js-club-select';
        this.elements.redirectUrl = '/coaching/index';
        this.elements.formPopup = $('<div class="uf-popup uf-component" title="">');
        this.elements.formPopup.dialog({
            autoOpen: false,
            width: 1150,
            modal: true,
            resizable: false,
        });
    }

    initEvents()
    {
        let self = this;

        this.container.on('change', this.elements.buttonClubSelect, function() {
            let clubIds = $(this).val(),
                placeSelect = self.container.find('.js-place-select');

            $.ajax({
                type: 'POST',
                url: '/coaching/get-place-by-clubs',
                data: {
                    clubs: clubIds
                }
            }).done(function (response) {
                let places = response.places;

                placeSelect.children().remove();
                if (!places) {
                    return;
                }

                let options = '';

                for (let placeId in places) {
                    options += "<option value='" + placeId + "'>" + places[placeId] + "</option>";
                }

                placeSelect.html(options);
            });
        });

        this.container.on('click', this.elements.buttonSchedule, function() {
            let button = $(this),
                title = button.attr('data-title');

            const {Scheduler} = require('../Scheduler');

            $.ajax({
                url: actionUrl.SCHEDULE,
                data: {
                    coachingId: self.coaching.id,
                }
            }).done(function (response) {
                let formPopup = self.elements.formPopup,
                    newForm = response.html;

                formPopup.html(newForm);
                formPopup.dialog({title: title});

                formPopup.dialog('open');

                new Scheduler($('.js-schedule-container'));

                formPopup.closest('.ui-dialog').css('top', (window.innerHeight - formPopup.height()) / 2);

                formPopup.find('.js-button-cancel').on('click', function () {
                    formPopup.dialog('close');
                });
            });
        });
    }
}

export {CoachingForm};