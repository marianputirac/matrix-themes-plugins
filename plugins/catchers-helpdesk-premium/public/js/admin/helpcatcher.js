(function ($) {
    "use strict";


    jQuery(document).ready(function ($) {

        var tinyEditor = null;
        var tinyValue = $('#stgh_helpcatcher_result_msg').val();

        function getEditor() {
            for (var i = 0; i < tinymce.editors.length; i++) {
                tinyEditor = tinymce.editors[i];

                tinyEditor.on('keyup', function () {
                    tinyValue = this.getContent();
                    putWidgetCode();
                });
            }
        }


        function getAllParamValues() {
            var paramList = {
                'buttonColor': 'helpcatcher_button_color',
                'enableUpload': 'helpcatcher_enable_attachment',
                'headerText': 'helpcatcher_letter_start',
                'resultMessage': 'helpcatcher_result_msg',
                'align': 'helpcatcher_position',
                'gdprEnable': 'helpcatcher_gdpr_enable',
                'hideName': 'helpcatcher_hide_name',
                'hideSubject': 'helpcatcher_hide_subject',
                'gdprUrl': 'helpcatcher_gdpr_url'
            };

            var result = {};
            for (var i in paramList) {
                var value = '';
                switch (paramList[i]) {
                    case 'helpcatcher_position':
                        value = $('select[name=\'stgh_helpcatcher_position\']').val();
                        break;
                    case 'helpcatcher_enable_attachment':
                        value = JSON.parse($('#stgh_helpcatcher_enable_attachment').prop('checked'));
                        break;
                    case 'helpcatcher_gdpr_enable':
                        value = JSON.parse($('#stgh_helpcatcher_gdpr_enable').prop('checked'));
                        break;
                    case 'helpcatcher_hide_name':
                        value = JSON.parse($('#stgh_helpcatcher_hide_name').prop('checked'));
                        break;
                    case 'helpcatcher_hide_subject':
                        value = JSON.parse($('#stgh_helpcatcher_hide_subject').prop('checked'));
                        break;
                    case 'helpcatcher_result_msg':
                        value = $.trim(tinyValue);
                        break;
                    default:
                        value = $.trim($('#stgh_' + paramList[i]).val());

                }

                result[i] = value;

            }
            return JSON.stringify(result);
        }

        function putWidgetCode(isTiny) {
            var paramsString = getAllParamValues();
            var newWidgetCodeString = stghHcLocal.widgetCodeDefault.replace('load()', 'load(' + paramsString + ')');
            $('#stgh_helpcatcher_embed_code').val(newWidgetCodeString);
        }


        $("#stgh_helpcatcher_button_color").wpColorPicker({
            change: function (event, ui) {
                putWidgetCode();
            },
        });

        $('#stgh_helpcatcher_enable_attachment').live('change', function () {
            putWidgetCode();
        });

        $('select[name = "stgh_helpcatcher_position"]').live('change', function () {
            putWidgetCode();
        });

        $('#stgh_helpcatcher_letter_start').live('keyup', function () {
            putWidgetCode();
        });

        $('#stgh_helpcatcher_result_msg').live('keyup', function () {
            tinyValue = $(this).val();
            putWidgetCode();
        });

        $('#stgh_helpcatcher_gdpr_enable').live('change', function () {
            putWidgetCode();
        });

        $('#stgh_helpcatcher_hide_name').live('change', function () {
            putWidgetCode();
        });

        $('#stgh_helpcatcher_hide_subject').live('change', function () {
            putWidgetCode();
        });

        $('#stgh_helpcatcher_gdpr_url').live('keyup', function () {
            putWidgetCode();
        });


        setTimeout(getEditor, 1000);

        $('.switch-tmce').live('click', function () {
            setTimeout(getEditor, 1000);
        });


        $('#stgh_helpcatcher_embed_code').attr('readonly', 'readonly');

    });

}(jQuery));

