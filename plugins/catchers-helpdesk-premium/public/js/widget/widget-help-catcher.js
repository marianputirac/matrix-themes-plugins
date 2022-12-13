

function getXmlHttp() {
    if (typeof XMLHttpRequest === 'undefined') {
        XMLHttpRequest = function () {
            try {
                return new ActiveXObject("Msxml2.XMLHTTP.6.0");
            }
            catch (e) {
            }
            try {
                return new ActiveXObject("Msxml2.XMLHTTP.3.0");
            }
            catch (e) {
            }
            try {
                return new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e) {
            }
            try {
                return new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
            }
            throw new Error("This browser does not support XMLHttpRequest.");
        };
    }
    return new XMLHttpRequest();
}

var WidgetHelpCatcher = {
    widget: document.getElementById('stgh-help-catcher-widget-block'),
    host: '{%host%}',
    ajaxurl: '{%ajaxurl%}',
    pluginName: '{%pluginName%}',
    created: false,
    iframe: null,
    bbg: null,
    form: null,
    success: null,
    error: null,
    schema: null,
    mobilever: null,
    rkey:'{%rkey%}',
    rcenable:'{%rcenable%}',

    validationField: {
        //'stg_ticket_name': {'required': '{%requiredMsg%}'},
        'stg_ticket_email': {'required': '{%requiredMsg%}', 'email': '{%emailMsg%}'},
        //'stg_ticket_subject': {'required': '{%requiredMsg%}'},
        'stg_ticket_message': {'required': '{%requiredMsg%}'},
        'stgh-help-catcher-gdpr-check': {'checkbox': '{%requiredMsg%}'}
    },
    validation: {
        'checkbox': function (v) {
            return v;
        },
        'required': function (v) {
            if (v == null || v == "") {
                return false;
            }
            return true;
        },
        'email': function (v) {
            var pattern = /^([a-z0-9_\.-])+@[a-z0-9-\.]+$/i;
            if (pattern.test(v)) {
                return true;
            } else {
                return false;
            }
        }
    },

    params: {
        style: {margin: '10px 0'},
        enable: false,
        enableUpload: false,
        gdprEnable: false,
        hideName: false,
        hideSubject: false,
        gdprUrl: '',
        buttonColor: '#f9c3a7',
        remoteParams: {},
        customField: {},
        valuesForm: {},
        headerText: '',
        resultMessage: '',
        width: 320,
        height: 450,
        align: 'right',
        fileaccept: 'image/jpeg,image/gif,image/png,image/bmp,image/tiff,image/x-icon,video/x-ms-asf,video/x-ms-wmv,video/x-ms-wmx,video/x-ms-wm,video/avi,video/divx,video/x-flv,video/quicktime,video/mpeg,video/mp4,video/ogg,video/webm,video/x-matroska,video/3gpp,video/3gpp2,text/plain,text/csv,text/tab-separated-values,text/calendar,text/richtext,text/css,text/html,text/vtt,application/ttaf+xml,audio/mpeg,audio/x-realaudio,audio/wav,audio/ogg,audio/midi,audio/x-ms-wma,audio/x-ms-wax,audio/x-matroska,application/rtf,application/javascript,application/pdf,application/x-shockwave-flash,application/java,application/x-tar,application/zip,application/x-gzip,application/rar,application/x-7z-compressed,application/x-msdownload,application/octet-stream,application/octet-stream,application/msword,application/vnd.ms-powerpoint,application/vnd.ms-write,application/vnd.ms-excel,application/vnd.ms-access,application/vnd.ms-project,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-word.document.macroEnabled.12,application/vnd.openxmlformats-officedocument.wordprocessingml.template,application/vnd.ms-word.template.macroEnabled.12,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel.sheet.macroEnabled.12,application/vnd.ms-excel.sheet.binary.macroEnabled.12,application/vnd.openxmlformats-officedocument.spreadsheetml.template,application/vnd.ms-excel.template.macroEnabled.12,application/vnd.ms-excel.addin.macroEnabled.12,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint.presentation.macroEnabled.12,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.ms-powerpoint.slideshow.macroEnabled.12,application/vnd.openxmlformats-officedocument.presentationml.template,application/vnd.ms-powerpoint.template.macroEnabled.12,application/vnd.ms-powerpoint.addin.macroEnabled.12,application/vnd.openxmlformats-officedocument.presentationml.slide,application/vnd.ms-powerpoint.slide.macroEnabled.12,application/onenote,application/oxps,application/vnd.ms-xpsdocument,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.presentation,application/vnd.oasis.opendocument.spreadsheet,application/vnd.oasis.opendocument.graphics,application/vnd.oasis.opendocument.chart,application/vnd.oasis.opendocument.database,application/vnd.oasis.opendocument.formula,application/wordperfect,application/vnd.apple.keynote,application/vnd.apple.numbers,application/vnd.apple.pages',
        validationField: {},
        validation: {}
    },

    load: function (params) {

        if (this.created) {
            return false;
        }

        this.loadParams(params);
        this.created = true;

        return true;
    },

    loadParams: function (params) {
        var xmlhttp = getXmlHttp(), me = this;
        xmlhttp.open('GET', this.ajaxurl + "?action=stgh_helpcatcher_params_action", true);
        xmlhttp.withCredentials = true;
        xmlhttp.responseType = 'json';
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {
                if (xmlhttp.status == 200 || xmlhttp.status == 304) {

                    if (xmlhttp.response) {

                        if (typeof xmlhttp.response == 'string') {
                            var parseParams = JSON.parse(xmlhttp.response);
                        }
                        else {
                            var parseParams = xmlhttp.response;
                        }
                        me.params.remoteParams = parseParams.params;
                        me.params.customField = parseParams.customField;
                        me.setParams(parseParams.params);

                        me.setParams(params);
                        me.createWidget();
                        return;
                    }

                    if (xmlhttp.responseText) {
                        var parseParams = JSON.parse(xmlhttp.responseText);
                        me.params.remoteParams = parseParams.params;
                        me.params.customField = parseParams.customField;
                        me.setParams(parseParams.params);

                        me.setParams(params);
                        me.createWidget();
                    }
                }
            }
        };
        xmlhttp.send(null);
    },

    createWidget: function () {
        if (!this.params.enable) {
            return false;
        }

        this.createWidgetButtons();
        this.createWidgetIframe();

        this.createWidgetForm();
        this.createWidgetSuccess();
        this.createWidgetError();
        this.createWidgetMobileCloser();

        this.appendStylesheets();

        if(this.rcenable == 1)
        {
            this.appendRecaptcha();
        }

        this.mergeUserConfig();

        this.addValidationListener();

        return true;
    },

    appendStylesheets: function () {
        var css = document.createElement('link');
        css.href = this.host + '/wp-content/plugins/'+this.pluginName+'/public/css/widget-help-catcher.css?ver=1.0.0';
        css.rel = "stylesheet";
        css.type = "text/css";
        this.iframe.contentWindow.document.head.appendChild(css);
    },

    appendRecaptcha: function () {
        var scr = document.createElement('script');
        scr.src = '//www.google.com/recaptcha/api.js?hl={%hl%}&badge=bottomleft';

        scr.defer = true;
        scr.async = true;


        scr.type = "text/javascript";


        var scr2 = document.createElement('script');
        scr2.src = this.host + '/wp-content/plugins/'+this.pluginName+'/public/js/widget/stgrecaptcha.js';;

        scr2.defer = true;
        scr2.async = true;


        scr2.type = "text/javascript";


        this.iframe.contentWindow.document.head.appendChild(scr);
        this.iframe.contentWindow.document.head.appendChild(scr2);


    },


    createWidgetButtons: function () {
        var bn = this.widget;
        bn.innerHTML = '<div id="stgh-hc-button-bg"><img id="stgh-hc-button" class="helpcatcher_button" src="' + this.host + '/wp-content/plugins/'+this.pluginName+'/public/images/hc-button-2.png" style="opacity: 1;width: inherit;border-radius: inherit;"></div>';

        var bbg = document.getElementById('stgh-hc-button-bg');
        this.bbg = bbg;

        bbg.style.position = 'fixed';

        if (this.params.align == 'left') {
            bbg.style.bottom = '40px';
            bbg.style.left = '20px';
        }

        if (this.params.align == 'right') {
            bbg.style.bottom = '40px';
            bbg.style.right = '20px';
        }

        bbg.style.margin = '0px';
        bbg.style.padding = '0px';
        bbg.style.width = '54px';
        bbg.style.height = '54px';
        bbg.style.backgroundColor = this.params.buttonColor;
        bbg.style.zIndex = 11;
        bbg.style.transition = '0.1s';
        bbg.style.backgroundSize = '15px 15px';
        bbg.style.cursor = 'pointer';
        bbg.style.borderRadius = '30px';
        bbg.style.boxShadow = 'rgba(0, 0, 0, 0.258824) 0px 2px 5px 0px';

        this.bbg.addEventListener('click', function () {
            WidgetHelpCatcher.toggle()
        });
    },

    createWidgetIframe: function () {
        this.iframe = document.createElement('iframe');
        this.widget.appendChild(this.iframe);

        var fr = this.iframe;
        fr.setAttribute('id', 'stgh-help-catcher-widget-iframe');
        if (this.mobilever) {
            fr.setAttribute('scrolling', 'yes');
        }
        else {
            fr.setAttribute('scrolling', 'no');
        }
        fr.setAttribute('frborder', 0);
        fr.style.display = 'none';
        fr.style.position = 'fixed';
        fr.style.border = 'none';
        fr.style.zIndex = 99999999;

        if (this.params.align == 'left') {
            fr.style.left = '20px';
            fr.style.bottom = '95px';
        }

        if (this.params.align == 'right') {
            fr.style.right = '20px';
            fr.style.bottom = '95px';
        }

        fr.style.margin = this.params.style.margin;
        fr.style.padding = '0px';

        fr.style.width = this.params.width;

        fr.style.backgroundColor = 'white';
        fr.style.boxShadow = 'rgba(0, 0, 0, 0.258824) 0px 1px 4px 0px';
    },


    createWidgetForm: function () {
        this.createForm();

        this.createFormHeader();

        this.createInputHidden(this.form, 'stg_saveTicket', '2');
        this.createInputText(this.form, 'stg_ticket_name', 'stg_ticket_name', '{%name%}', 'form-control', '{%nameValue%}');
        this.createInputText(this.form, 'stg_ticket_email', 'stg_ticket_email', '{%email%}', 'form-control', '{%emailValue%}');
        this.createInputText(this.form, 'stg_ticket_subject', 'stg_ticket_subject', '{%subject%}', 'form-control');

        this.createTextArea(this.form, 'stg_ticket_message', 'stg_ticket_message', '{%message%}', 'form-control');

        if(this.params.gdprEnable == "true" || this.params.gdprEnable == true)
        {
            this.createGDPRCheckbox(this.form);
        }


        if (this.params.enableUpload == "true" || this.params.enableUpload == true) {
            this.createAttachFileDiv(this.form, '{%attachfile%}');
        }

        this.createRCdiv(this.form);



        this.createFormSubmit(this.form, '{%submit%}');

        this.iframe.contentWindow.document.open();
        this.iframe.contentWindow.document.write();
        this.iframe.contentWindow.document.close();

        this.iframe.contentWindow.document.body.appendChild(this.form);
    },

    createWidgetSuccess: function () {
        var successBlock = document.createElement('div');
        successBlock.id = 'stgh-help-catcher-result-success';


        var successInner =
            '</div>' +
            '<div class="stgh-help-catcher-result-img">' +
            '<img src=' + this.host + '/wp-content/plugins/'+this.pluginName+'/public/images/hc-success.png id="stgh-hc-button-success">' +
            '</div>' +
            '<div id="stgh-help-catcher-result-text"></div>';

        successBlock.innerHTML = successInner;

        this.success = successBlock;
        this.iframe.contentWindow.document.body.appendChild(successBlock);
    },

    createWidgetError: function () {
        var errorBlock = document.createElement('div');
        errorBlock.id = 'stgh-help-catcher-result-error';

        var errorInner =
            '</div>' +
            '<div class="stgh-help-catcher-result-img">' +
            '<img src=' + this.host + '/wp-content/plugins/'+this.pluginName+'/public/images/hc-error.png id="stgh-hc-button-error">' +
            '</div>' +
            '<div id="stgh-help-catcher-result-text-error"></div>';

        errorBlock.innerHTML = errorInner;

        this.error = errorBlock;
        this.iframe.contentWindow.document.body.appendChild(errorBlock);
    },

    createWidgetMobileCloser: function () {

        var closemobile = document.createElement('div');
        closemobile.id = "stgh-help-catcher-mobile-close-block";
        closemobile.innerHTML =
            '<a href="#">X</a>' +
            '<br/>';

        closemobile.addEventListener('click', function () {
            WidgetHelpCatcher.hide();
        }, false);

        this.iframe.contentWindow.document.body.appendChild(closemobile);
    },


    setParams: function (params) {
        if ((typeof params === 'object')) {
            for (var i in params) {
                this.params[i] = params[i];
            }
        }
    },

    createForm: function () {
        var me = this;
        this.form = document.createElement('form');
        this.form.action = '';
        this.form.acceptCharset = 'UTF-8';
        this.form.enctype = 'multipart/form-data';
        this.form.method = 'post';
        this.form.id = 'stgh-help-catcher-form';

        if(this.rcenable != 1){
            this.form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (me.validationForm()) {
                    me.handlerSubmit();
                }
            });
        }

    },

    handlerSubmit: function () {
        this.iframe.contentWindow.document.getElementById('stgh-help-catchers-submit').setAttribute('disabled','disabled');
        var xmlhttp = getXmlHttp(), me = this;

        var loaderImg = document.createElement('img');
        loaderImg.setAttribute('src', this.host + '/wp-content/plugins/'+this.pluginName+'/public/images/ajax-loader.gif');
        loaderImg.setAttribute('border', '0');
        loaderImg.setAttribute('style', 'width:20px;height:20px;');
        loaderImg.setAttribute('id', 'stgh-help-catcher-button-spinner');

        var tmp = this.iframe.contentWindow.document.getElementById('stgh-help-catchers-submit').innerHTML;
        this.iframe.contentWindow.document.getElementById('stgh-help-catchers-submit').innerHTML = "";

        this.iframe.contentWindow.document.getElementById('stgh-help-catchers-submit').appendChild(loaderImg);

        xmlhttp.open('POST', this.ajaxurl + "?action=stgh_helpcatcher_action", true);
        xmlhttp.withCredentials = true;
        xmlhttp.responseType = 'json';
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {

                if (xmlhttp.status == 200 || xmlhttp.status == 304) {

                    var responseStatus = null;
                    var responseMsg = null;

                    if (!xmlhttp.response) {
                        if (xmlhttp.responseText) {
                            var parseParams = JSON.parse(xmlhttp.responseText);
                            responseStatus = parseParams.status;
                            responseMsg = parseParams.msg;
                        }
                    }
                    else {

                        if (typeof xmlhttp.response == 'string') {
                            var parseParams = JSON.parse(xmlhttp.response);
                        }
                        else {
                            var parseParams = xmlhttp.response;
                        }

                        responseStatus = parseParams.status;
                        responseMsg = parseParams.msg;
                    }

                    me.iframe.contentWindow.document.getElementById('stgh-help-catchers-submit').innerHTML = tmp;
                    me.form.style.display = 'none';


                    if (responseStatus) {
                        me.success.style.display = 'block';
                        if (me.params.resultMessage != '')
                            me.iframe.contentWindow.document.getElementById('stgh-help-catcher-result-text').innerHTML = me.params.resultMessage;
                        else
                            me.iframe.contentWindow.document.getElementById('stgh-help-catcher-result-text').innerHTML = responseMsg;

                        if (!me.mobilever)
                            me.iframe.setAttribute('height', me.success.clientHeight+20);

                    } else {
                        me.error.style.display = 'block';
                        me.iframe.contentWindow.document.getElementById('stgh-help-catcher-result-text-error').innerHTML = responseMsg;
                        if (!me.mobilever)
                            me.iframe.setAttribute('height', me.error.clientHeight+20);
                    }

                }
            }
        };
        xmlhttp.send(new FormData(this.form));
    },

    addValidationListener: function () {
        var rules = this.validationField,
            me = this;

        for (var field in rules) {
            var el = this.iframe.contentWindow.document.getElementById(field);

            // if (!el) {
            //     throw new Error('Form field id="' + field + '" not found');
            // }

            if(el) {
                el.addEventListener('change', function (e) {
                    me.validateField(e.target.id);
                });
            }
        }
    },

    validationForm: function () {
        var rules = this.validationField,
            isValid = true;

        for (var field in rules) {

            var result = this.validateField(field);

            if (!result) {
                isValid = false;
            }
        }

        return isValid;
    },

    validateField: function (field) {
        var el = this.iframe.contentWindow.document.getElementById(field),
            rules = this.validationField,
            isValid = true;

        if(!(this.params.gdprEnable == "true" || this.params.gdprEnable == true))
        {
            delete rules['stgh-help-catcher-gdpr-check'];
        }

        if (!el) {
            throw new Error('Form field id="' + field + '" not found');
        }

        for (var type in rules[field]) {

            var el_value = el.value;
            if(type == 'checkbox')
            {
                el_value = el.checked;
            }

            if (this.validate(type, el_value)) {
                this.removeErrorField(field);
            } else {
                this.setErrorField(field, rules[field][type]);
                isValid = false;
            }
        }

        return isValid;
    },

    validate: function (type, value) {
        var validate = this.validation;

        if (validate[type] == 'undefined') {
            throw new Error('Type validate "' + type + '" not found');
        }

        return this.validation[type](value);
    },

    setErrorField: function (id, msg) {
        var label = this.iframe.contentWindow.document.getElementById(id + '-error'),
            el = this.iframe.contentWindow.document.getElementById(id);

        label.innerHTML = msg;

        label.removeAttribute('style');
        el.setAttribute('style', 'border: 1px solid red;');
    },

    removeErrorField: function (id) {
        var label = this.iframe.contentWindow.document.getElementById(id + '-error'),
            el = this.iframe.contentWindow.document.getElementById(id);

        label.innerHTML = "";
        el.removeAttribute('style');
    },

    mergeUserConfig: function () {
        this.validationField = this.mergeObjects(this.validationField, this.params.validationField);
        this.validation = this.mergeObjects(this.validation, this.params.validation);
    },

    mergeObjects: function (obj1, obj2) {
        for (var p in obj2) {
            try {
                if (obj2[p].constructor == Object) {
                    obj1[p] = this.mergeObjects(obj1[p], obj2[p]);
                } else {
                    obj1[p] = obj2[p];
                }
            } catch (e) {
                obj1[p] = obj2[p];
            }
        }
        return obj1;
    },

    createFormHeader: function () {
        var element = document.createElement('div');
        element.setAttribute('id', 'stgh-help-catcher-form-label');

        element.innerHTML = this.params.headerText;
        this.form.appendChild(element);
    },

    createInputText: function (target, field_id, field_name, field_placeholder, field_class, defaultValue) {
        var field = document.createElement('input'),
            label = document.createElement('label');
        if (field_name in this.params.valuesForm) {
            field.value = this.params.valuesForm[field_name];
        }
        field.type = 'text';
        field.id = field_id;
        field.name = field_name;
        field.placeholder = field_placeholder;
        field.className = field_class;

        if(this.params.hideName == "true" || this.params.hideName == true)
        {
            if(field_id == 'stg_ticket_name')
            {
                field.setAttribute('style', 'display: none;');
            }
        }

        if(this.params.hideSubject == "true" || this.params.hideSubject == true)
        {
            if(field_id == 'stg_ticket_subject')
            {
                field.setAttribute('style', 'display: none;');
            }
        }


        if(typeof defaultValue != 'undefined')
        {
            field.value = defaultValue;
        }

        target.appendChild(field);

        label.id = field_id + '-error';
        label.className = 'stgh-help-catcher-valid-error';
        label.setAttribute('style', 'display: none;');
        target.appendChild(label);
    },

    createInputHidden: function (target, name, value) {
        var field = document.createElement('input');
        field.value = value;
        field.type = 'hidden';
        field.name = name;
        field.id = name;
        target.appendChild(field);
    },

    createTextArea: function (target, field_id, field_name, field_placeholder, field_class) {
        var field = document.createElement('textarea'),
            label = document.createElement('label'),
            div = document.createElement('div');

        if (field_name in this.params.valuesForm) {
            field.value = this.params.valuesForm[field_name];
        }
        field.cols = 55;
        field.rows = 10;
        field.id = field_id;
        field.name = field_name;
        field.placeholder = field_placeholder;
        field.className = field_class;
        div.appendChild(field);

        label.id = field_id + '-error';
        label.className = 'stgh-help-catcher-valid-error';
        label.setAttribute('style', 'display: none;');
        div.appendChild(label);

        target.appendChild(div);
    },

    createGDPRCheckbox: function (target) {
        var div = document.createElement('div');
        div.className = 'stgh-help-catcher-gdpr';
        div.id = 'stgh-help-catcher-gdpr';

        var label = document.createElement('label');
        label.innerHTML = this.params.gdprUrl;

        var input = document.createElement('input');
        input.type = 'checkbox';
        input.value = '';
        input.id = 'stgh-help-catcher-gdpr-check';
        input.name = 'stgh-gdpr-check';
        //input.required = 'required';

        div.appendChild(input);
        div.appendChild(label);

        target.appendChild(div);

        var er_label = document.createElement('label');
        er_label.id = 'stgh-help-catcher-gdpr-check-error';
        er_label.className = 'stgh-help-catcher-valid-error';
        er_label.setAttribute('style', 'display: none;');
        target.appendChild(er_label);
    },

    createAttachFileDiv: function (target, label) {
        var div = document.createElement('div');
        div.className = 'stgh-help-catcher-form-file-upload';

        var a = document.createElement('a');
        a.id = "stgh-help-catcher-attach-file";
        a.innerHTML = label;

        var input = document.createElement('input');
        input.type = 'file';
        input.value = '';
        input.id = 'stgh-help-catcher_files';
        input.name = 'stgh_attachment';
        input.accept = this.params.fileaccept;

        var a_remove = document.createElement('a');
        a_remove.id = 'stgh_remove_file';
        a_remove.innerHTML = 'X';

        a_remove.addEventListener('click', function () {
            var input = this.previousSibling;
            var a = input.previousSibling;

            input.value = "";
            a.innerHTML = label;
            this.style.display = 'none';
        }, false);


        input.addEventListener('change', function () {
            var name = this.value;
            var a = this.previousSibling;
            var a_remove = this.nextSibling;

            if (name == '') {
                this.value = "";
                a.innerHTML = label;
                a_remove.style.display = 'none';
                return;
            }

            if (this.value.length > 25) {
                name = this.value.substr(0, 25);
            }
            a.innerHTML = name;
            a_remove.style.display = 'block';

        }, false);

        div.appendChild(a);
        div.appendChild(input);
        div.appendChild(a_remove);

        target.appendChild(div);
    },

    createRCdiv: function(target){

        var rediv = document.createElement('div');
        rediv.id='inrecaptcha';
        this.mycaptcha = rediv;

        target.appendChild(rediv);

    },


    createFormSubmit: function (target, label) {
        var field = document.createElement('button');
        field.type = 'submit';
        field.name = 'stgh-help-catchers-submit';
        field.className = 'btn';
        field.value = label;
        field.id = 'stgh-help-catchers-submit';
        field.innerHTML = label;
        field.style.cursor = 'pointer';
        field.style.backgroundColor = this.params.buttonColor;

        target.appendChild(field);
    },

    positionateIframe: function () {

        var tW = document.documentElement.clientWidth <= this.params.width;
        var tH = document.documentElement.clientHeight <= this.params.height;

        if (tW || tH) {
            alert('positi1');
            this.iframe.style.top = '-10px';
            this.iframe.style.left = '0';
            this.iframe.style.zIndex = '1000000';

            this.iframe.setAttribute('width', document.documentElement.clientWidth);
            this.iframe.setAttribute('height', document.documentElement.clientHeight+70);

            if(tH) {
                this.iframe.style.position = 'absolute';
            }

            this.mobilever = true;

            this.iframe.contentWindow.document.getElementById('stgh-help-catcher-mobile-close-block').style.display = 'block';
        } else {
            this.mobilever = false;
            this.iframe.setAttribute('width', this.params.width);
        }
    },

    show: function () {
        this.iframe.contentWindow.document.getElementById('stgh-help-catchers-submit').removeAttribute('disabled');

        this.iframe.contentWindow.document.getElementById('stg_ticket_name').value = '{%nameValue%}';
        this.iframe.contentWindow.document.getElementById('stg_ticket_email').value = '{%emailValue%}';


        this.iframe.style.display = 'block';
        this.positionateIframe();
        var buttonImg = document.getElementById('stgh-hc-button');
        buttonImg.src = this.host + '/wp-content/plugins/'+this.pluginName+'/public/images/hc-button-close-2.png';

        if (this.params.align == 'left') {
            this.bbg.style.borderRadius = '30px 0% 30px 30px';
        }

        if (this.params.align == 'right') {
            this.bbg.style.borderRadius = '0 30px 30px 30px';
        }
    },

    hide: function () {
        this.form.reset();

        var errors = this.iframe.contentWindow.document.getElementsByClassName('stgh-help-catcher-valid-error');

        for (var i = 0; i < errors.length; i++) {
            errors[i].innerHTML='';
        }

        this.form.style.display = 'block';
        this.iframe.style.display = 'none';
        this.error.style.display = 'none';
        this.success.style.display = 'none';


        this.iframe.contentWindow.document.getElementById('stg_saveTicket').value = 2;

        if (this.params.enableUpload == "true" || this.params.enableUpload == true) {
            this.iframe.contentWindow.document.getElementById('stgh-help-catcher-attach-file').innerHTML = '{%attachfile%}';
            this.iframe.contentWindow.document.getElementById('stgh-help-catcher_files').value = '';
            this.iframe.contentWindow.document.getElementById('stgh_remove_file').style.display = 'none';
        }


        var buttonImg = document.getElementById('stgh-hc-button');
        buttonImg.src = this.host + '/wp-content/plugins/'+this.pluginName+'/public/images/hc-button-2.png';
        this.bbg.style.borderRadius = '30px';
    },

    toggle: function () {
        (this.iframe.style.display == 'block') ? this.hide() : this.show();
        var cheight = this.form.clientHeight+60;

        if(!this.mobilever)
            this.iframe.setAttribute('height', cheight);
    }

}
