import {VisualComponent} from "../VisualComponent";

class StaffUpdate extends VisualComponent
{
    constructor(container, staffId)
    {
        super(container);
        this.initEvents();
    }

    initElements()
    {}

    initEvents()
    {
        this.elements.navTabs = '.nav-tabs a';

        let self = this,
            hash = window.location.hash;

        if (hash) {
            $('ul.nav a[href="' + hash + '"]').tab('show');
        }

        self.container.on('click', this.elements.navTabs, function () {
            let scrollmem = $('body').scrollTop() || $('html').scrollTop();

            $(this).tab('show');
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
        });
    }
}

export {StaffUpdate};