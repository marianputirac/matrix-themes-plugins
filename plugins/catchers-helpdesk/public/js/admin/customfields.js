(function ($) {
    "use strict";

    jQuery(document).ready(function ($) {
        function toggleByType(value){
            $("[name^='stgh_term_meta[custom_field_format_meta]']").parents('.form-field').hide();
            switch(value){
                case 'dropdown':
                case 'checkboxes':
                case 'radio':
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").parents('.form-field').show();
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").prop('disabled', false);

                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").parents('.form-field').hide();
                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").prop('disabled', true);
                    break;
                case 'text':
                case 'textarea':
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").parents('.form-field').hide();
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").prop('disabled', true);

                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").parents('.form-field').show();
                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").prop('disabled', false);
                    break;
                case 'file':
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").parents('.form-field').hide();
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").prop('disabled', true);

                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").parents('.form-field').hide();
                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").prop('disabled', true);
                    break;
                case 'date':
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").parents('.form-field').hide();
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").prop('disabled', true);

                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").parents('.form-field').show();
                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").prop('disabled', false);

                    $("[name^='stgh_term_meta[custom_field_format_meta]']").parents('.form-field').show();
                    $("[name^='stgh_term_meta[custom_field_format_meta]']").prop('disabled', false);
                    break;
                case 'hidden':
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").parents('.form-field').hide();
                    $("[name^='stgh_term_meta[custom_field_options_meta]']").prop('disabled', true);

                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").parents('.form-field').show();
                    $("[name^='stgh_term_meta[custom_field_dvalue_meta]']").prop('disabled', false);
                    break;
            }
        }

        toggleByType($("[name^='stgh_term_meta[custom_field_type_meta]']").val());

        $("[name^='stgh_term_meta[custom_field_type_meta]']").on('change',function(){
                toggleByType($(this).val());
       });

    });

}(jQuery));

