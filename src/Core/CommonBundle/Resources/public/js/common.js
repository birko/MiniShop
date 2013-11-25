jQuery(document).ready(function () {
    jQuery('.tabbable .nav-tabs li a').click(function (e) {
        e.preventDefault()
        jQuery(this).tab('show');
    });
});