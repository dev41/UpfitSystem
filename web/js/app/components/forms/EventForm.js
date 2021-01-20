import {CoachingForm} from "./CoachingForm";

class EventForm extends CoachingForm
{
    constructor(container)
    {
        super(container);
        this.event = container.data('event');
        this.refreshCustomerProgress();
    }

    initElements()
    {
        super.initElements();

        this.customersSelect = this.container.find('.js-customer-select');
        this.customerProgress = this.container.find('.js-customer-progress');
        this.customerProgressLabel = this.customerProgress.find('span');
    }

    initEvents()
    {
        let self = this;

        super.initEvents();

        this.container.on('change', '.js-customer-select', function() {
            self.refreshCustomerProgress();
        });
    }

    refreshCustomerProgress()
    {
        let capacity = this.coaching.capacity,
            customers = this.customersSelect.val() ? this.customersSelect.val().length : 0,
            width = (customers / capacity) * 100;

        if (width < 0) {
            width = 0;
        }
        if (width > 100) {
            width = 100;
        }

        this.customerProgress.css('width', width + '%');
        this.customerProgressLabel.html(customers + '/' + capacity);

        this.customerProgress.removeClass('progress-bar-success progress-bar-warning progress-bar-danger');

        if (width < 70) {
            this.customerProgress.addClass('progress-bar-success');
        } else if (width >= 70 && width < 90) {
            this.customerProgress.addClass('progress-bar-warning');
        } else if (width <= 100) {
            this.customerProgress.addClass('progress-bar-danger');
        }
    }
}

export {EventForm}