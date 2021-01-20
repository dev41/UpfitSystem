import {ConfirmModalDialog} from "./ConfirmModalDialog";
import {VisualComponent} from "./VisualComponent";
import {TemplateComponent} from "./TemplateComponent";
import {EventForm} from "./forms/EventForm";
import {ErrorHelper} from "../helpers/ErrorHelper";
import {EffectHelper, highlightType} from "../helpers/EffectHelper";
import {Snackbar, SnackbarType} from "./Snackbar";

const event = {
    FC_ALL_RENDER: 'fcAllRender',
};

const fcConfig = {
    columnHeaderFormat: 'ddd',
    header: {
        left: 'prev,next month,agendaWeek,agendaDay today',
        center: 'title',
        right: ''
    },
    buttonIcons: {
        prev: ' fa fa-arrow-left',
        next: ' fa fa-arrow-right',
    },
    height: 'auto',
    slotLabelFormat: 'h A',
    minTime: '07:00:00',
    maxTime: '23:30:00',
    allDaySlot: false,
    allDayDefault: false,
    nextDayThreshold: false,
    defaultView: 'agendaWeek',
    displayEventTime: true,
    displayEventEnd: true,
    editable: true,
    eventStartEditable: true,
    eventDurationEditable: true,
    droppable: true,
    dropAccept: '.js-coaching',
    firstDay: 1, // Sunday=0, Monday=1, Tuesday=2, etc.
};

class Scheduler extends VisualComponent
{
    constructor(container)
    {
        super(container);
        this.elements = {};
        this.clubId = this.container.data('club_id');
        this.lastDroppedElement = null;
        this.lastEventIdViewRender = null;

        this.initElements();
        this.initEvents();
    }

