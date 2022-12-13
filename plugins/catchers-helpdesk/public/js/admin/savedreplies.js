(function ($) {
    "use strict";

    jQuery(document).ready(function ($) {

        var ticketId = $('#post_ID').val();
        var authorId = $('#post_author').val();
        var userId = $('#user-id').val();

        $.getJSON(ajaxurl + "?action=stgh-get-savedreplies", function (data) {

            if($("#wp-stgh_comment-editor-tools").length) {
                $("#wp-stgh_comment-editor-tools").prepend('<select name="replies" id="replies"><option></option></select>');
            }else {
                $("#stgh-form-agent").prepend('<select name="replies" id="replies"><option></option></select>');
            }
            $("#replies").stgselect2({
                placeholder: '<< ' + savedrepliesLocale.selectSavedReply + " >>",
                data: data
            });

            if($("#wp-stgh_comment_private-editor-tools").length) {
                $("#wp-stgh_comment_private-editor-tools").prepend('<select name="replies2" id="replies2" style="border:2px solid red;"><option></option></select>');
            }else {
                $("#stgh-form-private").prepend('<select name="replies2" id="replies2" style="border:2px solid red;margin-bottom:5px;"><option></option></select>');
            }

            $("#replies2").stgselect2({
                placeholder: '<< ' + savedrepliesLocale.selectSavedReply + " >>",
                data: data
            });

            $("#replies").on("stgselect2:select", function (e) {
                $.getJSON(ajaxurl + "?action=stgh-get-savedreplytext", {
                    'rid': $("#replies").stgselect2("val"),
                    'tid': ticketId,
                    'aid': authorId,
                    'uid': userId
                }, function (data) {
                    if ($('#stgh_comment').is(':visible')) {
                        //$('#stgh_comment').val(data);
                        $('#stgh_comment').insertAtCaret(data);

                    } else {
                        var editor = tinymce.get('stgh_comment');
                        //editor.setContent(data);
                        editor.execCommand('mceInsertContent', false, data);
                    }
                    $("#replies").stgselect2("val", false);
                });
            });

            $("#replies2").on("stgselect2:select", function (e) {
                $.getJSON(ajaxurl + "?action=stgh-get-savedreplytext", {
                    'rid': $("#replies2").stgselect2("val"),
                    'tid': ticketId,
                    'aid': authorId,
                    'uid': userId
                }, function (data) {
                    if ($('#stgh_comment_private').is(':visible')) {
                        //$('#stgh_comment_private').val(data);
                        $('#stgh_comment_private').insertAtCaret(data);

                    } else {
                        var editor = tinymce.get('stgh_comment_private');
                        //editor.setContent(data);
                        editor.execCommand('mceInsertContent', false, data);
                    }
                    $("#replies2").stgselect2("val", false);
                });
            });


        });
    });

}(jQuery));

