(function ($) {
    "use strict";


    jQuery(document).ready(function ($) {

        $.getJSON(ajaxurl + "?action=stgh-get-savedrepliesmacros", function (data) {

            $("#wp-stgh_content_auto_reply-media-buttons").prepend('<select name="macros" id="stgh_macros_auto_reply"><option></option></select>');


            $("#stgh_macros_auto_reply").stgselect2({
                placeholder: '<< ' + savedrepliesLocale.selectMacros + " >>",
                minimumResultsForSearch: Infinity,
                data: data
            });

            $("#wp-stgh_stgh_email_footer_content-media-buttons").prepend('<select name="macros" id="stgh_macros_footer_content"><option></option></select>');

            $("#stgh_macros_footer_content").stgselect2({
                placeholder: '<< ' + savedrepliesLocale.selectMacros + " >>",
                minimumResultsForSearch: Infinity,
                data: data
            });

            $("#stgh_macros_auto_reply").on("stgselect2:select", function (e) {

                if ($('#stgh_content_auto_reply').is(':visible')) {
                    $('#stgh_content_auto_reply').insertAtCaret($("#stgh_macros_auto_reply").stgselect2("val"));

                } else {
                    tinymce.execCommand('mceInsertContent', false, $("#stgh_macros_auto_reply").stgselect2("val"));
                }
                $("#stgh_macros_auto_reply").stgselect2("val", false);
            });


            $("#stgh_macros_footer_content").on("stgselect2:select", function (e) {

                if ($('#stgh_stgh_email_footer_content').is(':visible')) {
                    $('#stgh_stgh_email_footer_content').insertAtCaret($("#stgh_macros_footer_content").stgselect2("val"));

                } else {
                    tinymce.execCommand('mceInsertContent', false, $("#stgh_macros_footer_content").stgselect2("val"));
                }
                $("#stgh_macros_footer_content").stgselect2("val", false);
            });


        });

    });

}(jQuery));