    initElements()
    {
        this.elements.calendarContainer = this.container.find('.js-calendar');
        this.elements.coaching = this.container.find('.js-coaching');

        this.eventViewTemplate = this.container.find('.js-template-event-view').html();
        this.confirmDialogTemplate = this.container.find('.js-template-confirm-modal-dialog').html();

        this.eventViewContainer = this.container.find('.js-event-view-container');

        this.elements.eventPopup = this.container.find('.js-popup-event');
        this.elements.eventPopup.dialog({
            autoOpen: false,
            width: 550,
            modal: true,
            resizable: false
        });
        this.elements.eventPopupCloseButton = this.elements.eventPopup.closest('.ui-dialog').find('.ui-dialog-titlebar-close');

        this.initCalendar();

        this.elements.coaching.each(function() {
            let element = $(this),
                color = element.data('color');

            if (color) {
                element.css('background-color', color);
            }

            element.data('event', {
                title: $.trim(element.text()),
                stick: true
            });

            element.draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
    }

    static parseResponseToEventData(response)
    {
        let event = response.event;

        if (event.start) {
            event.start = moment(event.start);
        }
        if (event.end) {
            event.end = moment(event.end);
        }

        return event;
    }

    newEventPopup(fcEvent)
    {
        if (!(fcEvent instanceof Object) || !this.lastDroppedElement) {
            return;
        }

        let self = this,
            calendar = this.elements.calendar,
            message;

        this.lock();
        $.ajax({
            url: '/schedule/get-new-event-form',
            method: 'POST',
            data: {
                coachingId: this.lastDroppedElement.dataset.id,
                clubId: this.clubId,
                start: fcEvent.start.format('YYYY-MM-DD HH:mm:SS')
            }
        }).done(function(response) {
            let eventPopup = self.elements.eventPopup,
                form = response.form;

            eventPopup.html(form);
            form = eventPopup.find('form');
            new EventForm(form);
            eventPopup.dialog('open');

            // remove previous "removeEvents" callback functions
            self.elements.eventPopupCloseButton.off('click');
            self.elements.eventPopupCloseButton.on('click', function() {
                calendar.removeEvents(fcEvent._id);
                // need to "hard" close because use ".off" above
                eventPopup.dialog('close');
            });

            eventPopup.find('.js-button-cancel').on('click', function() {
                eventPopup.dialog('close');
                calendar.removeEvents(fcEvent._id);
            });

            eventPopup.find('.js-button-process').on('click', function(e) {
                e.preventDefault();
                self.lock();

                $.ajax({
                    url: '/schedule/new-event',
                    method: 'POST',
                    data: form.serialize()
                }).done(function(response) {
                    let eventData = Scheduler.parseResponseToEventData(response);
                    calendar.updateEvent($.extend(fcEvent, eventData));
                    self.renderEventView(fcEvent);
                    eventPopup.dialog('close');
                    message = response.message;
                    if (message) {
                        Snackbar.show({
                            type: SnackbarType.SUCCESS,
                            message: message.success,
                        });
                    }
                }).fail(function (xhr) {
                    ErrorHelper.highlightErrorsByXhrAndForm(xhr, form);
                }).always(function() {
                    self.unlock();
                });
            });

        }).always(function() {
            self.unlock();
        });
    }

    updateEventPopup(fcEvent)
    {
        let self = this,
            calendar = self.elements.calendar,
            eventId = fcEvent.event_id,
            message;

        self.lock();

        $.ajax({
            url: '/schedule/get-update-event-form',
            method: 'POST',
            data: {
                clubId: this.clubId,
                eventId: eventId
            }
        }).done(function(response) {
            let eventPopup = self.elements.eventPopup,
                form = response.form;

            eventPopup.html(form);
            form = eventPopup.find('form');
            new EventForm(form);
            eventPopup.dialog('open');

            self.elements.eventPopupCloseButton.off('click');
            self.elements.eventPopupCloseButton.on('click', function() {
                eventPopup.dialog('close');
            });

            eventPopup.find('.js-button-delete').on('click', function () {
                let title = $(this).attr('data-title'),
                    message = $(this).attr('data-message') + ' "' + fcEvent.title + '"?';

                new ConfirmModalDialog({
                    template: self.confirmDialogTemplate,
                    data: {
                        '{title}': title,
                        '{message}': message
                    },
                    button: {
                        'confirm': function() {
                            self.deleteEvent(fcEvent);
                            eventPopup.dialog('close');
                        }
                    }
                });
            });

            eventPopup.find('.js-button-cancel').on('click', function() {
                eventPopup.dialog('close');
            });

            eventPopup.find('.js-button-process').on('click', function(e) {
                self.lock();

                $.ajax({
                    url: '/schedule/update-event?eventId=' + eventId,
                    method: 'POST',
                    data: form.serialize()
                }).done(function(response) {
                    let eventData = Scheduler.parseResponseToEventData(response);
                    calendar.updateEvent($.extend(fcEvent, eventData));
                    self.renderEventView(fcEvent);
                    EffectHelper.highlight(self.eventViewContainer);
                    eventPopup.dialog('close');
                    message = response.message;
                    if (message) {
                        Snackbar.show({
                            type: SnackbarType.SUCCESS,
                            message: message.success,
                        });
                    }
                }).fail(function(xhr) {
                    ErrorHelper.highlightErrorsByXhrAndForm(xhr, form);
                }).always(function() {
                    self.unlock();
                });

                e.preventDefault();
            });
        }).always(function() {
            self.unlock();
        });
    }

    updateEventDate(fcEvent)
    {
        let self = this,
            eventId = fcEvent.event_id,
            message;

        self.lock();
        $.ajax({
            url: '/schedule/update-event-date',
            method: 'POST',
            data: {
                eventId: eventId,
                start: fcEvent.start.format('YYYY-MM-DD HH:mm:SS'),
                end: fcEvent.end ? fcEvent.end.format('YYYY-MM-DD HH:mm:SS') : null
            }
        }).done(function(response) {
            message = response.message;
            if (message) {
                Snackbar.show({
                    type: SnackbarType.SUCCESS,
                    message: message.success,
                });
            }
        }).always(function () {
            self.unlock();
        });
    }

    deleteEvent(fcEvent)
    {
        let self = this,
            calendar = self.elements.calendar,
            eventId = fcEvent.event_id,
            message;

        self.lock();
        $.ajax({
            url: '/schedule/delete-event',
            method: 'POST',
            data: {
                eventId: eventId
            }
        }).done(function(response) {
            calendar.removeEvents(fcEvent._id);
            self.eventViewContainer.html('<h3>Event information</h3>');
            EffectHelper.highlight(self.eventViewContainer);
            message = response.message;
            if (message) {
                Snackbar.show({
                    type: SnackbarType.SUCCESS,
                    message: message.success,
                });
            }
        }).always(function () {
            self.unlock();
        });
    }

    getEventView(fcEvent)
    {
        let self = this,
            eventView = $(TemplateComponent.bindTemplateData(this.eventViewTemplate, {
                '{title}': fcEvent.title,
                '{clubs}': fcEvent.clubNames,
                '{places}': fcEvent.placeNames,
                '{start}': fcEvent.start.format('MMMM Do YYYY, h:mm:ss a'),
                '{end}': fcEvent.end ? fcEvent.end.format('MMMM Do YYYY, h:mm:ss a') : '',
                '{level}': fcEvent.level,
                '{description}': fcEvent.description,
                '{capacity}': fcEvent.capacity,
                '{price}': fcEvent.price,
                '{color}': fcEvent.color,
                '{trainers}': fcEvent.trainerNames,
                '{customers}': fcEvent.customers,
            }));

        eventView.find('.js-data').each(function() {
            let e = $(this);
            if (e.html() === '') {
                e.closest('.form-group').remove();
            }
        });

        eventView.find('.js-button-delete').on('click', function () {
            let title = $(this).attr('data-title'),
                message = $(this).attr('data-message') + ' "' + fcEvent.title + '"?';

            new ConfirmModalDialog({
                template: self.confirmDialogTemplate,
                data: {
                    '{title}': title,
                    '{message}': message
                },
                button: {
                    'confirm': function() {
                        self.deleteEvent(fcEvent);
                    }
                }
            });
        });

        eventView.find('.js-button-update').on('click', function() {
            self.updateEventPopup(fcEvent);
        });

        return eventView;
    }

    renderEventView(fcEvent)
    {
        this.eventViewContainer.html(this.getEventView(fcEvent));
    }

    copyEvent(fcEvent)
    {
        let self = this,
            calendar = self.elements.calendar,
            eventId = fcEvent.event_id;

        self.lock();
        $.ajax({
            url: '/schedule/copy-event',
            method: 'POST',
            data: {
                eventId: eventId,
                start: fcEvent.start.format('YYYY-MM-DD HH:mm:SS'),
                end: fcEvent.end ? fcEvent.end.format('YYYY-MM-DD HH:mm:SS') : null
            }
        }).done(function(response) {
            let eventData = Scheduler.parseResponseToEventData(response);
            calendar.renderEvent(eventData, true);
        }).always(function () {
            self.unlock();
        });
    }

    initCalendar()
    {
        let self = this,
            events = this.elements.calendarContainer.data('events'),
            lang = this.elements.calendarContainer.data('lang');

        this.elements.calendarContainer.fullCalendar($.extend({}, fcConfig, {
            events: events,
            locale: lang,
            eventAfterAllRender: function () {
                self.trigger(event.FC_ALL_RENDER);
            },
            eventClick: function(fcEvent) {
                self.updateEventPopup(fcEvent);
            },
            eventReceive: function(fcEvent) {
                self.newEventPopup(fcEvent);
            },
            viewRender: function(view) {
                return;
                if (view.name !== 'agendaWeek' && view.name !== 'month') {
                    return;
                }

                let copyDate,
                    availableDayClasses = [
                        'fc-mon',
                        'fc-tue',
                        'fc-wed',
                        'fc-thu',
                        'fc-fri',
                        'fc-sat',
                        'fc-sun',
                    ],
                    calendar = view.calendar.el,
                    fcHeaderDays = calendar.find('.fc-day-header'),
                    arrayFilter = Array.prototype.filter,
                    headerDayTemplate = $('.js-template-header-day').html();

                fcHeaderDays.each(function() {
                    let day = $(this),
                        date = day.data('date'),
                        html = headerDayTemplate
                            .replace('{front}', day.html());

                    day.html(html);

                    day.find('button').on('click', function() {
                        day.find('.js-flip-container').addClass('flip');
                        copyDate = date;
                    });

                    day.find('.js-flip-container').on('mouseleave', function() {
                        if (!copyDate) {
                            let bgColumns = calendar.find('.fc-time-grid.fc-unselectable .fc-bg table');
                            bgColumns.find('td').removeClass('active');
                        }
                    });

                    day.find('.js-flip-container').on('mouseenter', function() {
                        let el = $(this),
                            dayClasses = el.closest('th')[0].classList,
                            dayClass = arrayFilter.call(dayClasses, value => -1 !== availableDayClasses.indexOf(value));

                        let events = view.calendar.clientEvents(function(event) {
                            let start = event.start.format('YYYY-MM-DD');

                            return start === date;
                        });

                        if (events.length === 0) {
                            return;
                        }

                        let bgColumns = calendar.find('.fc-time-grid.fc-unselectable .fc-bg table');

                        bgColumns.find('td[data-date="' + date + '"]').addClass('active');
                    });
                });

            },
            eventDrop: function(fcEvent, delta, revertFunc, jsEvent, ui, view ) {
                if (jsEvent.shiftKey) {
                    self.copyEvent(fcEvent);
                    revertFunc();
                } else {
                    self.updateEventDate(fcEvent);
                }
            },
            eventResize: function(fcEvent, element) {
                self.updateEventDate(fcEvent);
            },
            drop: function(date) {
                self.lastDroppedElement = this;
            },
            eventAfterRender: function(fcEvent, element) {
                element.on('mouseenter', function() {
                    if (self.lastEventIdViewRender === fcEvent.event_id) {
                        return;
                    }
                    self.lastEventIdViewRender = fcEvent.event_id;
                    self.renderEventView(fcEvent);
                });
            },
        }));

        this.elements.calendarContainer.find('.fc-prev-button').html('<i class="glyphicon glyphicon-menu-left"></i>');
        this.elements.calendarContainer.find('.fc-next-button').html('<i class="glyphicon glyphicon-menu-right"></i>');

        this.elements.calendar = this.elements.calendarContainer.fullCalendar('getCalendar');
    }

    initEvents()
    {
        this.container.find('.js-select-club').on('change', '#subplace-parent_id', function () {
            let club_id = $(this).val();
            if (club_id) {
                window.location.href = '/schedule/index?club_id=' + club_id;
            } else {
                window.location.href = '/schedule/index';
            }
        });
    }
}

export {Scheduler, event as SchedulerEvent};