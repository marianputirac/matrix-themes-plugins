(function ($) {
    "use strict";

    jQuery(document).ready(function ($) {

        $('.stgh_qa_topics_list').live('change',function () {
            $('#stgh_term_meta\\[custom_field_topic_meta\\]').val($(this).val());
        })

        $.getJSON(ajaxurl + "?action=stgh-get-savedrepliesmacros", function (data) {

            if($(".wp-editor-tools").length) {
                $(".wp-editor-tools:visible").prepend('<select name="macros" id="macros"><option></option></select>');
            }else {
                $("#wp-stgh_savedreply_description-wrap").prepend('<select name="macros" id="macros"><option></option></select>');
            }


            $("#macros").stgselect2({
                placeholder: '<< ' + savedrepliesLocale.selectMacros + " >>",
                minimumResultsForSearch: Infinity,
                data: data
            });

            $("#macros").on("stgselect2:select", function (e) {

                if ($('#stgh_savedreply_description').is(':visible')) {
                    $('#stgh_savedreply_description').insertAtCaret($("#macros").stgselect2("val"));

                } else {
                    tinymce.execCommand('mceInsertContent', false, $("#macros").stgselect2("val"));
                }
                $("#macros").stgselect2("val", false);
            });

        });

    });

}(jQuery));

