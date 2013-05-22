function testPlaceholder()
{
    var test = document.createElement('input');
    if(!('placeholder' in test))
    {
        jQuery('.nolabel label').show();
    }
}

jQuery(document).ready(function(){
    
    jQuery('#cart form .sameaddress input[type=checkbox]').change(function(){
        if( jQuery(this).attr('checked'))
        {
            jQuery('#cart form .shippingaddress').hide();
        }
        else
        {
            jQuery('#cart form .shippingaddress').show();
        }
    });

    jQuery('#cart form .company-link').click(function () {
        jQuery('#cart form .company').toggle('fast');
        return false;
    });
    
    jQuery('form.contact-form').submit(function(){
        jQuery(this).parent().load(jQuery(this).attr('action'),jQuery(this).serializeArray(),function(data, textStatus, jqXHR){
            testPlaceholder();
        });
        return false;
    });
    
});