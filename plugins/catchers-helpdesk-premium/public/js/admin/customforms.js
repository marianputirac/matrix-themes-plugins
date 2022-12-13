(function ($) {
    "use strict";

    jQuery(document).ready(function ($) {

        $('#edittag').submit(function(){
            if ($('#stgh_customforms_description').is(':visible')) {
                var description = $('#stgh_customforms_description').val();
            } else {
                var description = tinyMCE.activeEditor.getContent({format : 'raw'});
            }

            var return_value = true;
            var message = '';


            // if(description.indexOf('[ticket_field \'subject\']') == -1) {
            //     return_value = false;
            //     message = message+'\n'+ customFormsLocale.requiredField +' [ticket_field \'subject\']'+' '+customFormsLocale.notFound;
            // }

            // if(description.indexOf('[ticket_field \'message\']') == -1){
            //     return_value = false;
            //     message = message+'\n'+ customFormsLocale.requiredField +' [ticket_field \'message\']'+' '+customFormsLocale.notFound;
            // }

            // if(description.indexOf('[contact_field \'name\']') == -1){
            //     return_value = false;
            //     message = message+'\n'+ customFormsLocale.requiredField +' [contact_field \'name\']'+' '+customFormsLocale.notFound;
            // }


            if(description.indexOf('[contact_field \'email\']') == -1){
                return_value = false;
                message = message+'\n'+ customFormsLocale.requiredField +' [contact_field \'email\']'+' '+customFormsLocale.notFound;
            }

            if(message != '')
                alert(message);

            return return_value;
        });

        $.getJSON(ajaxurl + "?action=stgh-get-customfields", function (data) {

            if($(".wp-editor-tools").length) {
                $(".wp-editor-tools:visible").prepend('<select name="fields" id="fields"><option></option></select>');
            }else {
                $("#wp-stgh_customforms_description-wrap").prepend('<select name="fields" id="fields"><option></option></select>');
            }

            $("#fields").stgselect2({
                placeholder: '<< ' + customFormsLocale.selectFields + " >>",
                minimumResultsForSearch: Infinity,
                data: data
            });



            $("#fields").on("stgselect2:select", function (e) {

                var insertValue = $("#fields").stgselect2("val");
                var insertLabel = $("#fields").stgselect2("data")[0]['label'];;

                if(insertLabel)
                    insertValue = '<p><strong><label>'+insertLabel+':</label></strong><br />'+insertValue+'</p>';

                if ($('#stgh_customforms_description').is(':visible')) {

                    $('#stgh_customforms_description').insertAtCaret(insertValue);
                } else {
                    tinymce.execCommand('mceInsertContent', false, insertValue);
                }
                $("#fields").stgselect2("val", false);
            });

        });
    });

}(jQuery));

