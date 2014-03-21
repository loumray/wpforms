jQuery(document).ready(function($){
    $.each(wpforms_slider_setup, function (sliderParams) {
        var that = this,
            slider = $("#"+that.container+' .wpforms-slider'),
            input = $("#"+that.container+' .wpforms-slider-input');
        slider.slider({
            value: input.val(),
            min: that.min,
            max: that.max,
            step: that.step,
            slide: function( event, ui ) {
                input.val(ui.value).keyup();
            }
        });
        input.val(slider.slider("value"));
    });
});