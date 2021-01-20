class ErrorHelper
{
    static highlightErrorsByXhrAndForm(xhr, form)
    {
        let message = JSON.parse(xhr.responseJSON.message),
            formHash = form.data('hash'),
            errors = message.errors,
            model = message.model,
            label, inputName, field, fieldRow, helperBlock;

        form.find('.has-error').removeClass('has-error');
        form.find('.help-block').html('');

        for (label in errors) {

            if (formHash) {
                inputName = model + '_' + formHash + '[' + label + ']';
            } else {
                inputName = model + '[' + label + ']';
            }

            field = form.find('[name="' + inputName + '"]');

            if (!field) {
                continue;
            }

            fieldRow = field.closest('.row');
            helperBlock = fieldRow.find('.help-block');

            helperBlock.html(errors[label][0]);
            fieldRow.addClass('has-error');
        }
    }
}

export {ErrorHelper}