function loadStep(selector)
{
    jQuery(selector + ' > form').submit(function (event) {
        var target = jQuery(selector).attr('data-target');
        if (target != undefined) {
            event.preventDefault();
            jQuery.post(jQuery(this).attr('action'), jQuery(this).serializeArray(), function (data, textStatus, jqXHR) {
                var selectordata = jQuery("<div>").append(jQuery.parseHTML(data)).find(selector);
                var targetdata = jQuery("<div>").append(jQuery.parseHTML(data)).find(target);
                if (selectordata.length > 0)
                {
                    jQuery(selector).html(jQuery(selectordata).html());
                    loadStep(selector);
                }
                if (targetdata.length > 0) {
                    if (jQuery(target, jQuery(selector).parent()).length == 0) {
                        jQuery(targetdata).insertAfter(selector);
                    } else {
                        jQuery(target).html(jQuery(targetdata).html());
                    }
                    loadStep(target);
                }
            });
        }
    });

    var testtarget = jQuery(selector).attr('data-target');
    if (testtarget != undefined) {
        jQuery('.form-actions', jQuery(selector + ' > form')).hide();
        jQuery('select', jQuery(selector + ' > form')).change(function (event) {
            if (jQuery(this).parents('form').length > 0)
            {
                jQuery(jQuery(this).parents('form')[0]).trigger("submit");
            }
        });
        var value = jQuery('select', jQuery(selector + ' > form').val());
        for (var i = 0 ; i < value.length; i++)
        {
            if (value[i].value != "")
            {
                jQuery(jQuery(selector + ' > form')[0]).trigger("submit");
                break;
            }
        }
    }
}

jQuery(document).ready(function () {
    jQuery('.tabbable .nav-tabs li a').click(function (e) {
        e.preventDefault();
        jQuery(this).tab('show');
    });

    loadStep('.form-step.step-1');
});