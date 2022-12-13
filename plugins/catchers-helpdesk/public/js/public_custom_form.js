window.doSubmit = false;
window.formId = false;
window.recapId = false;
window.mycaptchaid = null;


var onloadCallbackIn = function() {
    window.doSubmit = true;
    var mform = jQuery('#'+window.formId);
    mform.submit();

};

(function ($) {
    "use strict";

    jQuery(document).ready(function ($) {

        jQuery('#stgh-new-reply').submit(function(){
            jQuery('.stgh-btn').attr('disabled','disabled');
        });

        jQuery('.stg-form').submit(function(e){

            window.formId = jQuery(this).attr('id');
            window.recapId = 'g-recaptcha'+window.formId.replace('stg-ticket-form','');

            if(window.doSubmit)
            {
                jQuery('#'+window.formId+' #stgsubmit').attr('disabled','disabled');
                return true;
            }

            if(window.mycaptchaid !== null)
            {
                grecaptcha.reset(window.mycaptchaid);
            }
            else {
                window.mycaptchaid = grecaptcha.render(window.recapId, {
                    'sitekey': jQuery('.g-recaptcha').attr('data-sitekey'),
                    'callback': 'onloadCallbackIn',
                    'size': 'invisible',
                    'badge': 'inline'
                });
            }


            var captchaSize = jQuery('.g-recaptcha').attr('data-size');
            if(captchaSize == 'invisible'){
                e.preventDefault();
                grecaptcha.execute();
            }

        });

        var requiredCheckboxes = $(':checkbox[required]');

        requiredCheckboxes.change(function(){
            var fieldName = $(this).attr('name');

            if(requiredCheckboxes.filter("[name = '"+fieldName+"']").is(':checked')) {
                requiredCheckboxes.filter("[name = '"+fieldName+"']").removeAttr('required');
            }
            else {
                requiredCheckboxes.filter("[name = '"+fieldName+"']").attr('required', 'required');
            }
        });


        $('.datepicker').each( function(index,element){
            $(this).datepicker({dateFormat: $(this).attr('dateformat')});
        });
    });

}(jQuery));