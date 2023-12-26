function amoweather_editCssWidget(el)
{
    const value = jQuery(el).val();
    const spinner = jQuery('.amoweather-spinner-wrapper-css');

    const data = {
        'action': 'my_action',
        'save-css': true,
        'value':    value,
    };

        spinner.show();
    jQuery.post(ajaxurl, data, function(response) {
        setTimeout(function () {
            spinner.hide();
        }, 500)
    });
}
function amoweather_togglePublicWidget(el)
{
    const checked = jQuery(el).prop('checked') == false ? 0 : 1;
    const spinner = jQuery('.amoweather-spinner-wrapper');

    const data = {
        'action': 'my_action',
        'checked': checked,
    };

        spinner.show();
    jQuery.post(ajaxurl, data, function(response) {
        setTimeout(function () {
            spinner.hide();
        }, 500)
    });
}