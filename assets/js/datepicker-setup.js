jQuery(document).ready(function($){

  $('input.wpf-datepicker').each(function () {
        var options = wpforms_datepicker_setup[this.id] || {};
        options = options.options || {};
        $(this).datepicker(options);
    });
});