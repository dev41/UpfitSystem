import {VisualComponent} from "./VisualComponent";

class TemplateComponent extends VisualComponent
{
    /**
     * @param {String} template
     * @param {Object} [data]
     */
    constructor(template, data)
    {
        let container = $(TemplateComponent.bindTemplateData(template, data || {}));
        super(container);

        this.template = template;
        this.data = data;
    }

    /**
     * @param {String} template
     * @param {Object} data
     * @returns {String}
     */
    static bindTemplateData(template, data)
    {
        let placeholder, regEx;
        for (placeholder in data) {
            if (!data.hasOwnProperty(placeholder)) {
                continue;
            }
            regEx = new RegExp(placeholder, 'g');
            template = template.replace(regEx, data[placeholder] ? data[placeholder] : '');
        }
        return template;
    }
}

export {TemplateComponent}