import {TemplateComponent} from "./TemplateComponent";

class StockTooltip extends TemplateComponent
{
    /**
     * @constructor
     *
     * @param {Object} options
     * @param {string|number} options.id
     * @param {jQuery} options.target
     * @param {string} options.template
     * @param {Object} [options.data]
     * @param {Object} [options.offsets]
     * @param {Object} [options.button]
     * @param {boolean} [options.hideOnMouseLeave] true
     */
    constructor(options)
    {
        super(options.template, options.data || {});

        this.options = options;
        this.id = options.id;
        this.target = options.target;
        this.hideOnMouseLeave = options.hasOwnProperty('hideOnMouseLeave') ? options.hideOnMouseLeave : true;
        this.offsets = options.offsets || {x: -5, y: -5};
        this.btnCallback = options.button || {};

        this.container = $(StockTooltip.bindTemplateData(this.template, this.data));
        this.buttons = this.container.find('.stock-tooltip-btn');

        StockTooltip.addTooltip(this.id, this.container);
        this.initEvents();
        $('body').append(this.container);
    }

    hide()
    {
        this.container.hide();
    }

    canDisplayedOnRight()
    {
        let targetPosition = this.target[0].getBoundingClientRect(),
            tooltipPosition = this.container.innerWidth(),
            maxXPosition = targetPosition.x + targetPosition.width + tooltipPosition + this.offsets.x;

        return (window.innerWidth - maxXPosition) > 0;
    }

    switchPositionClass(position)
    {
        this.container.removeClass('stock-tooltip-left stock-tooltip-right');

        switch (position) {
            case 'right': this.container.addClass('stock-tooltip-right'); return;
            case 'left': this.container.addClass('stock-tooltip-left'); return;
        }

        throw 'Unprocessable position for tooltip: ' + position;
    }

    show()
    {
        let targetPosition = this.target[0].getBoundingClientRect();

        this.container.css('top', targetPosition.y + this.offsets.y + 'px');

        if (this.canDisplayedOnRight()) {
            this.switchPositionClass('right');
            this.container.css('left', targetPosition.x + targetPosition.width + this.offsets.x + 'px');
        } else {
            this.switchPositionClass('left');
            this.container.css('left', targetPosition.x - this.container.innerWidth() - this.offsets.x + 'px');
        }

        this.container.show();
    }

    initEvents()
    {
        let self = this;

        this.target.on('mouseenter', function(e) {
            if (e.buttons !== 0) {
                return;
            }
            self.show();
        });

        let hideOnMouseLeave = function(e) {
            if (!self.hideOnMouseLeave) {
                return;
            }
            let t = $(e.relatedTarget);
            if (!t.closest(self.target).length && !t.closest(self.container).length) {
                self.hide();
            }
        };

        this.target.on('mouseleave', function(e) {
            hideOnMouseLeave(e);
        });
        this.container.on('mouseleave', function(e) {
            hideOnMouseLeave(e);
        });

        this.container.find('.stock-tooltip-close').on('click', function(e) {
            self.hide();
        });

        this.buttons.on('click', function() {
            let btnId = this.dataset['btn'];
            if (self.btnCallback[btnId] instanceof Function) {
                self.btnCallback[btnId](this);
            }
        });

        this.target.on('mousedown', function() {
            self.hide();
        });
    }

    static removeOutdatedTooltipsById(id)
    {
        if (StockTooltip.identities.indexOf(id) !== -1) {
            StockTooltip.tooltips.forEach(function(tooltip) {
                if (tooltip.id === id) {
                    tooltip.tooltip.remove();
                }
            });
        }
    }

    static addTooltip(id, tooltip)
    {
        this.removeOutdatedTooltipsById(id);

        StockTooltip.identities.push(id);
        StockTooltip.tooltips.push({
            id: id,
            tooltip: tooltip
        });
    }
}

StockTooltip.identities = [];
StockTooltip.tooltips = [];

export {StockTooltip}