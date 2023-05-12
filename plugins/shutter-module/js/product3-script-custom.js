//get the property code based on id of property eg: property with id 9 = property_fit
jQuery.noConflict();
(function ($) {
    $(function () {


        // ========== START - customize some properties by user =========
        var idCustomer = null;
        var idDealer = null;
        var selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}";

        idCustomer = jQuery('input[name="customer_id"]').val();
        idDealer = jQuery('input[name="dealer_id"]').val();

        // "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
        if (idCustomer == 274 || idDealer == 274) {
            selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"137\"]}"
        }
        console.log('idCustomer ', idCustomer);
        console.log('selectedPropertyValuesEcowood ', selectedPropertyValuesEcowood);
        // ========== END - customize some properties by user =========


        $(".show-next-panel").click(function () {
            $(this).closest(".panel").find(".panel-collapse").collapse("hide");
            $(this).closest(".panel").next().find(".panel-collapse").collapse("show");
            return false;
        });

        function getPropertyCodeById(id) {
            code = '';
            for (i = 0; i < property_fields.length; i++) {
                if (property_fields[i].id == id) {
                    code = property_fields[i].code;
                }
            }
            return code;
        }

        //get which fields depend on the specific field
        function getRelatedFields(field_id) {
            var fields = [];

            for (i = 0; i < property_values.length; i++) {
                if (property_values[i].all_property_values == 0) {
                    selected_property_values = JSON.parse(property_values[i].selected_property_values);
                    for (j = 0; j < selected_property_values.property_value_ids.length; j++) {
                        if (field_id == selected_property_values.property_field) {
                            fields.push(property_values[i].property_id);
                        }
                    }
                }
            }

            return uniqueItems(fields);
        }

        //get which fields depend on a product
        function getRelatedFieldsByProduct() {
            var fields = [];

            for (i = 0; i < property_values.length; i++) {
                if (property_values[i].all_products == 0) {
                    fields.push(property_values[i].property_id);
                }
            }

            return uniqueItems(fields);
        }

        //get the data for a field, based on another field's value
        function getRelatedFieldData(property_id, changed_property_id, value) {
            data = [];
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].property_id == property_id) {
                    if (property_values[i].all_property_values == 0) {
                        selected_property_values = JSON.parse(property_values[i].selected_property_values);
                        if (changed_property_id == selected_property_values.property_field) {
                            for (j = 0; j < selected_property_values.property_value_ids.length; j++) {
                                if (selected_property_values.property_value_ids[j] == value) {
                                    data.push(property_values[i]);
                                }
                            }
                        }
                    } else {
                        data.push(property_values[i]);
                    }
                }
            }
            return data;
        }

        //get the data for a field, based on another field's value
        function getRelatedFieldDataByProductId(property_id, value) {
            data = [];
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].property_id == property_id) {
                    if (property_values[i].all_products == 0) {
                        selected_products = JSON.parse(property_values[i].selected_products);
                        for (j = 0; j < selected_products.product_ids.length; j++) {
                            if (selected_products.product_ids[j] == value) {
                                data.push(property_values[i]);
                            }
                        }
                    } else {
                        data.push(property_values[i]);
                    }
                }

            }
            return data;
        }

        //get all field data
        function getAllFieldData(property_id) {
            data = [];
            var idCustomer = $('input[name="customer_id"]').val();
            if (idCustomer == 274 || idDealer == 274) {
                for (var i = 0; i < property_values.length; i++) {
                    if (property_values[i].property_id == property_id && property_values[i].value !== 'Ecowood') {
                        data.push(property_values[i]);
                    }
                }
            } else {
                for (var i = 0; i < property_values.length; i++) {
                    if (property_values[i].property_id == property_id) {
                        data.push(property_values[i]);
                    }
                }
            }
            return data;
        }

        //get field data whose value contains...
        function getFieldDataContains(property_id, contains, field_values) {
            data = [];
            field_values = typeof field_values !== 'undefined' ? field_values : property_values;
            console.log(field_values);
            for (var i = 0; i < field_values.length; i++) {
                if (field_values[i].property_id == property_id && field_values[i].value.indexOf(contains) > -1) {
                    data.push(field_values[i]);
                }

            }
            return data;
        }

        function loadItems(property_code, values) {
            $('#' + property_code).select2({
                data: {
                    results: values,
                    text: 'name'
                },
                formatSelection: format,
                formatResult: format,
                dropdownAutoWidth: true,
                escapeMarkup: function (m) {
                    return m;
                }
            });
        }

        //adds error to field or select box
        function addError(field_id, error) {
            console.log("Adding error " + error + " for field " + field_id);
            if ($("#" + field_id).prev().find('.select2-choice').length > 0) {
                $("#" + field_id).prev().addClass("error-field");
                $("#" + field_id).prev().css('display', 'block');
                if ($("#" + field_id).closest('.input-group-container').length > 0) {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).closest('.input-group-container'));
                } else {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).after());
                }
                //show the div with the error if hidden
                $("#" + field_id).closest(".panel").find(".panel-collapse").collapse("show");

            } else {
                $("#" + field_id).addClass("error-field");
                if ($("#" + field_id).closest('.input-group-container').length > 0) {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).closest('.input-group-container'));
                } else {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id));
                }
                //show the div with the error if hidden
                $("#" + field_id).closest(".panel").find(".panel-collapse").collapse("show");
            }
        }

        function getPropertyBladesize() {
            if ($("#property_bladesize").select2('data')) {
                // if flat louvre is selected do:
                if ($("#property_bladesize").select2('data').value == '81.2mm Flat Louver') {
                    // if warning flat louvre not exists show worning
                    if ($('.warning-louvre').length < 1) {
                        var html_flat_louvre = '<div class="warning-louvre">WARNING: please select only for painted colors!</div>';
                        $('div#s2id_property_bladesize').append(html_flat_louvre);
                    }
                } else {
                    // if flat louvre is not selected remove worning:
                    $(".warning-louvre").remove();
                }
                // if ($("#property_bladesize").select2('data').value == 'Flat Louver') {
                //     return parseFloat(53);
                // } else {
                return parseFloat($("#property_bladesize").select2('data').value);
                // }
            } else {
                return 0;
            }
        }

        function getPropertyMidrailheight() {
            var midrail = [];
            if ($("#property_midrailheight").val().length > 0) {
                midrail[0] = parseFloat($("#property_midrailheight").val());
            }

            return midrail;
        }

        function getPropertyMidrailheight2() {
            var midrail = [];
            if ($("#property_midrailheight2").val().length > 0) {
                midrail[0] = parseFloat($("#property_midrailheight2").val());
            }

            return midrail;
        }

        function getPropertyMidrailtotal() {
            var midrail = [];
            if ($("#property_midrailheight2").val().length > 0) {
                var mid1 = parseInt($("#property_midrailheight").val());
                var mid2 = parseInt($("#property_midrailheight2").val());
                midrail[0] = parseFloat(mid1 + mid2);
            }

            return midrail;
        }

        function getPropertyMidrailDivider() {
            var midrail = [];
            if ($("#property_midraildivider1").val().length > 0) {
                midrail[0] = parseFloat($("#property_midraildivider1").val());
            }

            return midrail;
        }

        function getPropertyMidrailDivider2() {
            var midrail = [];
            if ($("#property_midraildivider2").val().length > 0) {
                midrail[0] = parseFloat($("#property_midraildivider2").val());
            }

            return midrail;
        }

        function getPropertyMidrailCombiPanel() {
            // var midrail = [];
            // if ($("#property_solidpanelheight").val().length > 0) {
            //     midrail[0] = parseFloat($("#property_solidpanelheight").val());
            // }

            // return midrail;
        }

        function getPropertyControltype() {
            var controltype = '';
            if ($("#property_controltype").select2('data')) {
                controltype = $("#property_controltype").select2('data').value;
            }
            return controltype;
        }

        function getPropertyControlsplitheight() {
            var controlsplitheight = 0;
            if ($("#property_controlsplitheight").val() != '') {
                controlsplitheight = parseInt($("#property_controlsplitheight").val());
            }
            return controlsplitheight;
        }

        function getPropertyBuiltout() {
            var builtout = 0;
            if ($("#property_builtout").val() != '') {
                builtout = parseInt($("#property_builtout").val());
            }
            return builtout;
        }

        function getPropertyControlsplitheight2() {
            var controlsplitheight2 = 0;
            var totheight = getPropertyTotHeight();
            var midrailheight = getPropertyMidrailheight();
            var split_start = 0;

            if ($("#property_controlsplitheight2").val() != '') {
                controlsplitheight2 = parseInt($("#property_controlsplitheight2").val());
            }

            if (midrailheight > 0) split_start = midrailheight;
            if (totheight > 0) split_start = totheight;

            if (split_start > 0 && controlsplitheight2 > 0) {
                return [split_start, controlsplitheight2];
            } else {
                return null;
            }
        }

        function getPropertyTotHeight() {
            var tot_height = 0;
            if ($("#property_totheight").val() != '') {
                tot_height = parseInt($("#property_totheight").val());
            }
            return tot_height;
        }


        //When changing the attachment, check file size
        $('#attachment').on('change', function () {
            field = this;
            if (this.files && this.files[0]) {
                if (this.files[0].size > (8 * (1024 * 1024))) { //limit to 1MB
                    alert("The file is too big, choose a smaller one!");
                    $(this).val("");
                }
            }
        });

        /** Hide t-post frame after layout code T
         * if frame type have P in name (P4008w) show only T-post type with P in name
         * else hide
         */
        function hideTpostBtFrameType() {
            let titleFrameType = $('input[name="property_frametype"]:checked').attr('data-title');
            let result = titleFrameType.includes("P4");
            var cusid_ele = document.querySelectorAll('[name="property_tposttype"]');
            for (var i = 0; i < cusid_ele.length; ++i) {
                var item = cusid_ele[i];
                var itemAttr = $(item).attr('data-title');
                if (itemAttr.includes("P7") === true && titleFrameType.includes("P4") === true) {
                    $(item).parent().css("display", "block");
                } else if (itemAttr.includes("P7") === false && titleFrameType.includes("P4") === false) {
                    $(item).parent().css("display", "block");
                } else {
                    $(item).parent().css("display", "none");
                }
            }
        }

        $(".property-select").css('width', '100%');

        //$("#property_style").css('width', '18em');

        function format(item) {
            var row = item.value;
            if (item.image_file_name !== 'undefined' && item.image_file_name !== null) {
                row = "<span><img src='/uploads/property_values/images/" + item.id + "/thumb_" + item.image_file_name + "' height='44' width='44' /> " + row + "</span>";
            }

            return row;
        }

        var names = [{
            "id": 78,
            "name": "Aluminium Full Height",
            "description": "",
            "part_number": "aluminum-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-07T21:20:44.000+01:00",
            "updated_at": "2017-11-02T14:45:27.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 63,
            "name": "Basswood Bay",
            "description": "",
            "part_number": "basswood-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:06:43.000+00:00",
            "updated_at": "2015-11-08T10:06:43.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 64,
            "name": "Basswood Bay Tier-on-tier",
            "description": "",
            "part_number": "basswood-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:07:49.000+00:00",
            "updated_at": "2015-11-08T10:07:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 67,
            "name": "Basswood Cafe",
            "description": "",
            "part_number": "basswood-cafe",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:10:44.000+00:00",
            "updated_at": "2015-11-08T10:10:44.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 95,
            "name": "Basswood Café Bay",
            "description": "",
            "part_number": "basswood-cafe-bay",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T19:56:11.000+01:00",
            "updated_at": "2017-08-08T19:59:02.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 66,
            "name": "Basswood Fullheight",
            "description": "",
            "part_number": "basswood-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:09:53.000+00:00",
            "updated_at": "2015-11-08T10:09:53.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 92,
            "name": "Basswood Individual Shutter",
            "description": "",
            "part_number": "basswood-individualshutter",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-05-15T09:36:51.000+01:00",
            "updated_at": "2017-05-15T09:36:51.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 62,
            "name": "Basswood Shaped",
            "description": "",
            "part_number": "basswood-shaped",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:05:49.000+00:00",
            "updated_at": "2015-11-08T10:05:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 60,
            "name": "Basswood Special Shape",
            "description": "",
            "part_number": "basswood-specialshape",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:03:41.000+00:00",
            "updated_at": "2015-11-08T10:03:41.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 61,
            "name": "Basswood Tracked",
            "description": "",
            "part_number": "basswood-tracked",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:04:49.000+00:00",
            "updated_at": "2015-11-08T10:04:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 3,
            "name": "PAULOWNIA",
            "description": "PAULOWNIA",
            "part_number": "PAULOWNIA",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": "3.0",
            "price2": null,
            "price3": "0.0",
            "created_at": "2014-11-28T09:32:20.000+00:00",
            "updated_at": "2015-10-06T14:24:32.000+01:00",
            "image_file_name": "1A1212.jpg",
            "image_content_type": "image/jpeg",
            "image_file_size": 154522,
            "image_updated_at": "2015-03-27T13:41:14.000+00:00",
            "old_id": null,
            "minimum_quantity": "1.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 44,
            "name": "Paulownia Bay",
            "description": "",
            "part_number": "paulownia-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:32:58.000+01:00",
            "updated_at": "2015-10-26T11:44:20.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 51,
            "name": "Paulownia Bay Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-07T20:02:11.000+00:00",
            "updated_at": "2015-11-07T20:02:11.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 41,
            "name": "Paulownia Cafe",
            "description": "",
            "part_number": "paulownia-cafe",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:29:21.000+01:00",
            "updated_at": "2015-10-26T11:44:39.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 97,
            "name": "Paulownia Café Bay",
            "description": "",
            "part_number": "paulownia-cafe-bay",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T20:04:27.000+01:00",
            "updated_at": "2017-08-08T20:04:27.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 42,
            "name": "Paulownia Fullheight",
            "description": "",
            "part_number": "paulownia-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:30:32.000+01:00",
            "updated_at": "2015-11-15T12:31:35.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "1.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 90,
            "name": "Paulownia Individual Shutter",
            "description": "",
            "part_number": "paulownia-individualshutter",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-05-15T09:32:48.000+01:00",
            "updated_at": "2017-05-15T09:32:48.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 45,
            "name": "Paulownia Shaped",
            "description": "",
            "part_number": "paulownia-specialshape",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:35:17.000+01:00",
            "updated_at": "2018-01-18T12:13:05.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 106,
            "name": "Paulownia Shaped",
            "description": "",
            "part_number": "paulownia-shaped",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2018-01-19T10:57:49.000+00:00",
            "updated_at": "2018-01-19T10:57:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 94,
            "name": "Paulownia Solid Flat",
            "description": "",
            "part_number": "paulownia-solid-flat",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-07-13T16:55:16.000+01:00",
            "updated_at": "2017-07-13T16:55:16.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 102,
            "name": "Paulownia Solid Flat Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-solid-flat-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-09-11T01:28:52.000+01:00",
            "updated_at": "2017-09-11T01:28:52.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 93,
            "name": "Paulownia Solid Raised",
            "description": "",
            "part_number": "paulownia-solid-raised",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-07-13T16:55:09.000+01:00",
            "updated_at": "2017-07-13T16:55:09.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 98,
            "name": "Paulownia Solid Raised Café Style",
            "description": "",
            "part_number": "paulownia-solid-raised-cafe-style",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T20:12:55.000+01:00",
            "updated_at": "2017-08-08T20:12:55.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 103,
            "name": "Paulownia Solid Raised Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-solid-raised-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-09-11T01:31:32.000+01:00",
            "updated_at": "2017-09-11T01:31:32.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 43,
            "name": "Paulownia Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:31:30.000+01:00",
            "updated_at": "2015-10-26T11:44:27.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 46,
            "name": "Paulownia Tracked",
            "description": "",
            "part_number": "paulownia-tracked",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:36:08.000+01:00",
            "updated_at": "2015-10-26T11:43:58.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 84,
            "name": "Shutter \u0026 Blackout Blind Basswood Bay",
            "description": "",
            "part_number": "blackout-basswood-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:25:48.000+01:00",
            "updated_at": "2016-09-10T11:25:48.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 83,
            "name": "Shutter \u0026 Blackout Blind Basswood Bay Tier-on-Tier",
            "description": "",
            "part_number": "blackout-basswood-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:25:27.000+01:00",
            "updated_at": "2016-09-10T11:25:27.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 74,
            "name": "Shutter \u0026 Blackout Blind Basswood Fullheight",
            "description": "",
            "part_number": "blackout-basswood-fullheight",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T20:58:26.000+01:00",
            "updated_at": "2016-06-12T20:58:26.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 75,
            "name": "Shutter \u0026 Blackout Blind Basswood Tier-on-tier",
            "description": "",
            "part_number": "blackout-basswood-tot",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T21:01:34.000+01:00",
            "updated_at": "2016-09-10T11:08:43.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 82,
            "name": "Shutter \u0026 Blackout Blind Paulownia Bay",
            "description": "",
            "part_number": "blackout-paulownia-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:25:00.000+01:00",
            "updated_at": "2016-09-10T11:25:00.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 81,
            "name": "Shutter \u0026 Blackout Blind Paulownia Bay Tier-on-Tier",
            "description": "",
            "part_number": "blackout-paulownia-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:24:29.000+01:00",
            "updated_at": "2016-09-10T11:24:29.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 76,
            "name": "Shutter \u0026 Blackout Blind Paulownia Fullheight",
            "description": "",
            "part_number": "blackout-paulownia-fullheight",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T21:02:23.000+01:00",
            "updated_at": "2016-06-12T21:02:23.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 77,
            "name": "Shutter \u0026 Blackout Blind Paulownia Tier-on-Tier",
            "description": "",
            "part_number": "blackout-paulownia-tot",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T21:02:48.000+01:00",
            "updated_at": "2016-06-12T21:02:48.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 107,
            "name": "UPVC (UK Made)",
            "description": "",
            "part_number": "upvc-uk-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2018-02-03T01:56:58.000+00:00",
            "updated_at": "2018-02-06T10:26:09.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "1.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 55,
            "name": "UPVC Bay",
            "description": "",
            "part_number": "upvc-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:47:15.000+00:00",
            "updated_at": "2015-11-08T09:47:15.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 59,
            "name": "UPVC Cafe",
            "description": "",
            "part_number": "upvc-cafe",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:51:39.000+00:00",
            "updated_at": "2015-11-08T09:51:39.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 96,
            "name": "UPVC Café Bay",
            "description": "",
            "part_number": "upvc-cafe-bay",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T19:58:31.000+01:00",
            "updated_at": "2017-08-08T19:58:31.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 58,
            "name": "UPVC Fullheight",
            "description": "",
            "part_number": "upvc-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:50:03.000+00:00",
            "updated_at": "2015-11-08T09:50:03.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 91,
            "name": "UPVC Individual Shutter",
            "description": "",
            "part_number": "upvc-individualshutter",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-05-15T09:34:16.000+01:00",
            "updated_at": "2017-07-27T18:10:26.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 52,
            "name": "UPVC Special Shape",
            "description": "",
            "part_number": "upvc-specialshape",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-07T20:03:46.000+00:00",
            "updated_at": "2015-11-07T20:03:46.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 57,
            "name": "UPVC Tier-on-Tier",
            "description": "",
            "part_number": "upvc-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:49:11.000+00:00",
            "updated_at": "2015-11-08T09:49:11.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 53,
            "name": "UPVC Tracked",
            "description": "",
            "part_number": "upvc-tracked",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-07T20:04:45.000+00:00",
            "updated_at": "2015-11-07T20:04:45.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 4,
            "name": "UPVC Waterproof",
            "description": "UPVC Waterproof",
            "part_number": "UPVC Waterproof",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": "5.37",
            "price2": null,
            "price3": "0.0",
            "created_at": "2014-11-28T09:32:40.000+00:00",
            "updated_at": "2015-03-27T13:41:41.000+00:00",
            "image_file_name": "1A1616.jpg",
            "image_content_type": "image/jpeg",
            "image_file_size": 163368,
            "image_updated_at": "2015-03-27T13:41:38.000+00:00",
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }];
        var property_values = [{
            "id": 165,
            "property_id": 8,
            "value": "114.3mm",
            "created_at": "2016-01-18T17:15:44.000+00:00",
            "updated_at": "2016-05-18T17:35:18.000+01:00",
            "code": "",
            "uplift": "0.0",
            "color": "",
            "all_products": true,
            "selected_products": "{\"product_ids\":null}",
            "all_property_values": false,
            "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\",\"139\",\"137\",\"187\"]}",
            "graphic": "none",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "is_active": true,
            "property": {
                "id": 8,
                "name": "Louvre Size",
                "created_at": "2015-09-07T20:04:50.000+01:00",
                "updated_at": "2015-09-07T20:04:50.000+01:00",
                "code": "bladesize",
                "sort": null,
                "help_text": "",
                "input_type": "select"
            }
        },
            {
                "id": 55,
                "property_id": 8,
                "value": "88.9mm",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 52,
                "property_id": 8,
                "value": "81.2mm Flat Louver",
                "created_at": "2015-09-07T20:05:51.000+01:00",
                "updated_at": "2018-03-02T00:29:12.000+00:00",
                "code": "Flat Louver",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 54,
                "property_id": 8,
                "value": "76.2mm",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-03-02T00:29:29.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\",\"292\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 53,
                "property_id": 8,
                "value": "63.5mm",
                "created_at": "2015-09-07T20:05:51.000+01:00",
                "updated_at": "2018-03-02T00:29:12.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\",\"292\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 164,
                "property_id": 8,
                "value": "50.8mm",
                "created_at": "2016-01-18T17:15:16.000+00:00",
                "updated_at": "2016-02-22T09:52:32.000+00:00",
                "code": "50.8mm",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 56,
                "property_id": 9,
                "value": "Inside",
                "created_at": "2015-09-07T20:14:42.000+01:00",
                "updated_at": "2017-09-11T01:11:46.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"221\",\"229\",\"227\",\"226\",\"222\",\"228\",\"230\",\"231\",\"232\",\"233\",\"31\",\"35\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 9,
                    "name": "Fit Position",
                    "created_at": "2015-09-07T20:13:07.000+01:00",
                    "updated_at": "2015-11-08T19:32:00.000+00:00",
                    "code": "fit",
                    "sort": null,
                    "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
                    "input_type": "select"
                }
            }, {
                "id": 57,
                "property_id": 9,
                "value": "Outside",
                "created_at": "2015-09-07T20:15:08.000+01:00",
                "updated_at": "2017-09-11T01:11:36.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"221\",\"229\",\"227\",\"226\",\"222\",\"228\",\"230\",\"231\",\"232\",\"233\",\"31\",\"35\",\"36\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 9,
                    "name": "Fit Position",
                    "created_at": "2015-09-07T20:13:07.000+01:00",
                    "updated_at": "2015-11-08T19:32:00.000+00:00",
                    "code": "fit",
                    "sort": null,
                    "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
                    "input_type": "select"
                }
            }, {
                "id": 58,
                "property_id": 9,
                "value": "Surface",
                "created_at": "2015-09-07T20:16:20.000+01:00",
                "updated_at": "2015-09-07T20:17:28.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"33\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 9,
                    "name": "Fit Position",
                    "created_at": "2015-09-07T20:13:07.000+01:00",
                    "updated_at": "2015-11-08T19:32:00.000+00:00",
                    "code": "fit",
                    "sort": null,
                    "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
                    "input_type": "select"
                }
            }, {
                "id": 88,
                "property_id": 14,
                "value": "Lightblock",
                "created_at": "2015-09-07T21:25:06.000+01:00",
                "updated_at": "2016-03-03T15:22:31.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 151,
                "property_id": 14,
                "value": "M Track",
                "created_at": "2015-12-07T17:30:04.000+00:00",
                "updated_at": "2016-03-03T14:27:47.000+00:00",
                "code": "M",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 86,
                "property_id": 14,
                "value": "No",
                "created_at": "2016-01-06T09:03:53.000+00:00",
                "updated_at": "2017-04-04T08:42:52.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"305\",\"320\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 89,
                "property_id": 14,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:28:17.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 87,
                "property_id": 14,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-12-15T10:10:05.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 136,
                "property_id": 14,
                "value": "Track in Board",
                "created_at": "2015-10-08T09:34:24.000+01:00",
                "updated_at": "2016-03-03T14:25:59.000+00:00",
                "code": "B",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 85,
                "property_id": 14,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:43:21.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 73,
                "property_id": 11,
                "value": "Lightblock",
                "created_at": "2015-09-07T21:25:06.000+01:00",
                "updated_at": "2016-03-03T15:22:17.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 71,
                "property_id": 11,
                "value": "No",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-04-04T08:42:16.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 74,
                "property_id": 11,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:27:54.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 72,
                "property_id": 11,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-10-23T00:29:56.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"67\",\"66\",\"64\",\"140\",\"65\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 70,
                "property_id": 11,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:42:11.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"173\",\"141\",\"60\",\"301\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 78,
                "property_id": 12,
                "value": "Lightblock",
                "created_at": "2015-09-07T21:25:06.000+01:00",
                "updated_at": "2016-03-03T15:22:21.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 76,
                "property_id": 12,
                "value": "No",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-04-04T08:43:09.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 79,
                "property_id": 12,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:28:00.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 77,
                "property_id": 12,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-10-23T00:32:49.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"67\",\"66\",\"64\",\"140\",\"65\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 75,
                "property_id": 12,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:43:14.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"173\",\"141\",\"60\",\"150\",\"63\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 83,
                "property_id": 13,
                "value": "Lightblock",
                "created_at": "2015-09-07T21:25:06.000+01:00",
                "updated_at": "2016-03-03T15:22:26.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 81,
                "property_id": 13,
                "value": "No",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-04-04T08:43:01.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 84,
                "property_id": 13,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:28:04.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 82,
                "property_id": 13,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-10-23T00:35:26.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"67\",\"66\",\"64\",\"140\",\"65\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 135,
                "property_id": 13,
                "value": "Top Track",
                "created_at": "2015-10-08T09:33:59.000+01:00",
                "updated_at": "2016-03-03T15:28:10.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 80,
                "property_id": 13,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:42:56.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"300\",\"305\",\"320\",\"173\",\"141\",\"60\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 171,
                "property_id": 10,
                "value": "P4028X",
                "created_at": "2016-06-12T20:39:47.000+01:00",
                "updated_at": "2016-06-12T21:45:19.000+01:00",
                "code": "BL90",
                "uplift": "0.0",
                "color": "",
                "all_products": false,
                "selected_products": "{\"product_ids\":[\"74\",\"75\",\"76\",\"77\"]}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":null}",
                "graphic": "image",
                "image_file_name": "P4028X.png",
                "image_content_type": "image/png",
                "image_file_size": 33700,
                "image_updated_at": "2016-06-12T20:39:46.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 90,
                "property_id": 15,
                "value": "White",
                "created_at": "2015-09-07T23:00:49.000+01:00",
                "updated_at": "2016-04-01T23:26:53.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 440,
                "property_id": 15,
                "value": "Black",
                "created_at": "2015-09-07T23:00:49.000+01:00",
                "updated_at": "2016-04-01T23:26:53.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 167,
                "property_id": 15,
                "value": "Pearl",
                "created_at": "2016-01-25T10:55:34.000+00:00",
                "updated_at": "2016-04-01T23:26:45.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 174,
                "property_id": 15,
                "value": "Bisque",
                "created_at": "2016-01-25T10:55:34.000+00:00",
                "updated_at": "2016-04-01T23:26:45.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 92,
                "property_id": 15,
                "value": "Antique Brass",
                "created_at": "2015-09-07T23:01:43.000+01:00",
                "updated_at": "2016-04-22T09:10:21.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 91,
                "property_id": 15,
                "value": "Brass",
                "created_at": "2015-09-07T23:01:18.000+01:00",
                "updated_at": "2016-04-01T23:26:30.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 186,
                "property_id": 15,
                "value": "Hidden",
                "created_at": "2016-08-04T12:37:29.000+01:00",
                "updated_at": "2016-08-04T12:37:29.000+01:00",
                "code": "hidden",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"292\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 168,
                "property_id": 15,
                "value": "Brushed Nickel",
                "created_at": "2016-02-25T09:16:36.000+00:00",
                "updated_at": "2016-04-01T23:26:38.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"138\",\"139\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 93,
                "property_id": 15,
                "value": "Stainless Steel (+10%)",
                "created_at": "2015-09-07T23:02:36.000+01:00",
                "updated_at": "2016-01-06T06:50:37.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            // {
            //     "id": 187,
            //     "property_id": 18,
            //     "value": "Earth",
            //     "created_at": "2016-09-05T19:55:06.000+01:00",
            //     "updated_at": "2016-09-05T19:55:06.000+01:00",
            //     "code": "aluminum",
            //     "uplift": "0.0",
            //     "color": "",
            //     "all_products": true,
            //     "selected_products": "{\"product_ids\":null}",
            //     "all_property_values": true,
            //     "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
            //     "graphic": "none",
            //     "image_file_name": null,
            //     "image_content_type": null,
            //     "image_file_size": null,
            //     "image_updated_at": null,
            //     "is_active": true,
            //     "property": {
            //         "id": 18,
            //         "name": "Material",
            //         "created_at": "2015-10-19T20:30:37.000+01:00",
            //         "updated_at": "2015-10-19T21:47:55.000+01:00",
            //         "code": "material",
            //         "sort": 0,
            //         "help_text": "",
            //         "input_type": "select"
            //     }
            // },
            {
                "id": 188,
                "property_id": 18,
                "value": "Ecowood",
                "created_at": "2016-09-05T19:55:06.000+01:00",
                "updated_at": "2016-09-05T19:55:06.000+01:00",
                "code": "ecowood",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true
            },
            {
                "id": 139,
                "property_id": 18,
                "value": "Supreme",
                "created_at": "2015-10-19T20:32:01.000+01:00",
                "updated_at": "2015-10-19T20:32:01.000+01:00",
                "code": "basswood",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 138,
                "property_id": 18,
                "value": "Biowood",
                "created_at": "2015-10-19T20:31:50.000+01:00",
                "updated_at": "2015-11-08T19:36:15.000+00:00",
                "code": "paulownia",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 137,
                "property_id": 18,
                "value": "Green",
                "created_at": "2015-10-19T20:31:42.000+01:00",
                "updated_at": "2015-10-19T20:31:42.000+01:00",
                "code": "pvc",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            },
//            {
//                "id": 172,
//                "property_id": 20,
//                "value": "-",
//                "created_at": "2016-06-16T13:59:23.000+01:00",
//                "updated_at": "2016-06-16T13:59:23.000+01:00",
//                "code": "-",
//                "uplift": "0.0",
//                "color": "",
//                "all_products": true,
//                "selected_products": "{\"product_ids\":null}",
//                "all_property_values": false,
//                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\"]}",
//                "graphic": "none",
//                "image_file_name": null,
//                "image_content_type": null,
//                "image_file_size": null,
//                "image_updated_at": null,
//                "is_active": true,
//                "property": {
//                    "id": 20,
//                    "name": "Midrail Position Critical",
//                    "created_at": "2016-06-12T20:34:21.000+01:00",
//                    "updated_at": "2016-06-12T20:34:21.000+01:00",
//                    "code": "midrailpositioncritical",
//                    "sort": null,
//                    "help_text": "",
//                    "input_type": "input"
//                }
//            },
            {
                "id": 170,
                "property_id": 20,
                "value": "No",
                "created_at": "2016-06-12T20:35:48.000+01:00",
                "updated_at": "2017-11-27T12:05:58.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 20,
                    "name": "Midrail Position Critical",
                    "created_at": "2016-06-12T20:34:21.000+01:00",
                    "updated_at": "2016-06-12T20:34:21.000+01:00",
                    "code": "midrailpositioncritical",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 169,
                "property_id": 20,
                "value": "Yes",
                "created_at": "2016-06-12T20:35:36.000+01:00",
                "updated_at": "2017-11-27T12:05:53.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 20,
                    "name": "Midrail Position Critical",
                    "created_at": "2016-06-12T20:34:21.000+01:00",
                    "updated_at": "2016-06-12T20:34:21.000+01:00",
                    "code": "midrailpositioncritical",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 24,
                "property_id": 6,
                "value": "Bathroom Downstairs",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 25,
                "property_id": 6,
                "value": "Bathroom Upstairs",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 17,
                "property_id": 6,
                "value": "Bed 1",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 18,
                "property_id": 6,
                "value": "Bed 2",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 19,
                "property_id": 6,
                "value": "Bed 3",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 20,
                "property_id": 6,
                "value": "Bed 4",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 21,
                "property_id": 6,
                "value": "Bed 5",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 15,
                "property_id": 6,
                "value": "Dining Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 14,
                "property_id": 6,
                "value": "Family Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 27,
                "property_id": 6,
                "value": "Games Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 22,
                "property_id": 6,
                "value": "Guest Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 28,
                "property_id": 6,
                "value": "Hallway",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 13,
                "property_id": 6,
                "value": "Kitchen",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 12,
                "property_id": 6,
                "value": "Living Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 10,
                "property_id": 6,
                "value": "Lounge",
                "created_at": "2015-09-07T18:47:06.000+01:00",
                "updated_at": "2015-09-07T18:47:06.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 94,
                "property_id": 6,
                "value": "Other",
                "created_at": "2015-09-22T11:54:49.000+01:00",
                "updated_at": "2015-09-22T11:54:49.000+01:00",
                "code": "other",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 23,
                "property_id": 6,
                "value": "Spare Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 26,
                "property_id": 6,
                "value": "Study",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 11,
                "property_id": 6,
                "value": "TV Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 16,
                "property_id": 6,
                "value": "Utility Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 145,
                "property_id": 17,
                "value": "Other",
                "created_at": "2015-10-23T01:56:04.000+01:00",
                "updated_at": "2018-02-03T01:54:46.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            },
            {
                "id": 411,
                "property_id": 17,
                "value": "Frosted White",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": selectedPropertyValuesEcowood,
                //"selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }
            , {
                "id": 412,
                "property_id": 17,
                "value": "Neutral White",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": selectedPropertyValuesEcowood,
                //"selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }
            , {
                "id": 413,
                "property_id": 17,
                "value": "Shell White",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": selectedPropertyValuesEcowood,
                //"selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },

            {
                "id": 101,
                "property_id": 17,
                "value": "LS 601 PURE WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2018-02-08T10:49:01.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 103,
                "property_id": 17,
                "value": "LS 003 SILK WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 104,
                "property_id": 17,
                "value": "LS 630 MOST WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 105,
                "property_id": 17,
                "value": "LS 637 HOG BRISTLE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 106,
                "property_id": 17,
                "value": "LS 609 CHAMPAGNE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 107,
                "property_id": 17,
                "value": "LS 105 PEARL",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 108,
                "property_id": 17,
                "value": "LS 618 ALABASTER",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 109,
                "property_id": 17,
                "value": "LS 619 CREAMY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 110,
                "property_id": 17,
                "value": "LS 632 MISTRA",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 111,
                "property_id": 17,
                "value": "LS 910 JET BLACK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 112,
                "property_id": 17,
                "value": "LS 615 CLASSICAL WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 113,
                "property_id": 17,
                "value": "LS 617 New EGGSHELL",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 114,
                "property_id": 17,
                "value": "LS 620 LIME WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 115,
                "property_id": 17,
                "value": "LS 621 SAND",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 116,
                "property_id": 17,
                "value": "LS 622 STONE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 117,
                "property_id": 17,
                "value": "LS 032 SEA MIST",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 118,
                "property_id": 17,
                "value": "LS 049 STONE GREY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 119,
                "property_id": 17,
                "value": "LS 051 BROWN GREY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 120,
                "property_id": 17,
                "value": "LS 053 CLAY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 121,
                "property_id": 17,
                "value": "LS 072 MATTINGLEY 267",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 122,
                "property_id": 17,
                "value": "LS 108 RUSTIC GREY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 123,
                "property_id": 17,
                "value": "LS 109 WEATHERED TEAK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:35.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 124,
                "property_id": 17,
                "value": "LS 110 CHIQUE WHITE (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:41.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 125,
                "property_id": 17,
                "value": "LS 114 TAUPE (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:19.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 126,
                "property_id": 17,
                "value": "LS 202 GOLDEN OAK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:30.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 127,
                "property_id": 17,
                "value": "LS 204 OAK MANTEL (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:54.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 128,
                "property_id": 17,
                "value": "LS 205 GOLDENROD (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:59.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 129,
                "property_id": 17,
                "value": "LS 211 CHERRY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:06.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 130,
                "property_id": 17,
                "value": "LS 212 DARK TEAK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:13.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 131,
                "property_id": 17,
                "value": "LS 214 COCOA (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:20.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 132,
                "property_id": 17,
                "value": "LS 215 CORDOVAN (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:26.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 133,
                "property_id": 17,
                "value": "LS 219 MAHOGANY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:34.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 134,
                "property_id": 17,
                "value": "LS 220 NEW EBONY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:40.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 166,
                "property_id": 17,
                "value": "LS 221 BLACK WALNUT (+20%)",
                "created_at": "2016-01-20T11:49:59.000+00:00",
                "updated_at": "2016-04-01T23:31:47.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 220,
                "property_id": 17,
                "value": "LS 227 RED OAK (+20%)",
                "created_at": "2017-06-26T10:18:55.000+01:00",
                "updated_at": "2017-06-26T10:23:57.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 253,
                "property_id": 17,
                "value": "LS 229 RICH WALNUT (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:03.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 254,
                "property_id": 17,
                "value": "LS 230 OLD TEAK (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:23.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 255,
                "property_id": 17,
                "value": "LS 232 RED MAHOGANY (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:42.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 256,
                "property_id": 17,
                "value": "LS 237 WENGE (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:48.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 257,
                "property_id": 17,
                "value": "LS 862 FRENCH OAK (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:52.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 258,
                "property_id": 17,
                "value": "A100 (WHITE )",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:57.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 259,
                "property_id": 17,
                "value": "A103 (PERAL)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:02.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 260,
                "property_id": 17,
                "value": "A107( BLACK)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:06.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 261,
                "property_id": 17,
                "value": "A108 (SILVER)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 262,
                "property_id": 17,
                "value": "A202 (LIGHT CEDAR) (+10)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:14.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 263,
                "property_id": 17,
                "value": "A203 (GOLDEN OAK ) (+10%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:18.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 264,
                "property_id": 17,
                "value": "P601 WHITE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:22.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 265,
                "property_id": 17,
                "value": "P603 VANILLA BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:31.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 266,
                "property_id": 17,
                "value": "P630 WINTER WHITE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-02T00:06:34.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 267,
                "property_id": 17,
                "value": "P631 STONE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:39.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 268,
                "property_id": 17,
                "value": "P632 MISTRAL BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:43.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 269,
                "property_id": 17,
                "value": "P615 CLASSICAL WHITE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:49.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 270,
                "property_id": 17,
                "value": "P910 JET BLACK BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:53.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 271,
                "property_id": 17,
                "value": "P817 OLD TEAK BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 272,
                "property_id": 17,
                "value": "P819 COFFEE BEAN BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:14.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 273,
                "property_id": 17,
                "value": "PS-1 HONEY BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:18.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 293,
                "property_id": 17,
                "value": "UK01 White",
                "created_at": "2018-02-08T09:16:40.000+00:00",
                "updated_at": "2018-02-08T09:16:40.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"292\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, /*{
                "id": 163,
                "property_id": 19,
                "value": "38.1mm Astragal Beaded",
                "created_at": "2016-01-06T07:10:07.000+00:00",
                "updated_at": "2016-02-19T09:52:48.000+00:00",
                "code": "DBS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 162,
                "property_id": 19,
                "value": "38.1mm Astragal Flat",
                "created_at": "2016-01-06T07:09:53.000+00:00",
                "updated_at": "2016-02-19T09:52:59.000+00:00",
                "code": "DFS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 160,
                "property_id": 19,
                "value": "38.1mm Butt Beaded",
                "created_at": "2016-01-06T07:09:21.000+00:00",
                "updated_at": "2016-02-19T09:53:08.000+00:00",
                "code": "BS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 158,
                "property_id": 19,
                "value": "38.1mm Butt Flat",
                "created_at": "2016-01-06T07:08:53.000+00:00",
                "updated_at": "2016-02-19T09:53:17.000+00:00",
                "code": "FS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 161,
                "property_id": 19,
                "value": "38.1mm Rebated Beaded",
                "created_at": "2016-01-06T07:09:38.000+00:00",
                "updated_at": "2016-02-19T09:53:26.000+00:00",
                "code": "RBS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 159,
                "property_id": 19,
                "value": "38.1mm Rebated Flat",
                "created_at": "2016-01-06T07:09:06.000+00:00",
                "updated_at": "2016-02-19T09:53:34.000+00:00",
                "code": "RFS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 157,
                "property_id": 19,
                "value": "50.8mm Astragal Beaded",
                "created_at": "2016-01-06T07:07:11.000+00:00",
                "updated_at": "2018-02-03T01:36:44.000+00:00",
                "code": "DBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\","188"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 156,
                "property_id": 19,
                "value": "50.8mm Astragal Flat",
                "created_at": "2016-01-06T07:06:54.000+00:00",
                "updated_at": "2016-02-19T10:48:59.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 154,
                "property_id": 19,
                "value": "50.8mm Butt Beaded",
                "created_at": "2016-01-06T07:06:18.000+00:00",
                "updated_at": "2018-02-03T01:37:00.000+00:00",
                "code": "BS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\","188"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 152,
                "property_id": 19,
                "value": "50.8mm Butt Flat",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 155,
                "property_id": 19,
                "value": "50.8mm Rebated Beaded",
                "created_at": "2016-01-06T07:06:37.000+00:00",
                "updated_at": "2016-01-21T18:14:24.000+00:00",
                "code": "RBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 153,
                "property_id": 19,
                "value": "50.8mm Rebated Flat",
                "created_at": "2016-01-06T07:05:56.000+00:00",
                "updated_at": "2016-02-19T10:48:51.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },*/ {
                "id": 32,
                "property_id": 7,
                "value": "Bay Window",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-11-27T13:50:12.000+00:00",
                "code": "bay",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Bay_Window.png",
                "image_content_type": "image/png",
                "image_file_size": 76251,
                "image_updated_at": "2015-10-04T21:08:52.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 218,
                "property_id": 7,
                "value": "Bay Window Individual Shutter",
                "created_at": "2017-05-15T09:04:03.000+01:00",
                "updated_at": "2017-05-19T13:10:09.000+01:00",
                "code": "individualshutter",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Individual_Frames.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 15197,
                "image_updated_at": "2017-05-19T13:09:24.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 146,
                "property_id": 7,
                "value": "Bay Window Tier-on-Tier",
                "created_at": "2015-11-04T14:03:03.000+00:00",
                "updated_at": "2017-11-27T13:50:25.000+00:00",
                "code": "bay-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "thumb_Bay_Window.png",
                "image_content_type": "image/png",
                "image_file_size": 13179,
                "image_updated_at": "2015-11-04T14:03:26.000+00:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 225,
                "property_id": 7,
                "value": "Café Style Bay Window",
                "created_at": "2017-08-08T20:08:10.000+01:00",
                "updated_at": "2017-11-27T13:50:38.000+00:00",
                "code": "cafe-bay",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Bay_Window_Cafe_Style_copy.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 103406,
                "image_updated_at": "2017-08-09T12:20:56.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 29,
                "property_id": 7,
                "value": "Full Height",
                "created_at": "2015-09-07T19:20:15.000+01:00",
                "updated_at": "2015-10-19T21:58:32.000+01:00",
                "code": "fullheight",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "image",
                "image_file_name": "Full_Height.png",
                "image_content_type": "image/png",
                "image_file_size": 43607,
                "image_updated_at": "2015-10-04T21:09:35.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 227,
                "property_id": 7,
                "value": "Solid Flat\u003cbr/\u003eTier-on-Tier",
                "created_at": "2017-09-11T01:05:00.000+01:00",
                "updated_at": "2017-09-11T01:05:00.000+01:00",
                "code": "solid-flat-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Solid_Panel_Tier_on_Tier_Flat_bcf18cd5d2.png",
                "image_content_type": "image/png",
                "image_file_size": 200757,
                "image_updated_at": "2017-09-11T01:04:58.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 226,
                "property_id": 7,
                "value": "Solid Raised Café Style",
                "created_at": "2017-08-08T20:14:06.000+01:00",
                "updated_at": "2017-08-08T20:14:06.000+01:00",
                "code": "solid-raised-cafe-style",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Solid-Panel-Raised-Cafe-Style-1.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 123246,
                "image_updated_at": "2017-08-08T20:14:05.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 228,
                "property_id": 7,
                "value": "Solid Raised\u003cbr/\u003eTier-on-Tier",
                "created_at": "2017-09-11T01:05:46.000+01:00",
                "updated_at": "2017-09-11T01:05:46.000+01:00",
                "code": "solid-raised-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Solid_Panel_Tier_on_Tier_Raised_6ced5183ab.png",
                "image_content_type": "image/png",
                "image_file_size": 239469,
                "image_updated_at": "2017-09-11T01:05:44.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 229,
                "property_id": 7,
                "value": "Solid Combi Panel",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-combi",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 230,
                "property_id": 7,
                "value": "Solid Panel Bay Window Full Height",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-flat",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 231,
                "property_id": 7,
                "value": "Solid Panel Bay Window Tier-on-Tier",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-flat-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 232,
                "property_id": 7,
                "value": "Solid Panel Bay Window Cafe Style",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-raised-cafe-style",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 233,
                "property_id": 7,
                "value": "Solid Combi Panel Bay Window",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-combi",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 31,
                "property_id": 7,
                "value": "Tier-on-Tier",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-11-27T13:53:54.000+00:00",
                "code": "tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Tier-on-Tier.png",
                "image_content_type": "image/png",
                "image_file_size": 55654,
                "image_updated_at": "2015-10-04T21:10:24.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 300,
                "property_id": 10,
                "value": "A4001A",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "image",
                "image_file_name": "L50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 301,
                "property_id": 10,
                "value": "A4028",
                "created_at": "2015-10-22T09:26:58.000+01:00",
                "updated_at": "2016-06-12T21:40:12.000+01:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "image",
                "image_file_name": "Z50.png",
                "image_content_type": "image/png",
                "image_file_size": 5522,
                "image_updated_at": "2015-10-22T09:33:12.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }
            , {
                "id": 302,
                "property_id": 10,
                "value": "A4027",
                "created_at": "2015-09-07T20:35:24.000+01:00",
                "updated_at": "2018-02-03T01:34:19.000+00:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "image",
                "image_file_name": "A4027.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3732,
                "image_updated_at": "2015-10-21T19:07:32.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }
            , {
                "id": 305,
                "property_id": 10,
                "value": "4001A",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "image",
                "image_file_name": "L50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 350,
                "property_id": 19,
                "value": "50.8mm A1002B (Std.beaded stile)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 355,
                "property_id": 19,
                "value": "51mm 1001B( plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:04:20.000+00:00",
                    "updated_at": "2016-01-06T07:16:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 356,
                "property_id": 19,
                "value": "51mm 1005B(plain D-mould",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 357,
                "property_id": 19,
                "value": "51mm 1002B(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 358,
                "property_id": 19,
                "value": "51mm 1006B(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 359,
                "property_id": 19,
                "value": "51mm 1004B(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 360,
                "property_id": 19,
                "value": "51mm 1003B(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 361,
                "property_id": 19,
                "value": "35mm 1001A(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 362,
                "property_id": 19,
                "value": "35mm 1005A(plain D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 363,
                "property_id": 19,
                "value": "35mm 1002A(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 364,
                "property_id": 19,
                "value": "35mm 1006A(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 365,
                "property_id": 19,
                "value": "35mm 1004A(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 366,
                "property_id": 19,
                "value": "35mm 1003A(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 370,
                "property_id": 19,
                "value": "51mm T1001K(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 371,
                "property_id": 19,
                "value": "51mm T1005K(plain D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 372,
                "property_id": 19,
                "value": "51mm T1002K(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 373,
                "property_id": 19,
                "value": "51mm T1006K(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 374,
                "property_id": 19,
                "value": "51mm T1004K(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 375,
                "property_id": 19,
                "value": "51mm T1003K(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 376,
                "property_id": 19,
                "value": "41mm T1001M(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 377,
                "property_id": 19,
                "value": "41mm T1005M(plain D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 378,
                "property_id": 19,
                "value": "41mm T1003M(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 380,
                "property_id": 19,
                "value": "51mm PVC-P1001B(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 381,
                "property_id": 19,
                "value": "51mm PVC-P1005B(plain D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 382,
                "property_id": 19,
                "value": "51mm PVC-P1002B(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 383,
                "property_id": 19,
                "value": "51mm PVC-P1006B(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 384,
                "property_id": 19,
                "value": "51mm PVC-P1004E(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 385,
                "property_id": 19,
                "value": "51mm PVC-P1003E(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 400,
                "property_id": 16,
                "value": "Centre Rod",
                "created_at": "2015-09-26T01:29:12.000+01:00",
                "updated_at": "2017-11-27T12:03:46.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\",\"138\",\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 402,
                "property_id": 16,
                "value": "Offset Rod",
                "created_at": "2015-09-26T01:29:43.000+01:00",
                "updated_at": "2017-11-27T12:04:18.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 401,
                "property_id": 16,
                "value": "Hidden tilt",
                "created_at": "2017-07-21T02:51:01.000+01:00",
                "updated_at": "2017-11-27T12:04:38.000+00:00",
                "code": "",
                "uplift": "15.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"188\",\"138\",\"139\",\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 403,
                "property_id": 16,
                "value": "Concealed rod",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 390,
                "property_id": 22,
                "value": "Frame Only",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "Blackout Blind Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "blackoutblindcolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 405,
                "property_id": 22,
                "value": "101 - Snow White",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "Blackout Blind Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "blackoutblindcolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }
            , {
                "id": 406,
                "property_id": 22,
                "value": "K077 - Creamy",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "Blackout Blind Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "blackoutblindcolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }
            , {
                "id": 407,
                "property_id": 22,
                "value": "616 - Champagne",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "Blackout Blind Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "blackoutblindcolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }
            , {
                "id": 408,
                "property_id": 22,
                "value": "231 - Rusty Grey",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "Blackout Blind Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "blackoutblindcolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 409,
                "property_id": 22,
                "value": "841 - Cherry",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "Blackout Blind Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "blackoutblindcolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 410,
                "property_id": 22,
                "value": "310 - Roasted Coffee",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "Blackout Blind Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "blackoutblindcolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 437,
                "property_id": 23,
                "value": "P7032 - Standard T-Post",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 23,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 438,
                "property_id": 23,
                "value": "7001 - Supreme std. T-Post",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 23,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 439,
                "property_id": 23,
                "value": "7201 - T-Post with insert",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 23,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 441,
                "property_id": 23,
                "value": "7011 - Large T-Post",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 23,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }

        ];
        var property_fields = [{
            "id": 6,
            "name": " Room",
            "created_at": "2015-09-07T18:46:17.000+01:00",
            "updated_at": "2015-09-22T11:55:40.000+01:00",
            "code": "room",
            "sort": 0,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 7,
            "name": "Style",
            "created_at": "2015-09-07T19:18:34.000+01:00",
            "updated_at": "2015-09-07T19:18:34.000+01:00",
            "code": "style",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 8,
            "name": "Louvre Size",
            "created_at": "2015-09-07T20:04:50.000+01:00",
            "updated_at": "2015-09-07T20:04:50.000+01:00",
            "code": "bladesize",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 9,
            "name": "Fit Position",
            "created_at": "2015-09-07T20:13:07.000+01:00",
            "updated_at": "2015-11-08T19:32:00.000+00:00",
            "code": "fit",
            "sort": null,
            "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
            "input_type": "select"
        }, {
            "id": 10,
            "name": "Frame Type",
            "created_at": "2015-09-07T20:26:47.000+01:00",
            "updated_at": "2015-09-07T20:26:47.000+01:00",
            "code": "frametype",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 11,
            "name": "Frame Left",
            "created_at": "2015-09-07T21:03:52.000+01:00",
            "updated_at": "2015-09-07T21:03:52.000+01:00",
            "code": "frameleft",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 12,
            "name": "Frame Right",
            "created_at": "2015-09-07T21:27:18.000+01:00",
            "updated_at": "2015-09-07T21:27:18.000+01:00",
            "code": "frameright",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 13,
            "name": "Frame Top",
            "created_at": "2015-09-07T21:27:46.000+01:00",
            "updated_at": "2015-09-07T21:27:46.000+01:00",
            "code": "frametop",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 14,
            "name": "Frame Bottom",
            "created_at": "2015-09-07T21:28:16.000+01:00",
            "updated_at": "2015-09-07T21:28:16.000+01:00",
            "code": "framebottom",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 15,
            "name": "Hinge Color",
            "created_at": "2015-09-07T23:00:03.000+01:00",
            "updated_at": "2015-09-07T23:01:58.000+01:00",
            "code": "hingecolour",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 16,
            "name": "Control Type",
            "created_at": "2015-09-26T01:25:55.000+01:00",
            "updated_at": "2015-09-26T01:25:55.000+01:00",
            "code": "controltype",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 17,
            "name": "Shutter Colour",
            "created_at": "2015-10-01T18:09:08.000+01:00",
            "updated_at": "2015-10-01T18:09:08.000+01:00",
            "code": "shuttercolour",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }, {
            "id": 18,
            "name": "Material",
            "created_at": "2015-10-19T20:30:37.000+01:00",
            "updated_at": "2015-10-19T21:47:55.000+01:00",
            "code": "material",
            "sort": 0,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 19,
            "name": "Stile",
            "created_at": "2016-01-06T07:03:20.000+00:00",
            "updated_at": "2016-01-06T07:15:59.000+00:00",
            "code": "stile",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 20,
            "name": "Midrail Position Critical",
            "created_at": "2016-06-12T20:34:21.000+01:00",
            "updated_at": "2016-06-12T20:34:21.000+01:00",
            "code": "midrailpositioncritical",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }, {
            "id": 21,
            "name": "Locks",
            "created_at": "2016-09-05T23:48:25.000+01:00",
            "updated_at": "2016-09-05T23:49:53.000+01:00",
            "code": "locks",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }, {
            "id": 22,
            "name": "Blackout Blind Colour",
            "created_at": "2016-09-05T23:48:25.000+01:00",
            "updated_at": "2016-09-05T23:49:53.000+01:00",
            "code": "blackoutblindcolour",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }, {
            "id": 23,
            "name": "T Post Type",
            "created_at": "2016-09-05T23:48:25.000+01:00",
            "updated_at": "2016-09-05T23:49:53.000+01:00",
            "code": "tposttype",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }];

        //console.log('CUSTOM PROPERTY:  '+JSON.stringify(property_values_original));
        var layout_columns = {
            t: 0,
            c: 0,
            b: 0
        };
        var configuration = {};
        var shutter_type = "Blackout";

        style_check = getStyleTitle();
        //property fit hidden
        if (style_check.indexOf('Bay') > -1) {
            $(".property_fit").show();
        } else {
            $(".property_fit").hide();
        }

        showShapeUploadFileAccordingToStyle();
        initFilterByProduct();

        //updateLayoutFields($("#property_layoutcode").val());
        checkStyleTier();
        checkShutterType();

        if ($('input[name=property_frametype]').length == 1) {
            $('input[name=property_frametype]').first().closest('label').trigger('click');
        }
        $(".property-select").each(function () {
            var id = $(this).attr('id');
            var property_id = getPropertyIdByCode(id);
            values = getAllFieldData(property_id);
            loadItems(id, values);
            console.log("Loaded values for element with id: " + id + " and property_id: " + property_id);
        });

        calculateTotal();
        filterControlType();

        $('select').select2({
            dropdownAutoWidth: true
        });

        $("#property_width, #property_height").change(function () {
            calculateTotal();
        });

        $('input[name="property_frametype"], .property-select').click(function () {
            calculateTotal();
        });

        $("#add-buildout button").click(function () {
            $("#add-buildout").hide();
            $("#buildout").fadeIn();
            $("#buildout input").addClass("number");
            return false;
        });

        $("#remove-buildout").click(function () {
            $("#buildout").hide();
            $("#buildout input").val('');
            $("#buildout input").removeClass("number");
            $("#add-buildout").fadeIn();
            return false;
        });

        $("#property_room").change(function () {
            if ($(this).val() == '94') {
                $("#room-other").fadeIn();
                $("#room-other input").addClass('required');
            } else {
                $("#room-other").fadeOut();
                $("#room-other input").removeClass('required');
            }
        });

        $("#property_shuttercolour").change(function () {
            if ($(this).val() == '145') {
                $("#colour-other").fadeIn();
                $("#colour-other input").addClass('required');
            } else {
                $("#colour-other").fadeOut();
                $("#colour-other input").val('');
                $("#colour-other input").removeClass('required');
            }

            if ($("#property_shuttercolour").select2('data')) {
                var property_shcolour_check = $("#property_shuttercolour").select2('data').value;
                console.log('step 1');
                var property_stile_check = $("input[name=property_stile]:checked").attr('data-title');
                console.log('step 2');
                if (typeof property_stile_check !== "undefined") {
                    if (property_shcolour_check.includes("brushed") || property_shcolour_check.includes("BRUSHED")) {
                        if (!property_stile_check.includes("51mm")) {
                            showErrorModal("Shutter Colour", "Brushed Shutter Colour need to have Frame Stile with 51mm. ");
                        }
                    }
                }
            }
        });

        $("#property_material").change(function () {

            if ($("#property_material").select2('data')) {
                product_title_check = $("#property_material").select2('data').value;
                if (product_title_check.indexOf('PVC') > -1) {
                    property_id = getPropertyIdByCode('property_hingecolour');
                    for (i = 0; i < property_values.length; i++) {
                        if (property_values[i].property_id == property_id) {
                            //set default value only if there is not
                            if (property_values[i].value.indexOf('Stainless') > -1 && !$("#property_hingecolour").select2('data')) {
                                $("#property_hingecolour").select2("val", property_values[i].id);
                            }
                        }
                    }
                    if (product_title_check.indexOf('UK') > -1) {
                        if ($('form').attr('edit') === 'no') {
                            $("#property_frameleft").select2("val", '70');
                            $("#property_frameright").select2("val", '75');
                            $("#property_frametop").select2("val", '80');
                            $("#property_framebottom").select2("val", '85');

                            $(".frames #property_frameleft").prop("readonly", true);
                            $(".frames #property_frameright").prop("readonly", true);
                            $(".frames #property_frametop").prop("readonly", true);
                            $(".frames #property_framebottom").prop("readonly", true);
                        }
                    }
                } else {
                    if ($('form').attr('edit') === 'no') {
                        $(".frames #property_frameleft").prop("readonly", false);
                        $(".frames #property_frameright").prop("readonly", false);
                        $(".frames #property_frametop").prop("readonly", false);
                        $(".frames #property_framebottom").prop("readonly", false);
                        $("#add-buildout").parent().show();
                    }
                }
                if (product_title_check.indexOf('Earth') > -1) { //teo_01-Earth Hidden Only
                    showAluminumOptions(true);
                } else {
                    showAluminumOptions(false);
                }
            }
            showMidrailPositionCritical();
        });

        $('.property-select').on('change', function () {
            id = $(this).attr('id');
            field_id = getPropertyIdByCode(id);
            related_fields = getRelatedFields(field_id);

            for (var i = 0; i < related_fields.length; i++) {
                field_data = getRelatedFieldData(related_fields[i], field_id, $(this).val());
                property_code = getPropertyCodeById(related_fields[i]);
                //   console.log("Loading to " + property_code + " data: " + field_data);


                if ($("#" + "property_" + property_code).data('select2')) {
                    loadItems("property_" + property_code, field_data);
                } else {
                    var field_check = "property_" + property_code;
                    $('input[name=' + field_check + ']').each(function () {
                        var found = false;
                        for (var i = 0; i < field_data.length; i++) {
                            if ($(this).val() == field_data[i].id)
                                found = true;
                        }
                        if (found) {
                            $(this).closest('label').fadeIn();
                        } else {
                            $(this).prop('checked', false);
                            $(this).closest('label').hide();
                        }
                    });
                }

            }
            console.log("Length: " + $("#choose-frametype label").filter(":visible").length);
            if ($("#choose-frametype label").filter(":visible").length == 0) {
                $("#required-choices-frametype").show();
            } else {
                //($("#choose-frametype label").filter(":visible").length);
                $("#required-choices-frametype").hide();
            }

            //after filtering if style is checked (selected) we need to apply some filters again
            if ($(this).attr('id') == 'property_material' && $('input[name=property_style]:checked').length > 0) {
                $('input[name=property_style]:checked').trigger('click', false);
            }

            if ($("#property_material").select2('data')) {
                product_title_check = $("#property_material").select2('data').value;
                $("#locks").show();
                $("#property_locks").val('No');
                if (product_title_check.indexOf('Earth') > -1) {  //teo_02-Earth Hidden Only
                    showAluminumOptions(true);
                    $("#property_sparelouvres").val('No');
                    $("#spare-louvres").hide();
                    $("#spare-louvres").closest("div.row").hide();
                    // $("#locks").show();
                    // $("#property_locks").val('No');
                    //select material
                    // var id_material = $("#property_material").select2('data').id;
                    // if(id_material == 187){
                    //     $("#property_sparelouvres").val('No');
                    //     $("#spare-louvres").hide();
                    //     $("#spare-louvres").closest("div.row").hide();
                    // }
                } else {
                    // $("#locks").hide();
                    showAluminumOptions(false);
                }
            }

            if ($("#canvas_container1").filter(":visible").length > 0) {
                updateShutter();
            }
        });

        $(document).on("change", "input", function () {
            try {
                filterControlType();
                if ($("#canvas_container1").filter(":visible").length > 0) {
                    updateShutter();
                }
            } catch (err) {
                //console.log('Shutter data not ready yet' + err);
            }
        });

        $("#choose-style label").click(function (event, trigger_change) {
            if (typeof trigger_change === 'undefined') {
                trigger_change = true;
            }

            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
                if (style_check.indexOf('Bay Window') > -1) {
//teo                        $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeIn();

                } else {
//teo                        $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeOut();

                }
                //set default value only if new record or empty value
                if (style_check.indexOf('Bay ') > -1) {
                    $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeIn();
                    //$("#property_fit").parent().parent().parent().show();
                } else {
                    $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeOut();
                }


                //if style is 'Shaped & French Cut Out', allow to upload a file for the shape
                if (style_check.indexOf('Shaped') > -1 || style_check.indexOf('French') > -1) {
                    console.log('123');
                    $("#shape-section").fadeIn();
                    $("#shape-section").addClass("required");
                }
                // else {
                //     console.log('456');
                //     $("#shape-section").fadeOut();
                //     $("#shape-section").removeClass("required");
                // }

                checkStyleTier();

                value = $('input[name=property_style]:checked').val();
                field_id = getPropertyIdByCode('property_style');
                style_related_fields = getRelatedFields(field_id);
                console.log(style_related_fields);
                for (var i = 0; i < style_related_fields.length; i++) {
                    field_data = getRelatedFieldData(style_related_fields[i], field_id, value);
                    property_code = getPropertyCodeById(style_related_fields[i]);
                    console.log("Loading to " + property_code + " data: " + field_data);
                    if ($("#" + "property_" + property_code).data('select2')) {
                        loadItems("property_" + property_code, field_data);
                    } else {
                        //filter only when track is selected
                        if (value == 35) {
                            var field_check = "property_" + property_code;
                            console.log("filtering " + field_check + ' from style');
                            $('input[name=' + field_check + ']').each(function () {
                                var found = false;
                                for (var i = 0; i < field_data.length; i++) {
                                    if ($(this).val() == field_data[i].id)
                                        found = true;
                                }
                                if (found) {
                                    $(this).closest('label').fadeIn();
                                } else {
                                    $(this).closest('label').fadeOut();
                                    $(this).prop('checked', false);
                                }
                            });
                        } else {
                            if (trigger_change)
                                $("#property_material").trigger('change');
                        }
                    }
                }

                //if style is 'Shaped & French Cut Out', allow to upload a file for the shape
                if (style_check.indexOf('Shaped') > -1) {
                    $("#choose-frametype input[value=141]").prop("checked", false).closest("label").hide();
                } else {
                    if ($("#property_material").val() == '138' || $("#property_material").val() == '139' || $("#property_material").val() == '188') {
                        $("#choose-frametype input[value=141]").closest("label").show();
                    }
                    // Selectare default tposttype
                    // if ($("#property_material").val() == '138' || $("#property_material").val() == '137') {
                    //     $("#property_tposttype").select2("val", '437');
                    // }
                    // if ($("#property_material").val() == '139' ) {
                    //     $("#property_tposttype").select2("val", '438');
                    // }
                }

                if (style_check.indexOf('Café') > -1) {
                    $("#property_frametop").select2("val", '81');
                    $("#property_tposttype").select2("val", '439');
                } else {
                    $("#property_frametop").select2("val", '80');
                }

                //default values for tracked style
                if ($("#order_product_id").val() == '') {
                    //default values for tracked style
                    if (style_check.indexOf('Tracked') > -1) {
                        //$("#property_frametype").select2("val", '68');
                        //$("#property_frametype").trigger('change'); //needed so that filtering will work correctly

                        if ($('form').attr('edit') === 'no') {
                            $("#property_frameleft").select2("val", '73');
                            $("#property_frameright").select2("val", '78');
                            $("#property_frametop").select2("val", '135');
                            $("#property_framebottom").select2("val", '136');
                        }
                    }
                }

                if (style_check.indexOf('Tracked') > -1) {
                    $('input[name="property_stile"][type="radio"]').parent().hide();
                    $('input[name="property_stile"][value="374"]').parent().show();
                    $('input[name="property_stile"][value="375"]').parent().show();
                    $('input[name="property_stile"][value="360"]').parent().show();
                    $('input[name="property_stile"][value="359"]').parent().show();
                    $('input[name="property_stile"][value="366"]').parent().show();
                    $('input[name="property_stile"][value="365"]').parent().show();
                    $('input[name="property_stile"][value="385"]').parent().show();
                    $('input[name="property_stile"][value="384"]').parent().show();
                    $('input[name="property_stile"][value="350"]').parent().show();
                }


                setProductByMaterialAndStyle();
            }
        });

        $("#property_material").click(function () {
            //alert( $(this).val() );
            // Selectare default tposttype
            if ($("#property_material").val() == '138' || $("#property_material").val() == '137' || $("#property_material").val() == '188') {
                $("#property_tposttype").select2("val", '437');
            }
            if ($("#property_material").val() == '139') {
                $("#property_tposttype").select2("val", '438');
            }
            // select frame checked
            $('input[name="property_frametype"]:checked').trigger('click');
        });

        $("#choose-frametype label").click(function () {
            let value;
            if ($('input[name="property_frametype"]:checked').length > 0 || $('input[name="property_frametype"][checked]').length > 0) {

                value = $('input[name=property_frametype]:checked').val();
                field_id = getPropertyIdByCode('property_frametype');
                related_fields = getRelatedFields(field_id);
                console.log(related_fields);
                for (var i = 0; i < related_fields.length; i++) {
                    field_data = getRelatedFieldData(related_fields[i], field_id, value);
                    property_code = getPropertyCodeById(related_fields[i]);
                    console.log("Loading to " + property_code + " data: " + field_data);
                    if ($("#" + "property_" + property_code).data('select2')) {
                        loadItems("property_" + property_code, field_data);
                    } else {
                        //filter only when track is selected
                        var field_check = "property_" + property_code;
                        console.log("filtering " + field_check + ' from frametype');
                        $('input[name=' + field_check + ']').each(function () {
                            var found = false;
                            for (var i = 0; i < field_data.length; i++) {
                                if ($(this).val() == field_data[i].id)
                                    found = true;
                            }
                            if (found) {
                                $(this).closest('label').fadeIn();
                            } else {
                                $(this).closest('label').fadeOut();
                                $(this).prop('checked', false);
                            }
                        });
                    }
                }

                //set default values for frame left-right-top-bottom
                if (value == 144) { //bottom m-track
                    if ($('form').attr('edit') === 'no') {
                        $("#property_frameleft").select2("val", '70');
                        $("#property_frameright").select2("val", '75');
                        $("#property_frametop").select2("val", '80');
                        $("#property_framebottom").select2("val", '151');
                    }
                }

                if (value == 143) { //track in board
                    if ($('form').attr('edit') === 'no') {
                        $("#property_frameleft").select2("val", '70');
                        $("#property_frameright").select2("val", '75');
                        $("#property_frametop").select2("val", '80');
                        $("#property_framebottom").select2("val", '136');
                    }
                }

                if (value === 144 || value === 143) {
                    //$("#frame-left, #frame-right, #frame-top, #frame-bottom").hide();
                } else {
                    console.log('frame_top 10');
                    //$("#frame-left, #frame-right, #frame-top, #frame-bottom").show();
                    // console.log('selectare property_frametype 3');

                    // freme top problem on add new shutter

                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_framebottom").select2("val", '85');
                    if (style_check.indexOf('Café') > -1) {
                        $("#property_frametop").select2("val", '81');
                    } else {
                        $("#property_frametop").select2("val", '80');
                    }
                }
            }
        });

        $("#property_controltype").change(function () {
            if ($("#property_controltype").select2('data')) {
                controltype_check = $("#property_controltype").select2('data').value;
                if (controltype_check.indexOf('Split') > -1) {
                    $("#control-split-height").addClass("required");
                    $("#control-split-height").addClass("number");
                    $("#control-split-height").fadeIn();
                } else {
                    $("#control-split-height").fadeOut();
                    $("#control-split-height").removeClass("required");
                    $("#control-split-height").removeClass("number");
                    $("#control-split-height input").val('');
                }
            }
            showHideControlSplit2();
        });

        $("#property_frametype").change(function () {
            if (!$("#property_frametop").select2("data")) {
                style_check = getStyleTitle();
                //default values for cafe style
                if (style_check.indexOf('Café') > -1) {
                    $("#property_frametop").select2("val", '81');
                }
            }
        });

        $("#property_midrailheight").change(function () {
            showMidrailPositionCritical();
            showHideControlSplit2();
        });

        $(" #property_midrailheight2").change(function () {
            showMidrailPositionCritical();
            showHideControlSplit2();
        });

        $("#property_material, #attachment").change(function () {
            setProductByMaterialAndStyle();
            filterStiles();
        });

        $('.property-select').each(function () {
            $(this).trigger('change');
        });

        $("#property_layoutcode").on('keyup', function () {
            var text = $(this).val().toUpperCase();
            updateLayoutFields(text);

            if (text.length > 1) {
                // console.log('keyup press ' + text);
                // console.log(text.length);
                if (text == 'L' || text == 'R') {
                    // console.log("text == 'L' || text == 'R'");
                    // console.log(text.length);
                    $('#property_opendoor').parent().hide();
                } else if ((text == 'LL' || text == 'RR') && text.length === 2) {
                    // console.log("text == 'LL' && text.length === 2");
                    // console.log(text.length);
                    $('#property_opendoor').parent().hide();
                } else if ((text == 'LLL' || text == 'RRR') && text.length === 3) {
                    // console.log("text == 'LLL' && text.length === 3");
                    // console.log(text.length);
                    $('#property_opendoor').parent().hide();
                } else {
                    $('#property_opendoor').parent().show();
                    $('#property_opendoor').val('Right');
                    $('#property_opendoor').trigger('change');
                }

                // $('#property_opendoor option[value="Right"]').attr("selected", "selected");
            } else {
                $('#property_opendoor').parent().hide();
            }
            /** Hide t-post frame after layout code T
             * if frame type have P in name (P4008w) show only T-post type with P in name
             * else hide
             */
            hideTpostBtFrameType();
        });

        var lengKeyR = 0;

        $("#property_layoutcode").on('keypress', function (event) {
            $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
            var charCode = event.which;
            if (!charCode) { // <-- charCode === 0
                return; // return false, optionally
            }
            var character = String.fromCharCode(charCode).toUpperCase();

            // $("#buildout-select").change(function () {
            //     // Check input( $( this ).val() ) for validity here
            //     if ($(this).val() === 'flexible') {
            //         console.log('flexible');
            //         $('input[name="property_b_buildout1"]').prop('checked', false);
            //         $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
            //     } else {
            //         $('.pull-left.extra-column-buildout.property_b_buildout1').show();
            //     }
            // });

            var text = $(this).val().toUpperCase();
            let letter = text.charAt(0);
            console.log('letter ' + letter);
            if (letter == 'R' && lengKeyR == 1 && "L" == character) { // <-- charCode === 0
                console.log('letter r and lengkeyr1');
                return false; // return false, optionally
            }
            if ($("#property_material").val() == '138' && letter == 'R' && (lengKeyR == 2 || lengKeyR == 3) && "L" == character) {
                console.log('letter r and lengkeyr1');
                return false; // return false, optionally
            }

            if (letter == 'R') {
                var str = text;
                var stringsearch = "R"
                for (var i = lengKeyR = 0; i < str.length; lengKeyR += +(stringsearch === str[i++])) ;
            }

            if ("L" == character || "R" == character || event.keyCode == 8) {
                return true;
            } else if ("T" == character || "B" == character || "C" == character) {
                var text = $("#property_layoutcode").val();
                //console.log('in G press '+text.length);
                if (text.charAt(text.length - 1).toUpperCase() == 'G') {
                    return false;
                } else {
                    return true;
                }
            } else if ("G" == character) {
                var text = $("#property_layoutcode").val();
                //console.log('in G press '+text.length);
                if (text.charAt(text.length - 1).toUpperCase() == 'L' || text.charAt(text.length - 1).toUpperCase() == 'R') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }


        });

        $("#property_material, #property_bladesize, #property_stile, #property_hingecolour").change(function () {
            filterControlType();
        });

        $("#property_hingecolour").change(function () {
            // if hinge color id is mot equal to 93 (Stainless Steel) and material is green then show alert-info about waterproof
            property_val = $('input[name="property_hingecolour"]').val();
            if (product_title_check.indexOf('Green') > -1) {
                // console.log(property_val);
                if (property_val !== '93') {
                    $('#step4-info .alert-info').show();
                } else {
                    $('#step4-info .alert-info').hide();
                }
            }
        });

        $("#buildout-select").change(function () {
            // Check input( $( this ).val() ) for validity here
            if ($(this).val() === 'flexible') {
                console.log('flexible');
                $('input[name="property_b_buildout1"]').prop('checked', false);
                $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
            } else {
                $('.pull-left.extra-column-buildout.property_b_buildout1').show();
            }
        });

        function initFilterByProduct() {
            value = $("#product_id").val();

            related_fields = getRelatedFieldsByProduct();
            console.log(related_fields);
            for (var i = 0; i < related_fields.length; i++) {
                field_data = getRelatedFieldDataByProductId(related_fields[i], value);
                property_code = getPropertyCodeById(related_fields[i]);
                console.log("Loading to " + property_code + " data: " + field_data);
                loadItems("property_" + property_code, field_data);
            }
        }

        function filterStiles() {
            stile_id = getPropertyIdByCode('property_stile');
            style_check = getStyleTitle();
            stile_default = 155; //butt rebated 50.8
            stile_data = [];
            //get the stile data based on property material
            property_material = $("#property_material").val();
            field_id = getPropertyIdByCode('property_material');
            related_fields = getRelatedFields(field_id);

            for (var i = 0; i < related_fields.length; i++) {
                property_code = getPropertyCodeById(related_fields[i]);
                if (property_code == 'stile') {
                    stile_data = getRelatedFieldData(related_fields[i], field_id, property_material);
                }
            }


            // if (parseFloat($("#property_height").val()) >= 1500 && style_check.indexOf('Tier') == -1) {
            //     new_stile_data = getFieldDataContains(stile_id, '50.8', stile_data);
            // } else {
            //     new_stile_data = stile_data;
            // }
            new_stile_data = stile_data;

            // loadItems('property_stile', new_stile_data);
            // if (!$("#property_stile").select2('data') || $("#property_stile").select2('data').id == 'undefined') {
            //     $("#property_stile").select2("val", stile_default);
            // }
        }

        function showShapeUploadFileAccordingToStyle() {
            //if 'Shaped & French Cut Out' style is chosen when configuration is loaded, show the upload file section
            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
                //console.log('sdfdsf ');
                //show the upload file section to allow uploading of a shape
                if (style_check.indexOf('Shaped') > -1 || style_check.indexOf('French') > -1) {
                    //console.log('clickkkk');
                    $("#shape-section").show();
                    $("#shape-section").addClass("required");
                }
            }
        }

        function setProductByMaterialAndStyle() {
            material_id = '';
            if ($("#property_material").select2('data')) {
                material_id = $("#property_material").select2('data').id;
            }

            material_code = '';
            style_code = '';
            product_id = '';
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].id == material_id)
                    material_code = property_values[i].code;
            }
            if ($('input[name=property_style]:checked').length > 0) {
                style_code = $('input[name=property_style]:checked').data('code');
            }
            existingShapeFile = $('#provided-shape').html().trim();
            if ($('input[name=attachment]').val() != '' || existingShapeFile.length > 0) {
                style_code = 'specialshape';
            }

            product_code = material_code + '-' + style_code;
            if (shutter_type == 'Blackout') {
                product_code = 'blackout-' + product_code;
            }

            console.log('Checking product code: ' + product_code);
            console.log('With products' + names);
            for (var i = 0; i < names.length; i++) {
                if (product_code == names[i].part_number) {
                    product_id = names[i].id;
                }
            }

            $("#product_id").val(product_id);
        }

        //todo: this might be removed, because allowed values are set by backend
        function showAluminumOptions(aluminum_selected) {
            if (aluminum_selected) {
                $(".locks").fadeIn();
                $(".locks input").removeClass('not-required');
                //        $("#add-buildout").parent().hide(); //teo - show buildout for Earth
            } else {
                $(".locks").fadeOut();
                $(".locks input").addClass('not-required');
                $(".locks input").val('No');
                $("#add-buildout").parent().show();
            }

            //buildout needs to be hidden for PVC UK made
            if ($("#property_material").select2('data')) {
                product_title_check = $("#property_material").select2('data').value;

                if (product_title_check.indexOf('UK') > -1 && product_title_check.indexOf('PVC') > -1) {
                    $("#add-buildout").parent().hide();
                }
            }
        }

        $('#property_b_buildout1').on('click', function () {
            if ($(this).prop('checked') == false) {
                $(this).attr("value", "no");
            } else {
                $(this).attr("value", "yes");
            }
        });
        $('#property_c_buildout1').on('click', function () {
            if ($(this).prop('checked') == false) {
                $(this).attr("value", "no");
            } else {
                $(this).attr("value", "yes");
            }
        });
        $('#property_t_buildout1').on('click', function () {
            if ($(this).prop('checked') == false) {
                $(this).attr("value", "no");
            } else {
                $(this).attr("value", "yes");
            }
        });

        //create new columns based on the layout code types
        function updateLayoutFields(text) {
            var text = $("#property_layoutcode").val();
            var property_material = $("#property_material").val();

            if (property_material == 188) {
                $('.note-ecowood-angle').show();
            } else {
                $('.note-ecowood-angle').hide();
            }
            //count T occurences

            // $("#property_layoutcode").on('keyup', function () {
            //     var text = $("#property_layoutcode").val();
            //     updateLayoutFields(text);
            // });

            //pull data from prototype html
            //console.log('add extra-column 1');
            var new_column_contents = $("#extra-column").html();
            var new_column = "<div class=\"pull-left extra-column\">" + new_column_contents + "</div>";

            layout_columns.t = 0;
            layout_columns.c = 0;
            layout_columns.b = 0;
            layout_columns.g = 0;
            bchar_nr = 1;
            tchar_nr = 1;
            gchar_nr = 1;
            cchar_nr = 1;

            $(".extra-column").remove();
            $(".extra-column-buildout").remove();
            $('.tpost-type').hide();
            //$('.tpost-type label').hide();

            var count_lr = 0;
            var count_t = 0;
            var count_g = 0;

            //clear extra fields
            for (var i = 0; i < text.length; i++) {
                //console.log('t-post press in for '+new_column_contents);
                if (text.charAt(i).toUpperCase() == 'L' || text.charAt(i).toUpperCase() == 'R') {
                    count_lr++;
                    //console.log('Panels left-right: '+count_lr);
                    $('#panels_left_right').val(count_lr);
                }

                if (text.charAt(i).toUpperCase() == 'T') {
                    //console.log('t-post press in if');
                    console.log('tchar_nr : ' + tchar_nr);
                    count_t++;
                    layout_columns.t++;
                    label = 'T-Post ' + layout_columns.t;
                    id = "property_t" + layout_columns.t;
                    addField(label, id, 1);

                    if (tchar_nr < 2) {
                        label = 'T-Post Buildout ' + tchar_nr;
                        label2 = 'T-Post Style ';
                        id2 = "property_t_buildout" + tchar_nr;
                        addFieldCheckboxBuildoutSelect(label2, id2, 'help text demo', 't');
                        addFieldCheckboxBuildout(label, id2, 1);
                        tchar_nr++;
                        // console.log('tchar_nr after : ' + bchar_nr);
                        $('.tpost-type').show();
                        $('.tpost-type label').show();
                    } else {
                        console.log('tchar_nr depasit : ' + tchar_nr);

                    }


                }

                if (text.charAt(i).toUpperCase() == 'C') {
                    //console.log('t-post press in if');
                    console.log('cchar_nr : ' + cchar_nr);
                    layout_columns.c++;
                    label = 'C-Post ' + layout_columns.c;
                    id = "property_c" + layout_columns.c;
                    addField(label, id, 1);

                    if (cchar_nr < 2) {
                        label = 'C-Post Buildout ' + cchar_nr;
                        id3 = "property_c_buildout" + cchar_nr;
                        addFieldCheckboxBuildout(label, id3, 1);
                        cchar_nr++;
                        console.log('cchar_nr after : ' + bchar_nr);
                    } else {
                        console.log('cchar_nr depasit : ' + cchar_nr);
                    }

                }


                if (text.charAt(i).toUpperCase() == 'B') {
                    console.log('bchar_nr : ' + bchar_nr);
                    layout_columns.b++;

                    label = 'Bay Post ' + layout_columns.b;
                    id2 = "property_bp" + layout_columns.b;
                    addField(label, id2, 1);

                    if (property_material == 188) {
                        labela = 'Bay Angle ' + layout_columns.b;
                        id1a = "property_ba" + layout_columns.b;
                        addFieldBuildAngleSelect(labela, id1a, 1);
                    } else {
                        label = 'Bay Angle ' + layout_columns.b;
                        id1 = "property_ba" + layout_columns.b;
                        addField(label, id1, 1);
                    }

                    if (bchar_nr < 2) {
                        label = 'B-Post Buildout ';
                        label2 = 'B-Post Type ';
                        id3 = "property_b_buildout" + bchar_nr;
                        addFieldCheckboxBuildoutSelect(label2, id3, 'help text demo', 'b');
                        // addFieldCheckboxBuildout(label, id3, 1);

                        bchar_nr++;
                        console.log('bchar_nr after : ' + bchar_nr);
                    } else {
                        console.log('bchar_nr depasit : ' + bchar_nr);
                    }

                    if (property_material == 188) {
                        $('select[name="bay-post-type"]').parent().parent().css('display', 'none');
                    } else {
                        $('select[name="bay-post-type"]').parent().parent().css('display', 'block');
                    }
                }


                if (text.charAt(i).toUpperCase() == 'G') {
                    //console.log('g-post press in if');
                    //console.log('gchar_nr : '+gchar_nr);
                    count_g++;
                    layout_columns.g++;
                    label = 'G-Post ' + layout_columns.g;
                    id = "property_g" + layout_columns.g;
                    addField(label, id, 1);

                    if (gchar_nr < 20) {
                        label = 'G-Post Buildout ' + gchar_nr;
                        id2 = "property_g_buildout" + gchar_nr;
                        addFieldCheckboxBuildout(label, id2, 1);
                        gchar_nr++;
                        console.log('G-char_nr after : ' + bchar_nr);
                        $('.gpost-type').show();
                    } else {
                        console.log('gchar_nr depasit : ' + gchar_nr);

                    }
                }


            }


            var bp1 = $('#bp1').val();
            var bp2 = $('#bp2').val();
            var bp3 = $('#bp3').val();
            var bp4 = $('#bp4').val();
            var bp5 = $('#bp5').val();
            var bp6 = $('#bp6').val();
            var bp7 = $('#bp7').val();
            var bp8 = $('#bp8').val();
            var bp9 = $('#bp9').val();
            var bp10 = $('#bp10').val();
            var bp11 = $('#bp11').val();
            var bp12 = $('#bp12').val();
            var bp13 = $('#bp13').val();
            var bp14 = $('#bp14').val();
            var bp15 = $('#bp15').val();

            var ba1 = $('#ba1').val();
            var ba2 = $('#ba2').val();
            var ba3 = $('#ba3').val();
            var ba4 = $('#ba4').val();
            var ba5 = $('#ba5').val();
            var ba6 = $('#ba6').val();
            var ba7 = $('#ba7').val();
            var ba8 = $('#ba8').val();
            var ba9 = $('#ba9').val();
            var ba10 = $('#ba10').val();
            var ba11 = $('#ba11').val();
            var ba12 = $('#ba12').val();
            var ba13 = $('#ba13').val();
            var ba14 = $('#ba14').val();
            var ba15 = $('#ba15').val();


            var t1 = $('#t1').val();
            var t2 = $('#t2').val();
            var t3 = $('#t3').val();
            var t4 = $('#t4').val();
            var t5 = $('#t5').val();
            var t6 = $('#t6').val();
            var t7 = $('#t7').val();
            var t8 = $('#t8').val();
            var t9 = $('#t9').val();
            var t10 = $('#t10').val();
            var t11 = $('#t11').val();
            var t12 = $('#t12').val();
            var t13 = $('#t13').val();
            var t14 = $('#t14').val();
            var t15 = $('#t15').val();

            var c1 = $('#c1').val();
            var c2 = $('#c2').val();
            var c3 = $('#c3').val();
            var c4 = $('#c4').val();
            var c5 = $('#c5').val();
            var c6 = $('#c6').val();
            var c7 = $('#c7').val();
            var c8 = $('#c8').val();
            var c9 = $('#c9').val();
            var c10 = $('#c10').val();
            var c11 = $('#c11').val();
            var c12 = $('#c12').val();
            var c13 = $('#c13').val();
            var c14 = $('#c14').val();
            var c15 = $('#c15').val();

            $('#property_bp1').val(bp1);
            $('#property_bp2').val(bp2);
            $('#property_bp3').val(bp3);
            $('#property_bp4').val(bp4);
            $('#property_bp5').val(bp5);
            $('#property_bp6').val(bp6);
            $('#property_bp7').val(bp7);
            $('#property_bp8').val(bp8);
            $('#property_bp9').val(bp9);
            $('#property_bp10').val(bp10);
            $('#property_bp11').val(bp11);
            $('#property_bp12').val(bp12);
            $('#property_bp13').val(bp13);
            $('#property_bp14').val(bp14);
            $('#property_bp15').val(bp15);


            $('#property_ba1').val(ba1);
            $('#property_ba2').val(ba2);
            $('#property_ba3').val(ba3);
            $('#property_ba4').val(ba4);
            $('#property_ba5').val(ba5);
            $('#property_ba6').val(ba6);
            $('#property_ba7').val(ba7);
            $('#property_ba8').val(ba8);
            $('#property_ba9').val(ba9);
            $('#property_ba10').val(ba10);
            $('#property_ba11').val(ba11);
            $('#property_ba12').val(ba12);
            $('#property_ba13').val(ba13);
            $('#property_ba14').val(ba14);
            $('#property_ba15').val(ba15);

            $('#property_t1').val(t1);
            $('#property_t2').val(t2);
            $('#property_t3').val(t3);
            $('#property_t4').val(t4);
            $('#property_t5').val(t5);
            $('#property_t6').val(t6);
            $('#property_t7').val(t7);
            $('#property_t8').val(t8);
            $('#property_t9').val(t9);
            $('#property_t10').val(t10);
            $('#property_t11').val(t11);
            $('#property_t12').val(t12);
            $('#property_t13').val(t13);
            $('#property_t14').val(t14);
            $('#property_t15').val(t15);

            $('#property_c1').val(c1);
            $('#property_c2').val(c2);
            $('#property_c3').val(c3);
            $('#property_c4').val(c4);
            $('#property_c5').val(c5);
            $('#property_c6').val(c6);
            $('#property_c7').val(c7);
            $('#property_c8').val(c8);
            $('#property_c9').val(c9);
            $('#property_c10').val(c10);
            $('#property_c11').val(c11);
            $('#property_c12').val(c12);
            $('#property_c13').val(c13);
            $('#property_c14').val(c14);
            $('#property_c15').val(c15);


            //restart tooltips because of new fields
            $('[data-toggle="tooltip"]').tooltip({
                'placement': 'top'
            });
        }

        //add new field based on layout code
        function addField(label, id, helptext) {

            //use data from prototype column
            //console.log('add extra-column 2');
            var new_column_contents = $("#extra-column").html();
            //console.log(new_column_contents);

            //create extra column element
            var new_column = "<div class=\"pull-left extra-column\">" + new_column_contents + "</div>";
            var element = $(new_column);
            $(element).find(".extra-column-label").html(label);
            $(element).find("input").attr("id", id);
            $(element).find("input").addClass('required');
            $(element).find("input").attr("name", id);

            if (configuration[id] !== 'undefined') {
                $(element).find("input").val(configuration[id]);
            }

            if ($(".extra-column").length > 0) {
                $(element).insertAfter($(".extra-column").last());
            } else {
                $(element).appendTo(".extra-columns-row div");
            }
        }

        //add new field based on layout code checkbox
        function addFieldCheckbox(label, id, helptext) {

            //use data from prototype column
            //console.log('add extra-column 2');
            var new_column_contents = $("#extra-column").html();
            //console.log(new_column_contents);

            //create extra column element
            var new_column = "<div class=\"pull-left extra-column\">" + new_column_contents + "</div>";
            var element = $(new_column);
            $(element).find(".extra-column-label").html(label);
            $(element).find("input").attr("type", "checkbox");
            $(element).find("input").attr("id", id);
            $(element).find("input").attr("name", id);
            $(element).find("input").attr("value", "yes");

            if (configuration[id] !== 'undefined') {
                $(element).find("input").val(configuration[id]);
            }

            if ($(".extra-column").length > 0) {
                $(element).insertAfter($(".extra-column").last());
            } else {
                $(element).appendTo(".extra-columns-row div");
            }
        }

        //add new field Buildout based on layout code checkbox
        function addFieldCheckboxBuildout(label, id, helptext) {

            //use data from prototype column
            //console.log('add extra-column 2');
            var new_column_contents = $("#extra-column").html();
            //console.log(new_column_contents);

            //create extra column element
            var new_column = "<div class=\"pull-left extra-column-buildout\">" + new_column_contents + "</div>";
            var element = $(new_column);
            $(element).find(".extra-column-label").html(label);
            $(element).find("input").attr("type", "checkbox");
            $(element).find("input").attr("id", id);
            $(element).find("input").attr("name", id);
            $(element).find("input").attr("value", "yes");

            $(element).find(".extra-column-label").append("<select id='buildout-select' name='bay-post-type'><option value='normal'>Normal</option><option value='flexible'>Flexible</option></select>");


            // if (configuration[id] !== 'undefined') {
            //     $(element).find("input").val('normal');
            // }
            $(element).appendTo(".extra-columns-buildout-row div");

            $('#buildout-select').parent().parent().find('.input-group').remove();

            $('#buildout-select').parent().parent().html(function (i, html) {
                return html.replace(":", "");
            });


            $("select#buildout-select").change(function () {
                // Check input( $( this ).val() ) for validity here
                if ($(this).val() === 'flexible') {
                    console.log('flexible');
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                }
            });

            if (configuration[id] !== 'undefined') {
                $(element).find("input").val(configuration[id]);
            }

            if ($(".extra-column-buildout").length > 0) {
                $(element).insertAfter($(".extra-columns-buildout-row div .extra-column-buildout").last());
            } else {
                $(element).appendTo(".extra-columns-buildout-row div");
            }
        }


        //add new field select based on layout code if material is ecowood
        function addFieldBuildAngleSelect(label, id, helptext) {

            //use data from prototype column
            //console.log('add extra-column 2');
            var new_column_contents = $("#extra-column").html();
            //console.log(new_column_contents);

            //create extra column element
            var new_column = "<div class=\"pull-left extra-column\">" + new_column_contents + "</div>";
            var element = $(new_column);
            $(element).find(".extra-column-label").html(label);
            $(element).find("input").remove();
            $(element).find(".extra-column-label").append("<select class='b-angle-select' id='" + id + "' name='" + id + "'><option value='90'>90</option><option value='135'>135</option></select>");

            $(element).find('.input-group').remove();

            $(element).html(function (i, html) {
                return html.replace(":", "");
            });

            if ($(".extra-column").length > 0) {
                $(element).insertAfter($(".extra-column").last());
            } else {
                $(element).appendTo(".extra-columns-row div");
            }
        }


        //add new field Buildout based on layout code checkbox
        function addFieldCheckboxBuildout(label, id, helptext) {

            //use data from prototype column
            //console.log('add extra-column 2');
            var new_column_contents = $("#extra-column").html();
            //console.log(new_column_contents);

            //create extra column element
            var new_column = "<div class=\"pull-left extra-column-buildout\">" + new_column_contents + "</div>";
            var element = $(new_column);
            $(element).find(".extra-column-label").html(label);
            $(element).find("input").attr(
                {
                    "type": "checkbox",
                    "id": id,
                    "name": id,
                    "value": "yes",
                }
            );


            if (configuration[id] !== 'undefined') {
                $(element).find("input").val('yes');
            }

            if ($(".extra-column-buildout").length > 0) {
                $(element).insertAfter($(".extra-columns-buildout-row div .extra-column-buildout").last());
            } else {
                $(element).appendTo(".extra-columns-buildout-row div");
            }
        }

        //add new field Buildout based on layout code checkbox
        function addFieldCheckboxBuildoutSelect(label, id, helptext, character) {

            //use data from prototype column
            //// console.log('add extra-column 2');
            var new_column_contents = $("#extra-column").html();
            //// console.log(new_column_contents);

            //create extra column element
            var new_column = '<div class="pull-left extra-column-buildout">' + new_column_contents + '</div>';
            var element = $(new_column);

            $(element).find(".extra-column-label").html(label);
            $(element).find("input").remove();
            if (character === 'b') {
                $(element).find(".extra-column-label").append("<select id='buildout-select' name='bay-post-type'><option value='normal'>Normal</option><option value='flexible'>Flexible</option></select>");
            } else if (character === 't') {
                $(element).find(".extra-column-label").append("<select id='buildout-select-t' name='t-post-type'><option value='normal'>Normal</option><option value='adjustable'>Adjustable</option></select>");
            }

            // if (configuration[id] !== 'undefined') {
            //     $(element).find("input").val('normal');
            // }
            $(element).appendTo(".extra-columns-buildout-row > div");

            $('#buildout-select').parent().parent().find('.input-group').remove();

            $('#buildout-select').parent().parent().html(function (i, html) {
                return html.replace(":", "");
            });
            $('#buildout-select-t').parent().parent().html(function (i, html) {
                return html.replace(":", "");
            });


            $("select#buildout-select").change(function () {
                // Check input( $( this ).val() ) for validity here
                if ($(this).val() === 'flexible') {
                    // console.log('flexible');
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                }
            });

            // if ($(".extra-column-buildout").length > 0) {
            //     $(element).insertAfter($(".extra-columns-buildout-row div .extra-column-buildout").last());
            // } else {
            //     $(element).appendTo(".extra-columns-buildout-row div");
            // }
        }


        function calculateTotal() {
            total = $("#property_width").val() * $("#property_height").val();
            if (total == 'NaN')
                total = 0;
            $("#property_total").val(parseFloat(total) / parseFloat(1000000));

            //midrailheight required for >1800 height and NOT Tier styles
            style_check = getStyleTitle();
            if (parseFloat($("#property_height").val()) >= 1800 && parseFloat($("#property_height").val()) <= 3000 && style_check.indexOf('Tier') == -1) {
                $("#property_midrailheight").addClass("required");
                console.log('midrail required');
                //$("#midrail-height").show();
            } else {
                //$("#property_midrailheight").removeClass("required");
                //$("#midrail-height").hide();
            }

            filterStiles();
            showHideControlSplit2();
        }

        //get the property id based on ui property code eg: property_fit = property with id 9
        function getPropertyIdByCode(code) {
            id = 0;
            for (i = 0; i < property_fields.length; i++) {
                if (("property_" + property_fields[i].code) == code) {
                    id = property_fields[i].id;
                }
            }
            return id;
        }


        function showMidrailPositionCritical() {
            if ($("#property_material").select2('data')) {
                product_title_check = $("#property_material").select2('data').value;
                var property_midrailpositioncritical = $("#property_midrailpositioncritical").val();

                if (product_title_check.indexOf('PVC') == -1 && $("#property_midrailheight").val() > 0) {
                    $("#midrail-position-critical").show();
                    $("midrail-position-critical input").removeClass('not-required');
                    if (property_midrailpositioncritical === '') {
                        $("#property_midrailpositioncritical").select2("val", '170');
                    }
                } else if (product_title_check.indexOf('PVC') == -1 && $("#property_midrailheight2").val() > 0) {
                    $("#midrail-position-critical").show();
                    $("midrail-position-critical input").removeClass('not-required');
                    if (property_midrailpositioncritical === '') {
                        $("#property_midrailpositioncritical").select2("val", '170');
                    }
                } else {
                    $("#midrail-position-critical").hide();
                    $("#midrail-position-critical input").addClass('not-required');
                }
            } else {
                $("#midrail-position-critical").hide();
                $("#midrail-position-critical input").addClass('not-required');
            }
        }

        function resetErrors() {
            $(".error-field").removeClass("error-field");
            $("span.error-text").remove();
        }

        function getStyleTitle() {
            var title = '';
            if ($('input[name=property_style]:checked').length > 0) {
                title = $('input[name=property_style]:checked').data('title');
            }
            return title;
        }

        function checkStyleTier() {
            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
            } else {
                return;
            }

            if (style_check.indexOf('Café') > -1) {
                $("#property_frametop").select2("val", '81');
            }


            console.log('select style ----------------');

//teo                    if (style_check.indexOf('Café') > -1) {
//teo                        console.log('selected CAFE Frame NO  ----------------  1');
//teo                        $("#property_material").select2("val", '187');
//teo                    }

            if (!$("#property_frametop").select2("data")) {
                console.log('selected CAFE Frame NO  ----------------  2');
                if (style_check.indexOf('Café') > -1) {
                    $("#property_frametop").select2("val", '81');
                }
            }
//teo
            if (style_check.indexOf('Special') > -1) {
                $(".tot-height").fadeIn();
                $("#property_totheight").fadeIn();
                $("#property_totheight").removeClass("required");
                $("#property_totheight").addClass("not-required");
            } else if (style_check.indexOf('Tier') > -1 && style_check.indexOf('Solid') == -1) {
                $(".tot-height").fadeIn();
                $("#property_totheight").fadeIn();
                $("#property_totheight").addClass("required");
                $("#property_midrailheight").removeClass("required");
                $("#property_totheight").removeClass("not-required");
                //ring-pull
                $("#ring-pull").hide();
                $("#property_ringpull").val('No');
                $("#solid-panel-height").hide();
            } else if (style_check.indexOf('Solid') > -1) {

                //$("#solid-panel-height").show();
                $("#midrail-height").show();
                $("#midrail-height2").show();
                $("#midrail-divider").hide();
                $("#midrail-divider2").hide();
                $("#midrail-height input").val('');
                $("#solidtype").show();
                // $("#midrail-height2").hide();
                // $("#midrail-height2 input").val('');
                $("#property_bladesize").closest('div').hide();
                $("#property_bladesize").val('');
                $("#property_bladesize").addClass('not-required');

                //$("#property_sparelouvres").prop('checked', false);
                $("#property_sparelouvres").val('No');
                $("#spare-louvres").hide();
                $("#spare-louvres").closest("div.row").hide();
                $("#ring-pull").show();
                $("#property_controltype").select2('val', '');
                $("#property_controltype").addClass('not-required');
                $("#property_controltype").closest('div').hide();
                $("#control-split-height").hide();

                if ($('form').attr('edit') === 'no') {

                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_frametop").select2("val", '80');
                    $("#property_framebottom").select2("val", '85');

                }

                if (style_check.indexOf('Tier') > -1) {
                    $(".tot-height").fadeIn();
                    $("#property_totheight").fadeIn();
                    $("#property_totheight").addClass("required");
                    $("#midrail-height").hide();
                    $("#midrail-height2").hide();
                } else if (style_check.indexOf('Café') > -1) {
                    $("#midrail-height").hide();
                    $("#midrail-height2").hide();
                } else {
                    $(".tot-height").hide();
                    $("#property_totheight").val('');
                    $("#property_totheight").removeClass("required");
                    $("#property_totheight").addClass("not-required");
                }
            } else if (style_check.indexOf('Combi') > -1) {
                $("#spare-louvres").hide();

                $("#midrail-height").hide();
                $("#midrail-height2").hide();
                $("#midrail-divider").hide();
                $("#midrail-divider2").hide();
                $("#midrail-height input").val('');
                // $("#midrail-height2").hide();
                // $("#midrail-height2 input").val('');
                $("#property_bladesize").closest('div').show();
                $("#property_bladesize").val('');
                $("#property_bladesize").addClass('not-required');

                //$("#property_sparelouvres").prop('checked', false);
                $("#property_sparelouvres").val('No');
                $("#spare-louvres").hide();
                $("#spare-louvres").closest("div.row").hide();
                $("#ring-pull").show();
                $("#property_controltype").select2('val', '');
                $("#property_controltype").addClass('not-required');
                $("#property_controltype").closest('div').hide();
                $("#control-split-height").hide();

                if ($('form').attr('edit') === 'no') {
                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_frametop").select2("val", '80');
                    $("#property_framebottom").select2("val", '85');
                }

                if (style_check.indexOf('Tier') > -1) {
                    $(".tot-height").fadeIn();
                    $("#property_totheight").fadeIn();
                    $("#property_totheight").addClass("required");
                } else {
                    $(".tot-height").hide();
                    $("#property_totheight").val('');
                    $("#property_totheight").removeClass("required");
                    $("#property_totheight").addClass("not-required");
                }

                $("#solid-panel-height").show();
                $("#property_midrailheight").removeClass("required");

            } else {
                $("#ring-pull").hide();
                $("#solidtype").hide();
                $("#solid-panel-height").hide();
                //$("#property_ringpull").prop('checked', false);
                $("#property_ringpull").val('No');
                $(".tot-height").fadeOut();
                $("#property_horizontaltpost").prop('checked', false);
                $("#property_totheight").val('');
                $("#property_totheight").removeClass("required");
                //if no tier style then add required to midrailheight if height>1800
                if (parseFloat($("#property_height").val()) >= 1800 && parseFloat($("#property_height").val()) <= 3000 && style_check.indexOf('Tier') == -1) {
                    $("#property_midrailheight").addClass("required");
                    console.log('midrail required');
                }

                //$("#solid-panel-height").hide();
                $("#midrail-height").show();
                $("#midrail-height2").show();
                $("#midrail-divider").show();
                $("#midrail-divider2").show();
                // $("#midrail-height2").hide();
                // $("#midrail-height2 input").val('');
                $("#property_bladesize").closest('div').show();

                //$("#property_sparelouvres").prop('checked', false);
                $("#spare-louvres").show();
                $("#spare-louvres").closest("div.row").show();
                $("#property_controltype").closest('div').show();
                $("#control-split-height").show();
            }
            if (style_check.indexOf('Solid') == -1 && style_check.indexOf('Combi') < -1) {
                $("#midrail-height").show();
                $("#property_bladesize").closest('div').show();
                $("#property_bladesize").removeClass('not-required');

                $("#spare-louvres").show();
                $("#spare-louvres").closest("div.row").show();
                $("#property_controltype").closest('div').show();

                //$("#property_framebottom, #property_frametop,
                // #property_frameright,#property_frameleft").prop('readonly',false);
            }
            if (style_check.indexOf('Solid Panel Bay Window Full Height') > -1) {
                console.log('SELECTED Solid Panel Bay Window Full Height');
                $("#midrail-height").show();
                $("#midrail-height2").show();
                $("#solid-panel-height").hide();
            }
            if (style_check.indexOf('Solid Panel Bay Window Cafe Style') > -1) {
                $("#midrail-height").hide();
                $("#midrail-height2").hide();
            } else if (style_check.indexOf('Solid Combi Panel Bay Window') > -1) {
                console.log('Solid Combi Panel Bay Window');
                $("#solid-panel-height").show();
                $("#property_bladesize").closest('div').show();
                $("#midrail-height").hide();
                $("#midrail-height2").hide();
            }
            // else{
            //     $("#solid-panel-height").hide();
            //     $("#midrail-height").hide();
            //     $("#midrail-height2").hide();
            // }

            if ($('#property_horizontaltpost').is(":visible") === false) {
                $("#property_horizontaltpost").prop('checked', false);
                $("#property_horizontaltpost").attr('value', 'No');
            } else {
                $("#property_horizontaltpost").attr('value', 'Yes');
            }
        }

        //we need to show/hide control split height 2 if we have midrail or totheight
        function showHideControlSplit2() {
            var check_height = parseFloat($("#property_height").val());
            var check_louvresize = getPropertyBladesize();
            var check_controltype = $("#property_controltype").val();
            var check_controlsplitheight = parseFloat($("#property_controlsplitheight").val());
            var check_controlsplitheight2 = parseFloat($("#property_controlsplitheight2").val());
            var check_midrailheight = getPropertyMidrailheight();
            var check_totheight = getPropertyTotHeight();
            var show_split2 = false;

            if (check_controltype == '96' || check_controltype == '95') {
                var height_required_split;
                if (check_louvresize == '63') height_required_split = 876;
                if (check_louvresize == '76') height_required_split = 1060;
                if (check_louvresize == '89') height_required_split = 1105;

                if (check_midrailheight > 0 || check_totheight > 0) {

                    var split_panel_at_height = 0;
                    if (check_midrailheight > 0) split_panel_at_height = check_midrailheight;
                    if (check_totheight > 0) split_panel_at_height = check_totheight;
                    var panel1_height = check_height - split_panel_at_height;
                    var panel2_height = check_height - panel1_height;

                    if (panel1_height > height_required_split && panel2_height > height_required_split) {
                        show_split2 = true;
                    }
                }
            }
            if (show_split2) {
                $("#property_controlsplitheight2").show();
            } else {
                $("#property_controlsplitheight2").hide();
                $("#property_controlsplitheight2").val(0);
            }
        }

        function checkShutterType() {
            if (shutter_type == 'Shutter') {
                var disable_property_values = [];
            } else if (shutter_type == 'Blackout') {
                var disable_property_values = [187, 33, 35];
            }

            for (var i = 0; i < disable_property_values.length; i++) {
                for (var j = 0; j < property_values.length; j++) {
                    if (property_values[j].id == disable_property_values[i]) {
                        property_values.splice(j);
                    }
                }
            }
        }

        function filterControlType() {
            var property_material = ($("#property_material").select2('data') ? $("#property_material").select2('data').value : '');
            var property_bladesize = ($("#property_bladesize").select2('data') ? $("#property_bladesize").select2('data').value : '');
            var property_stile = ($('input[name=property_stile]:checked').attr('data-title') ? $('input[name=property_stile]:checked').attr('data-title') : '');
            var property_hingecolour = ($("#property_hingecolour").select2('data') ? $("#property_hingecolour").select2('data').value : '');
            var show_hidden_tilt = false

            if (
                property_material.indexOf('PVC') == -1 &&
                property_bladesize.indexOf('47mm') == -1 &&
                property_bladesize.indexOf('114mm') == -1 &&
                //property_stile.indexOf('38.1') == -1 &&
                property_hingecolour.indexOf('Hidden') == -1) {
                show_hidden_tilt = true;
            } else {
                show_hidden_tilt = false;
            }

            controltype_data = getAllFieldData(getPropertyIdByCode('property_controltype'));
            console.log(controltype_data);
            var new_controltype_data = [];
            $.each(controltype_data, function (index, row) {
                //aluminum has only hidden rod teo_03-Earth Hidden Only
//teo_04-Earth Hidden Only                    if (property_material.indexOf('Earth') > -1 &&
// row.value.indexOf('Clearview') == -1) {
                if (property_material.indexOf('Earth') > -1 && row.value.indexOf('Hidden') == -1) {
                    return true;
                }

                if (property_material.indexOf('PVC') > -1 && property_material.indexOf('UK') > -1) {
                    if (row.value.indexOf('Clearview') == -1)
                        return true;
                }

                if (show_hidden_tilt) {
                    new_controltype_data.push(row);
                } else {
                    if (row.value.indexOf('Hidden') == -1) {
                        new_controltype_data.push(row);
                    }
                }
            });
            console.log(new_controltype_data);

            loadItems('property_controltype', new_controltype_data);
        }

        /* submit checks */
        $("#add-product-single-form").submit(function (e) {
            e.preventDefault();
            alert('submit-press');
            resetErrors();


            var check_height = parseFloat($("#property_height").val());
            var style_check = getStyleTitle();

            var check_controltype = $("#property_controltype").val();
            var check_controlsplitheight = parseFloat($("#property_controlsplitheight").val());
            var check_controlsplitheight2 = parseFloat($("#property_controlsplitheight2").val());
            var check_midrailheight = getPropertyMidrailheight();
            if (check_midrailheight == '') {
                check_midrailheight = 0;
            }
            if (isNaN(check_controlsplitheight)) {
                check_controlsplitheight = 0;
            }
            var check_totheight = getPropertyTotHeight();
            var width_and_height_errors = '';
            var width_and_height_errors_count = 0;
            var tpost_count = 0;

            //find any validation errors
            errors = 0;
            $(".select2-container").removeClass("error-field");
            $("input").removeClass("error-field");
            $(".error-text").remove();
            $("#nowarranty").hide();
            if ($('input[name=property_style]:checked').length == 0) {
                errors++;
                $("<span class=\"error-text\">Please select a style</span>").insertAfter($("#choose-style"));
            }

            //check if property frametype is selected & if the selected value is visible
            //parent is used, because input is by default hidden, but the parent is not
            if ($('input[name=property_frametype]:checked').length == 0) {
                errors++;
                $("<span class=\"error-text\">Please select frame type</span>").insertBefore($("#choose-frametype"));
                $("#choose-frametype").closest(".panel").find(".panel-collapse").collapse("show");
                error_text = 'Please select frame type';
                addError("property_frametype", error_text);
                modalShowError(error_text);
            }
            //check if select or input that are marked as required have values
            $("select.required, input.required").not('.not-required').each(function () {
                if ($(this).val() == '' && $(this).is(":visible")) {
                    errors++;
                    addError($(this).attr('id'), 'Please fill in this field');
                } else {
                    //also check if select2('data') has values for required select boxes, we need this check for
                    // filtered values
                    if ($(this).data('select2') != 'undefined') {
                        console.log("Checking valid select2 data for id " + $(this).attr('id'));
                        console.log($(this).data('select2'));
                        if (!$(this).select2('data') || $(this).select2('data').id == 'undefined') {
                            errors++;
                            addError($(this).attr('id'), 'Please fill in this field');
                        }
                    }
                }
            });

            $(".extra-columns-row input.required").each(function () {
                if ($(this).val() == '') {
                    errors++;
                    addError($(this).attr('id'), 'Please fill in this field');
                }
            });

            $(".number").each(function () {
                if ($(this).val() != '' & !isPositiveInteger($(this).val())) {
                    errors++;
                    addError($(this).attr('id'), 'Please enter a correct number');
                }
            });

            $("input.property-select").not('.not-required').each(function () {
                if ($(this).val() == '' && $(this).is(":visible")) {
                    errors++;
                    addError($(this).attr('id'), 'Please fill in this field');
                } else {
                    //also check if select2('data') has values for required select boxes, we need this check for
                    // filtered values
                    if ($(this).data('select2') != 'undefined') {
                        console.log("Checking valid select2 data for id " + $(this).attr('id'));
                        console.log($(this).data('select2'));
                        if (!$(this).select2('data') || $(this).select2('data').id == 'undefined' && $(this).is(":visible")) {
                            errors++;
                            addError($(this).attr('id'), 'Please fill in this field');
                        }
                    }
                }
            });

            //midrail should be below 1800mm
            if (check_midrailheight > 1800) {
                error_text = 'Midrail Height should be below 1800mm';
                width_and_height_errors = width_and_height_errors + error_text + '. ';
                width_and_height_errors_count++;
                errors++;
                addError("property_midrailheight", error_text);
            }

            //minimum height check
            if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) < 260) {
                errors++;
                error_text = 'Height should be at least 260mm';
                width_and_height_errors = width_and_height_errors + error_text + '. ';
                width_and_height_errors_count++;
                addError("property_height", error_text);
            }

            //height check for tot and not tot
            // var stile_check = getPropertyStile();
            // if (stile_check == 50.8) {
            //     panel_height = 1800;
            // } else {
            //     panel_height = 1500;
            // }
            // max_height = panel_height * 2;

            //height check for tot and not tot
            var id_material = $("input#property_material").val();
            //var stile_check = getPropertyStile();
            //green-137
            if (id_material == 137) {
                panel_height = 1350;
            }
            //biowood-138, supreme-139, earth-187
            else {
                panel_height = 1500;
            }
            max_height = panel_height * 2;
            ;

            if (style_check.indexOf('Tier') > -1) {
                if (check_height > panel_height && check_totheight == 0) {
                    errors++;
                    error_text = 'T-o-t height required for height more than ' + panel_height.toString() + 'mm. ';
                    width_and_height_errors = width_and_height_errors + error_text;
                    width_and_height_errors_count++;
                    addError("property_totheight", error_text);
                }
            } else {
                if (check_height > panel_height && check_midrailheight == 0) {
                    errors++;
                    error_text = 'Midrail height required for height more than ' + panel_height.toString() + 'mm. ';
                    width_and_height_errors = width_and_height_errors + error_text;
                    width_and_height_errors_count++;
                    addError("property_midrailheight", error_text);
                }
            }

            //max height
            if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) > max_height) {
                errors++;
                error_text = 'Height should not exceed ' + max_height.toString() + 'mm. ';
                width_and_height_errors = width_and_height_errors + error_text;
                width_and_height_errors_count++;
                addError("property_height", error_text);
            }

            //midrailheight should not be more than height of shutter
            if (check_midrailheight > 0 && (check_midrailheight > check_height)) {
                error_text = 'Midrail Height should not exceed height of shutter ' + check_height.toString() + 'mm. ';
                errors++;
                addError("property_midrailheight", error_text);
            }

            //calculate max width
            //consecutive same panels 1=850,2=650,3=550
            var layout_code = $("#property_layoutcode").val();
            var max_width = 0,
                min_width = 0;
            var current_max_width = 0;
            layout_code = layout_code.toUpperCase();

            counter = 0;
            last_char = ''; //used to check if last character is different
            total_panels = 0;
            tracked_layout_error = false

            var id_material = $("#property_material").select2('data').id;

            var check_louvresize = $("input#property_bladesize").val();

            //calculate width   biowood-138  supreme-139  green-137   earth-187
            panel1_width = (id_material == 137 ? 890 : 890);
            panel2_width = (id_material == 137 ? 550 : 550);
            panel2_width = (id_material == 138 ? 625 : 625);
            panel3_width = (id_material == 138 ? 550 : 550);
            panel2_width = (id_material == 188 ? 625 : 625);
            panel3_width = (id_material == 188 ? 550 : 550);
            panel3_width = (id_material == 137 ? 550 : 550);

            if (check_louvresize == 53 && id_material == 137) {
                panel1_width = (id_material == 137 ? 750 : 750);
            }
            if (check_louvresize == 54 && id_material == 137) {
                panel1_width = (id_material == 137 ? 890 : 890);
            }
            if (check_louvresize == 55 && id_material == 137) {
                panel1_width = (id_material == 137 ? 890 : 890);
            }
            if (check_louvresize == 165 && id_material == 137) {
                panel1_width = (id_material == 137 ? 890 : 890);
            }

            if (id_material == 138 || id_material == 188) {


                for (var i = 0; i < layout_code.length; i++) {
                    if (layout_code.charAt(i) != 'L' && layout_code.charAt(i) != 'R') {
                        last_char = layout_code.charAt(i);
                        continue;
                    }
                    //console.log(layout_code.charAt(i));

                    total_panels++;
                    if (last_char != layout_code.charAt(i)) {
                        //check if we dont have an even number of panels for tracked
                        if (last_char != '' && (style_check.indexOf('Tracked') > -1) && (counter % 2 != 0)) {
                            tracked_layout_error = true;
                        }
                        counter = 1;
                    } else {
                        counter++;
                    }

                    if (layout_code.charAt(i + 1) == 'undefined' || layout_code.charAt(i + 1) != layout_code.charAt(i)) {
                        if (counter == 1) {
                            current_max_width = counter * panel1_width;
                            if (current_max_width > 890) {
                                console.log('counter litere: ' + counter);
                                error_text = '<br/>Max width for single panel too high.';
                                errors++;
                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                                alert('1');
                            }
                            if (panel1_width < 200) {
                                console.log('counter litere: ' + counter);
                                error_text = '<br/>Min width for single panel too low.';
                                errors++;
                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                                alert('2');
                            }
                        } else if (counter == 2) {
                            current_max_width = counter * panel2_width;
                            if (current_max_width > 550 * counter) {
                                console.log('counter litere: ' + counter);
                                error_text = '<br/>Max width for multiple panel too high.';
                                errors++;
                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                                alert('3');
                            }
                            if (panel2_width < 200) {
                                console.log('counter litere: ' + counter);
                                error_text = '<br/>Min width for multiple panel too low.';
                                errors++;
                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                                alert('4');
                            }
                        } else if (counter == 3) {
                            current_max_width = counter * panel3_width;
                            if (current_max_width > 6250 * counter) {
                                console.log('counter litere: ' + counter);
                                error_text = '<br/>Max width for multiple panel too high.';
                                errors++;
                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                                alert('5');
                            }
                            if (panel3_width < 200) {
                                console.log('counter litere: ' + counter);
                                error_text = '<br/>Min width for multiple panel too low.';
                                errors++;
                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                                alert('6');
                            }
                        } else { //counter > 3 means that we have LLLL OR RRRR. More than 3 same panels allowed only for tracked.
                            if (!(style_check.indexOf('Tracked') > -1)) {
                                current_max_width = counter * panel2_width;
                                error_text = '<br/>Layout code is invalid. No more than 3 consecutive ' + last_char + ' panels allowed.';
                                errors++;
                                width_and_height_errors_count++;
                                width_and_height_errors = width_and_height_errors + error_text;
                                console.log('counter litere: ' + counter);

                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                            }
                        }
                        max_width = max_width + current_max_width;
                    }
                    console.log('counter litere 2: ' + counter);

                    last_char = layout_code.charAt(i);
                }
            } else {

                for (var i = 0; i < layout_code.length; i++) {
                    if (layout_code.charAt(i) != 'L' && layout_code.charAt(i) != 'R') {
                        last_char = layout_code.charAt(i);
                        continue;
                    }
                    //console.log(layout_code.charAt(i));


                    if (layout_code.charAt(i + 1) == 'undefined' || layout_code.charAt(i + 1) != layout_code.charAt(i)) {
                        if (counter == 1) {
                            current_max_width = counter * panel1_width;
                        } else if (counter == 2) {
                            current_max_width = counter * panel2_width;
                        }
                            // else if (counter == 3) {
                            // 	current_max_width = counter * panel3_width;
                        // }
                        else { //counter > 3 means that we have LLLL OR RRRR. More than 3 same panels allowed only for tracked.
                            if (!(style_check.indexOf('Tracked') > -1)) {
                                current_max_width = counter * panel2_width;
                                error_text = '<br/>Layout code is invalid. No more than 2 consecutive ' + last_char + ' panels allowed.';
                                errors++;
                                width_and_height_errors_count++;
                                width_and_height_errors = width_and_height_errors + error_text;
                                console.log('counter litere: ' + counter);

                                addError("property_layoutcode", error_text);
                                modalShowError(error_text);
                            }
                        }
                        max_width = max_width + current_max_width;
                    }
                    console.log('counter litere 2: ' + counter);

                    last_char = layout_code.charAt(i);
                }

                total_panels++;
                if (last_char != layout_code.charAt(i)) {
                    //check if we dont have an even number of panels for tracked
                    if (last_char != '' && (style_check.indexOf('Tracked') > -1) && (counter % 2 != 0)) {
                        tracked_layout_error = true;
                    }
                    counter = 1;
                } else {
                    counter++;
                }

            }
            //we need to check again tracked at the end if the panels are even
            //check if we dont have an even number of panels for tracked
            if ((style_check.indexOf('Tracked') > -1) && (counter % 2 != 0)) {
                tracked_layout_error = true;
            }

            if (tracked_layout_error) {
                errors++;
                addError("property_layoutcode", 'Tracked shutters require even number of panels per layout code');
            }
            //calculate min width based on the number of panels (Ls&Rs)
            min_width = total_panels * 200;

            //minimum for solid panels and raised is 260mm
            if (style_check.indexOf('Solid') > -1 && style_check.indexOf('Raised') > -1) {
                min_width = total_panels * 250;
            }

            if ($("#property_width").val() != '' && parseFloat($("#property_width").val()) < min_width) {
                errors++;
                error_text = 'Width should be at least ' + min_width + 'mm. ';
                width_and_height_errors = width_and_height_errors + error_text;
                width_and_height_errors_count++;
                addError("property_width", error_text);
            }

            if ($("#property_width").val() != '' && parseFloat($("#property_width").val()) > max_width && !(style_check.indexOf('Tracked') > -1)) {
                errors++;
                error_text = 'Width should be at most ' + max_width + 'mm for this layout code.';
                width_and_height_errors = width_and_height_errors + error_text;
                width_and_height_errors_count++;
                addError("property_width", error_text);
            }

            //For Blackout shutters a Tpost is required for >1400mm width
            if (shutter_type == 'Blackout' && ((layout_code.match(/t/ig) || []).length == 0 && (layout_code.match(/b/ig) || []).length == 0 && (layout_code.match(/c/ig) || []).length == 0) && parseFloat($("#property_width").val()) > 1400) {
                errors++;
                addError("property_layoutcode", 'Shutter and Blackout Blind require a T-post if width is more than 1400mm');
            }

            if ((layout_code.indexOf("B") > 0 || layout_code.indexOf("C") > 0) && (style_check.indexOf('Bay') == -1)) {
                errors++;
                addError("property_layoutcode", 'Please choose Bay Window style with a layout code containing B or C.');
            }

            /* clearview checks */
            var check_louvresize = getPropertyBladesize();
            if (check_controltype == '96' || check_controltype == '95') {
                var split_required = false;
                var split2_required = false;

                var split_min_height = 0;
                var split_max_height = 0;

                var split2_min_height = 0;
                var split2_max_height = 0;

                var height_required_split;
                if (check_louvresize == '47') {
                    height_required_split = 923;
                    if (check_midrailheight > 0)
                        height_required_split = 878;
                }

                if (check_louvresize == '63') {
                    height_required_split = 960;
                    if (check_midrailheight > 0)
                        height_required_split = 900;
                }
                if (check_louvresize == '76') {
                    height_required_split = 1155;
                    if (check_midrailheight > 0)
                        height_required_split = 1085;
                }
                if (check_louvresize == '89') {
                    height_required_split = 1190;
                    if (check_midrailheight > 0)
                        height_required_split = 1130;
                }

                if (check_louvresize == '114') height_required_split = 1200;

                if (check_midrailheight == 0 && check_totheight == 0 && check_height > height_required_split) {
                    split_min_height = 0;
                    split_max_height = height_required_split;
                    split_required = true;

                    //**REMOVE THE BELOW ADD SOMEWHERE ELSE
                    //if(parseInt(check_controlsplitheight)==0 && check_height>height_required_split){
                    //    split_required = true;
                    //}
                } else if (check_midrailheight > 0 || check_totheight > 0) {

                    var split_panel_at_height = 0;
                    if (check_midrailheight > 0) split_panel_at_height = parseInt(check_midrailheight);
                    if (check_totheight > 0) split_panel_at_height = parseInt(check_totheight);
                    var panel1_height = split_panel_at_height;
                    var panel2_height = check_height - panel1_height;

                    console.log("Panel 1 height:" + panel1_height);
                    console.log("Panel 2 height:" + panel2_height);
                    console.log("Height required split:" + height_required_split);

                    if (panel1_height > height_required_split && panel2_height > height_required_split) {
                        split_required = true;
                        split2_required = true;

                        split_min_height = 0;
                        split_max_height = split_panel_at_height;

                        split2_min_height = split_panel_at_height + 1;
                        split2_max_height = check_height;
                    } else if (panel1_height > height_required_split || panel2_height > height_required_split) {
                        split_required = true;
                        if (panel1_height > height_required_split) {
                            split_min_height = 0;
                            split_max_height = split_panel_at_height;
                        } else {
                            split_min_height = split_panel_at_height + 1;
                            split_max_height = check_height;
                        }
                    }
                }

                if (check_midrailheight > 0 && check_controlsplitheight > 0) {
                    var distance = Math.abs(check_midrailheight - check_controlsplitheight);
                    if (distance <= 10) {
                        error_text = '<br/>Distance between split height and midrail should be more than 10mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight", error_text);
                    }
                }

                if (check_midrailheight > 0 && parseInt(check_controlsplitheight2) > 0) {
                    var distance = Math.abs(check_midrailheight - parseInt(check_controlsplitheight2));
                    if (distance <= 10) {
                        error_text = '<br/>Distance between split height 2 and midrail should be more than 10mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight2", error_text);
                    }
                }

                // if (split_required) {
                //     $("#property_controltype").select2("val", 95); //change to clearview split if not already
                // changed
                //     $("#control-split-height").show();
                //     if (parseInt(check_controlsplitheight) == 0) {
                //         error_text = '<br/>Split height is required for ' + check_louvresize + 'mm and height ' +
                // check_height + 'mm'; errors++; width_and_height_errors_count++; width_and_height_errors =
                // width_and_height_errors + error_text; addError("property_controlsplitheight", error_text); }

                //     if (check_controlsplitheight > split_max_height) {
                //         error_text = '<br/>Split height should be less than ' + split_max_height + 'mm';
                //         errors++;
                //         width_and_height_errors_count++;
                //         width_and_height_errors = width_and_height_errors + error_text;
                //         addError("property_controlsplitheight", error_text);
                //     }
                //     if (check_controlsplitheight < split_min_height) {
                //         error_text = '<br/>Split height should be more than ' + split_min_height + 'mm';
                //         errors++;
                //         width_and_height_errors_count++;
                //         width_and_height_errors = width_and_height_errors + error_text;
                //         addError("property_controlsplitheight", error_text);
                //     }
                // }

                if (split2_required) {
                    $("#property_controltype").select2("val", 95); //change to clearview split if not already changed
                    $("#control-split-height").show();
                    $("#property_controlsplitheight2").show();
                    if (parseInt(check_controlsplitheight2) == 0) {
                        error_text = '<br/>Second split height is required for ' + check_louvresize + 'mm and height ' + check_height + 'mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight2", error_text);
                    }

                    if (check_controlsplitheight2 > split2_max_height) {
                        error_text = '<br/>Second split height should be less than ' + split2_max_height + 'mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight2", error_text);
                    }
                    if (check_controlsplitheight2 < split2_min_height) {
                        error_text = '<br/>Second split height should be more than ' + split2_min_height + 'mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight2", error_text);
                    }
                } else {
                    $("#property_controlsplitheight2").hide();
                }
            }

            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
                if (style_check.indexOf('Shaped') > -1) {
                    existingShapeFile = $('#provided-shape').html().trim();
                    newShapeFile = $('#attachment').val();
                    /* if (existingShapeFile == '' && newShapeFile == '') {
                            errors++;
                            addError("shape-upload-container", 'Please provide the desired shape for style "Shaped & French Cut Out"');
                        } */
                }
            }

            if ($("#canvas_container1 svg").length > 0) {
                $("#shutter_svg").html($("#canvas_container1").html());
            }

            var nowarranty_checked = $("#property_nowarranty").prop("checked");
            if (errors > 0) {
                if (width_and_height_errors.length > 0 && !nowarranty_checked) {
                    $("#nowarranty").show();
                    showErrorModal("Width and height errors", "This shutter is outside of warranty. The following errors have occured: <br/>" + width_and_height_errors + "<br/><br/>Either <strong>accept</strong> that there will be no warranty or <strong>change</strong> the configuration. ");
                    return false;
                } else if (width_and_height_errors.length > 0 && errors == width_and_height_errors_count && nowarranty_checked) { //if the errors are only width and height and no warranty is checked we can allow everything
                    return true
                } else {
                    return false;
                }
            } else {
                return true;
            }
        });

        $('[data-toggle="tooltip"]').tooltip({
            'placement': 'top'
        });

        if ($('input[name=property_style]:checked').length > 0) {
            $('input[name=property_style]:checked').closest('label').trigger('click');
        }

        $('.drawing-panel').on('shown.bs.collapse', function () {
            //alert('test');
            updateShutter();
        });

        $(window).resize(function () {
            if ($("#canvas_container1").filter(":visible").length > 0) {
                updateShutter();
            }
        });


        $(".print-drawing").click(function () {
            w = window.open();
            w.document.write($('#canvas_container1').html());
            w.print();
            w.close();

            return false;
        });

        if ($("#property_nowarranty").prop("checked")) {
            $("#nowarranty").show();
        }
        //open first collapsing content on page load
        $(document).ready(function () {
            $("#accordion").find(".panel-collapse").first().collapse("show");
            if ($('input[name=property_frametype]:checked').length > 0) {
                $('input[name=property_frametype]:checked').closest('label').trigger('click');
            }

            $("select#buildout-select").change(function () {
                // Check input( $( this ).val() ) for validity here
                if ($(this).val() === 'flexible') {
                    console.log('flexible');
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                }
            });
        });


        ////////////////////// L50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];

            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// SBS50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_SBS50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", 10 - 22, 0,
                "L", 9 - 22, 1.5,
                "C", 9 - 22, 1.5, 4 - 22, -2, -22, 2.5,

                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z60SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z60SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 20.5,
                "L", 16, 29.5 + 30.5 - 20.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", 16 + 10, 30.5 + 9,
                "L", 16 + 15, 30.5 + 9,
                "L", 16 + 15, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 9
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 60, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L70(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 39.5 + 30.5,
                "L", 16, 39.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 70, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L90 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 90, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 50, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L50MF /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50MF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 25.4 + 30.6,
                "L", 16, 25.4 + 30.6,
                "C", 16, 56, 21.5, 53.4, 16, 51,
                "L", 12.5, 51,
                "L", 12.5, 35.6,
                "L", 16, 35.6,
                "C", 16, 35.6, 21.5, 33, 16, 30.6,
                "L", 12.5, 30.6,
                "L", 0, 30.6,
                "L", 0, 2.5,

                "M", 23, 51,
                "L", 28, 51,
                "L", 28, 36,
                "L", 23, 36,
                "L", 23, 51,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11


            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 56, 43.9, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L60SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_L60SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 20.5,
                "L", 16, 29.5 + 30.5 - 20.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", 16 + 10, 30.5 + 9,
                "L", 16 + 15, 30.5 + 9,
                "L", 16 + 15, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 9
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 60, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F70(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 39.5 + 30.5,
                "L", 16, 39.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 70, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 90, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        ////////////////////// Z40 /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", 10 - 22 - 18, 0,
                "C", 10 - 22 - 18, 0, 10 - 22 - 28 + 1, 1, 10 - 22 - 28, 9,
                "L", 10 - 22 - 28, 9,
                "L", -22, 9,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z40SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", 8 - 22 - 18, 0,
                "C", 8 - 22 - 18, 0, 8 - 22 - 28 + 1, 1, 8 - 22 - 28, 9,
                "L", 8 - 22 - 28, 9,
                "L", -22, 9,
                "L", -22, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 20.5,
                "L", 16, 29.5 + 30.5 - 20.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", 16 + 10, 30.5 + 9,
                "L", 16 + 15, 30.5 + 9,
                "L", 16 + 15, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 9
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 60, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -22 - 18, 0,
                "C", -22 - 18, 0, -22 - 28 + 1, 1, -22 - 28, 9,
                "L", -22 - 28, 9,
                "L", -22, 9,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z2BS /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z2BS(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = [
                "M", 0, 0,
                "L", -12, 0,
                "C", -12, 0, -13, 0, -14, 1.5,
                "C", -15, 1.5, -18.5, -2.5, -22, 4,
                "L", -22 - 13.8, 0,
                "C", -22 - 13.8, 0, -22 - 15.8, -0.5, -22 - 17.8, 2,
                "C", -22 - 17.8, 2, -22 - 25, -4, -22 - 28.8, 5,
                "L", -22 - 28.8, 12,
                "L", -22, 12,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z3CS /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z3CS(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -6, 0, //rightmost half (straight)
                "C", -6, 0, -8, -1, -10, 1, //rightmost half arc
                "C", -10, 1, -11.5, 3, -13, 2, //opposite arc between
                "C", -13, 2, -15, 0.5, -18, 3, //right low arc
                /////////////////////
                "L", -22, 6,
                "C", -22, 6, -22 - 16.1, -7, -22 - 32.2, 6, //center arc
                "L", -22 - 36.2, 3,
                /////////////////////
                "C", -22 - 36.2, 3, -22 - 38.2, 0.5, -22 - 41.2, 2, //left low arc
                "C", -22 - 41.2, 2, -22 - 42.2, 3, -22 - 44.2, 1, //opposite arc between
                "C", -22 - 44.2, 1, -22 - 50.2, -3, -22 - 54.2, 4, //leftmost half arc
                "L", -22 - 54.2, 4,
                "L", -22 - 54.2, 14,
                "L", -22, 14,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 0 + 30.5,
                "L", 0, 30.5,
                'L', 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// D50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_D50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -12, 0,
                "C", -12, 0, -13, 0, -14, 1.5,
                "C", -15, 1.5, -19, -2.5, -22, 4,
                "L", -22, 30.5 - 6.5,
                "L", -22 - 22, 30.5 - 6.5,
                "L", -22 - 22, 30.5 - 13,
                "C", -22 - 22, 30.5 - 13, -22 - 26, 13, -22 - 28.8, 30.5 - 11,
                "L", -22 - 28.8, 30.5 + 6.5,
                "L", 16, 30.5 + 6.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", -22 - 22, 30.5 - 13,
                "C", -22 - 22, 30.5 - 13, -22 - 16, 5, -22, 6
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-50.8, 37, 66.8, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// BL90 /////////////////////////////////////////////////////////////////////////
        function drawFrame_BL90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6 - 26,
                "L", 16, 59.5 + 30.5 - 6 - 26,
                // "L", 16, 30.5,
                "L", 16, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 20.5,
                "L", 16, 27.5 + 30.5 - 20.5,
                "L", 16, 30.5,

                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11,

                "M", 16 + 10, 30.5 + 8,
                "L", 16 + 15, 30.5 + 8,
                "L", 16 + 15, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 8
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 90, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", 7 - 25.4, 0,
                "L", 6 - 25.4, 1.5,
                "L", 5 - 25.4, 1.5,
                "C", 5 - 25.4, 1.5, 2.5 - 25.4, -2.5, -25.4, 1.5,
                "L", -25.4, 47.15,
                "L", 9.53, 47.15,
                "L", 9.53, 28.1,
                "L", 0, 28.1,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 47.15, 34.93, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        ////////////////////// F50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "M", 0, 2.5,
                "C", 0, 2.5, -3, -2.5, -6, 2.5,
                "L", -6, 2.5,
                "L", -6, 7,
                "L", -7, 7,
                "L", -7, 12,
                "L", -15, 12,
                "L", -15, 7,
                "L", -16, 7,
                "L", -16, 2.5,
                "C", -16, 2.5, -19, -2.5, -22, 2.5,
                "L", -22, 19.5 + 30.5,
                "L", -11, 19.5 + 30.5,
                "C", -11, 19.5 + 30.5, -8, 19.5 + 30.5, -8, 19.5 + 30.5 - 3,
                "L", 2.5, 19.5 + 30.5 - 3,
                "C", 2.5, 19.5 + 30.5 - 3, 2.5, 19.5 + 30.5, 5.5, 19.5 + 30.5,
                "L", 5.5, 19.5 + 30.5,
                "L", 16.5, 19.5 + 30.5,
                "L", 16.5, 30.5,
                "L", 0, 30.5,
                "L", 0, 2,
                "M", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38.5, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F70PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "M", 0, 2.5,
                "C", 0, 2.5, -3, -2.5, -6, 2.5,
                "L", -6, 2.5,
                "L", -6, 7,
                "L", -7, 7,
                "L", -7, 12,
                "L", -15, 12,
                "L", -15, 7,
                "L", -16, 7,
                "L", -16, 2.5,
                "C", -16, 2.5, -19, -2.5, -22, 2.5,
                "L", -22, 39.5 + 30.5,
                "L", -11, 39.5 + 30.5,
                "C", -11, 39.5 + 30.5, -8, 39.5 + 30.5, -8, 39.5 + 30.5 - 3,
                "L", 2.5, 39.5 + 30.5 - 3,
                "C", 2.5, 39.5 + 30.5 - 3, 2.5, 39.5 + 30.5, 5.5, 39.5 + 30.5,
                "L", 5.5, 39.5 + 30.5,
                "L", 16.5, 39.5 + 30.5,
                "L", 16.5, 30.5,
                "L", 0, 30.5,
                "L", 0, 2,
                "M", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 70, 38.5, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F90PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "M", 0, 2.5,
                "C", 0, 2.5, -3, -2.5, -6, 2.5,
                "L", -6, 2.5,
                "L", -6, 7,
                "L", -7, 7,
                "L", -7, 12,
                "L", -15, 12,
                "L", -15, 7,
                "L", -16, 7,
                "L", -16, 2.5,
                "C", -16, 2.5, -19, -2.5, -22, 2.5,
                "L", -22, 59.5 + 30.5,
                "L", -11, 59.5 + 30.5,
                "C", -11, 59.5 + 30.5, -8, 59.5 + 30.5, -8, 59.5 + 30.5 - 3,
                "L", 2.5, 59.5 + 30.5 - 3,
                "C", 2.5, 59.5 + 30.5 - 3, 2.5, 59.5 + 30.5, 5.5, 59.5 + 30.5,
                "L", 5.5, 59.5 + 30.5,
                "L", 16.5, 59.5 + 30.5,
                "L", 16.5, 30.5,
                "L", 0, 30.5,
                "L", 0, 2,
                "M", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 70, 38.5, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z40PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", 10 - 22 - 20, 0,
                "C", 10 - 22 - 20, 0, 10 - 22 - 28 + 1, 1, 10 - 22 - 28, 9,
                "L", 10 - 22 - 28, 9.53,
                "L", -19.5, 9.53,

                "L", -19.5, 19.05 + 28.1 - 24 - 1,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 24,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 12,
                "L", -19.5, 19.05 + 28.1 - 12 + 1,

                "L", -19.5, 19.05 + 28.1,
                "L", 9.53, 19.05 + 28.1,
                "L", 9.53, 28.1,
                "L", 0, 28.1,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-19.05, 47.15, 28.58, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", 10 - 22 - 30, 0,
                "C", 10 - 22 - 30, 0, 10 - 22 - 38 + 1, 1, 10 - 22 - 38, 9,
                "L", 10 - 22 - 38, 9.53,
                "L", -19.5, 9.53,

                "L", -19.5, 19.05 + 28.1 - 24 - 1,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 24,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 12,
                "L", -19.5, 19.05 + 28.1 - 12 + 1,

                "L", -19.5, 19.05 + 28.1,
                "L", 9.53, 19.05 + 28.1,
                "L", 9.53, 28.1,
                "L", 0, 28.1,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-19.05, 47.15, 28.58, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z2BSPVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z2BSPVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 4,
                "C", 0, 4, -4, -3, -10, 4, //rightmost arc
                "L", -15, 4,
                "C", -15, 4, -15 - 2.25, 6, -19.5, 4,
                "L", -24.5, 4,
                "C", -24.5, 4, -24.5 - 7.25, -3, -24.5 - 14.5, 4, //center arc
                "L", -24.5 - 19.5, 4,
                "C", -24.5 - 19.5, 4, -24.5 - 21.75, 6, -24.5 - 24, 4,
                "L", -22 - 31.5, 4,
                "C", -22 - 31.5, 4, -22 - 37.5, -3, -22 - 41.5, 4, //leftmost half arc
                "L", -63.5, 14.5,
                "L", -20, 14.5,
                "L", -20, 14.5 + 8.65,
                "L", -20, 14.5 + 8.65 + 16,
                "L", -20, 15.5 + 31.65,
                "L", 6.5 - 20, 15.5 + 31.65,
                "L", 7.5 - 20, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 7.5, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 6.5, 15.5 + 31.65,
                "L", 9.5, 15.5 + 31.65,
                "L", 9.5, 31.65,
                "L", 0, 31.65,
                "L", 0, 4
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-19.05, 47.15, 28.58, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z3CSPVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z3CSPVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 4,
                "C", 0, 4, -4, -3, -10, 4, //rightmost arc
                "C", -10, 4, -14, 0, -18, 5, //right low arc
                "C", -18, 5, -22 - 16.1, -6, -22 - 36.2, 5, //center arc
                "C", -22 - 36.2, 5, -22 - 39.2, 0, -22 - 44.2, 4, //left low arc
                "C", -22 - 44.2, 4, -22 - 50.2, -3, -22 - 54.2, 4, //leftmost half arc
                "L", -22 - 54.2, 14.5,
                "L", -26, 14.5,
                "L", -26, 14.5 + 8.65,
                "L", 10 - 26, 14.5 + 8.65,
                "L", 10 - 26, 14.5 + 8.65 + 16,
                "L", -26, 14.5 + 8.65 + 16,
                "L", -26, 15.5 + 31.65,
                "L", 6.5 - 26, 15.5 + 31.65,
                "L", 7.5 - 26, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 7.5, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 6.5, 15.5 + 31.65,
                "L", 9.5, 15.5 + 31.65,
                "L", 9.5, 31.65,
                "L", 0, 31.65,
                'L', 0, 4
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-26, 47.15, 35.47, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };
        ///////////////////////////////////////////////////
        /////////////// STILES and PANEL //////////////////
        ///////////////////////////////////////////////////
        function drawPanelStile_FS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RFS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 38.1, -27,
                    "L", -6 + 38.1, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat == true) {
                var right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                var right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, -27 + 14.5,
                    "L", -6 + 38.1, -27 + 14.5,
                    "L", -6 + 38.1, 0,
                    "L", 0, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DFS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
                "M", 0, 0,
                "L", 0, -27,

                "L", 38.1 - 9, -27,
                "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                "L", 38.1, -3,
                "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                "L", 38.1 - 9, 0,

                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 38.1 - 9, -27,
                    "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                    "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                    "L", 38.1, -3,
                    "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                    "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                    "L", 38.1 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 38.1 - 9, -27,
                    "C", -6 + 38.1 - 9, -27, -6 + 38.1 - 9 + 1, -27, -6 + 38.1 - 9 + 3, -27 + 2,
                    "C", -6 + 38.1 - 9 + 3, -27 + 2, -6 + 38.1 - 9 + 6.5, -27 - 1, -6 + 38.1, -27 + 3,
                    "L", -6 + 38.1, -3,
                    "C", -6 + 38.1, -3, -6 + 38.1 - 9 + 6.5, 1, -6 + 38.1 - 9 + 3, -2,
                    "C", -6 + 38.1 - 9 + 3, -2, -6 + 38.1 - 9 + 1, 0, -6 + 38.1 - 9, 0,

                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat) {
                var right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 9, 0
                ];
            } else {
                var right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 38.1, -27,
                    "L", 38.1, -27 + 14.5,
                    "L", -6 + 38.1, -27 + 14.5,
                    "L", -6 + 38.1, 0,
                    "L", 9, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 38.1 - 9, -27,
                    "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                    "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                    "L", 38.1, -3,
                    "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                    "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                    "L", 38.1 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 38.1 - 9, -27,
                    "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                    "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                    "L", 38.1, -3,
                    "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                    "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                    "L", 38.1 - 9, 0,

                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_FS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RFS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");   //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 50.8, -27,
                    "L", -6 + 50.8, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat == true) {
                var right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                var right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, -27 + 14.5,
                    "L", -6 + 50.8, -27 + 14.5,
                    "L", -6 + 50.8, 0,
                    "L", 0, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DFS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
                "M", 0, 0,
                "L", 0, -27,

                "L", 50.8 - 9, -27,
                "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                "L", 50.8, -3,
                "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                "L", 50.8 - 9, 0,

                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 50.8 - 9, -27,
                    "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                    "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                    "L", 50.8, -3,
                    "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                    "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                    "L", 50.8 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 50.8 - 9, -27,
                    "C", -6 + 50.8 - 9, -27, -6 + 50.8 - 9 + 1, -27, -6 + 50.8 - 9 + 3, -27 + 2,
                    "C", -6 + 50.8 - 9 + 3, -27 + 2, -6 + 50.8 - 9 + 6.5, -27 - 1, -6 + 50.8, -27 + 3,
                    "L", -6 + 50.8, -3,
                    "C", -6 + 50.8, -3, -6 + 50.8 - 9 + 6.5, 1, -6 + 50.8 - 9 + 3, -2,
                    "C", -6 + 50.8 - 9 + 3, -2, -6 + 50.8 - 9 + 1, 0, -6 + 50.8 - 9, 0,

                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat) {
                var right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 9, 0
                ];
            } else {
                var right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 50.8, -27,
                    "L", 50.8, -27 + 14.5,
                    "L", -6 + 50.8, -27 + 14.5,
                    "L", -6 + 50.8, 0,
                    "L", 9, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 50.8 - 9, -27,
                    "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                    "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                    "L", 50.8, -3,
                    "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                    "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                    "L", 50.8 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 50.8 - 9, -27,
                    "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                    "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                    "L", 50.8, -3,
                    "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                    "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                    "L", 50.8 - 9, 0,

                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8 - 7, -27,
                "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                "L", 50.8, -27 + 4,
                "L", 50.8 - 5, -27 + 4,
                "L", 50.8 - 5, -27 + 4 + 3,
                "L", 50.8 - 5 - 9, -27 + 4 + 3,
                "L", 50.8 - 5 - 9, -27 + 4,
                "L", 50.8 - 32, -27 + 4,
                "L", 50.8 - 32, -4,
                "L", 50.8 - 5 - 9, -4,
                "L", 50.8 - 5 - 9, -4 - 3,
                "L", 50.8 - 5, -4 - 3,
                "L", 50.8 - 5, -4,
                "L", 50.8, -4,
                "L", 50.8, -1,
                "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 7, 0,
                "C", 7, 0, 4.75, -2.5, 2.5, -1,
                "C", 2.5, -1, 1, 1, 0, -1,
                "L", 0, -4,
                "L", 5, -4,
                "L", 5, -4 - 3,
                "L", 5 + 9, -4 - 3,
                "L", 5 + 9, -4,
                "L", 32, -4,
                "L", 32, -27 + 4,
                "L", 5 + 9, -27 + 4,
                "L", 5 + 9, -27 + 4 + 3,
                "L", 5, -27 + 4 + 3,
                "L", 5, -27 + 4,
                "L", 0, -27 + 4,
                "L", 0, -27 + 1,
                "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 7, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");   //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8 - 7, -27,
                    "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                    "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                    "L", 50.8, -27 + 4,
                    "L", 50.8 - 5, -27 + 4,
                    "L", 50.8 - 5, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4,
                    "L", 50.8 - 32, -27 + 4,
                    "L", 50.8 - 32, -4,
                    "L", 50.8 - 5 - 9, -4,
                    "L", 50.8 - 5 - 9, -4 - 3,
                    "L", 50.8 - 5, -4 - 3,
                    "L", 50.8 - 5, -4,
                    "L", 50.8, -4,
                    "L", 50.8, -1,
                    "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                    "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -14.5,
                    "L", -6, -14.5,
                    "L", -6, -27,
                    "L", -6 + 50.8 - 7, -27,
                    "L", -6 + 50.8 - 7, -27,
                    "C", -6 + 50.8 - 7, -27, -6 + 50.8 - 4.75, -27 + 2.5, -6 + 50.8 - 2.5, -27 + 1,
                    "C", -6 + 50.8 - 2.5, -27 + 1, -6 + 50.8 - 1, -27 - 1, -6 + 50.8, -27 + 1,
                    "L", -6 + 50.8, -27 + 4,
                    "L", -6 + 50.8 - 5, -27 + 4,
                    "L", -6 + 50.8 - 5, -27 + 4 + 3,
                    "L", -6 + 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", -6 + 50.8 - 5 - 9, -27 + 4,
                    "L", -6 + 50.8 - 32, -27 + 4,
                    "L", -6 + 50.8 - 32, -4,
                    "L", -6 + 50.8 - 5 - 9, -4,
                    "L", -6 + 50.8 - 5 - 9, -4 - 3,
                    "L", -6 + 50.8 - 5, -4 - 3,
                    "L", -6 + 50.8 - 5, -4,
                    "L", -6 + 50.8, -4,
                    "L", -6 + 50.8, -1,
                    "C", -6 + 50.8, -1, -6 + 50.8 - 1, 1, -6 + 50.8 - 2.5, -1,
                    "C", -6 + 50.8 - 2.5, -1, -6 + 50.8 - 4.75, -2.5, -6 + 50.8 - 7, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat == true) {
                var right_path = [
                    "M", 7, 0,
                    "C", 7, 0, 4.75, -2.5, 2.5, -1,
                    "C", 2.5, -1, 1, 1, 0, -1,
                    "L", 0, -4,
                    "L", 5, -4,
                    "L", 5, -4 - 3,
                    "L", 5 + 9, -4 - 3,
                    "L", 5 + 9, -4,
                    "L", 32, -4,
                    "L", 32, -27 + 4,
                    "L", 5 + 9, -27 + 4,
                    "L", 5 + 9, -27 + 4 + 3,
                    "L", 5, -27 + 4 + 3,
                    "L", 5, -27 + 4,
                    "L", 0, -27 + 4,
                    "L", 0, -27 + 1,
                    "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                    "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 7, 0
                ];
            } else {
                var right_path = [
                    "M", 7, 0,
                    "C", 7, 0, 4.75, -2.5, 2.5, -1,
                    "C", 2.5, -1, 1, 1, 0, -1,
                    "L", 0, -4,
                    "L", 5, -4,
                    "L", 5, -4 - 3,
                    "L", 5 + 9, -4 - 3,
                    "L", 5 + 9, -4,
                    "L", 32, -4,
                    "L", 32, -27 + 4,
                    "L", 5 + 9, -27 + 4,
                    "L", 5 + 9, -27 + 4 + 3,
                    "L", 5, -27 + 4 + 3,
                    "L", 5, -27 + 4,
                    "L", 0, -27 + 4,
                    "L", 0, -27 + 1,
                    "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                    "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                    "L", -6 + 50.8, -27,
                    "L", -6 + 50.8, -27 + 14.5,
                    "L", 50.8, -27 + 14.5,
                    "L", 50.8, 0,
                    "L", 7, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8 - 7, -27,
                    "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                    "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                    "L", 50.8, -27 + 4,
                    "L", 50.8 - 5, -27 + 4,
                    "L", 50.8 - 5, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4,
                    "L", 50.8 - 32, -27 + 4,
                    "L", 50.8 - 32, -4,
                    "L", 50.8 - 5 - 9, -4,
                    "L", 50.8 - 5 - 9, -4 - 3,
                    "L", 50.8 - 5, -4 - 3,
                    "L", 50.8 - 5, -4,
                    "L", 50.8, -4,
                    "L", 50.8, -1,
                    "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                    "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8 - 7, -27,
                    "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                    "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                    "L", 50.8, -27 + 4,
                    "L", 50.8 - 5, -27 + 4,
                    "L", 50.8 - 5, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4,
                    "L", 50.8 - 32, -27 + 4,
                    "L", 50.8 - 32, -4,
                    "L", 50.8 - 5 - 9, -4,
                    "L", 50.8 - 5 - 9, -4 - 3,
                    "L", 50.8 - 5, -4 - 3,
                    "L", 50.8 - 5, -4,
                    "L", 50.8, -4,
                    "L", 50.8, -1,
                    "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                    "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            var louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            var right_path = [
                "M", 7, 0,
                "C", 7, 0, 4.75, -2.5, 2.5, -1,
                "C", 2.5, -1, 1, 1, 0, -1,
                "L", 0, -4,
                "L", 5, -4,
                "L", 5, -4 - 3,
                "L", 5 + 9, -4 - 3,
                "L", 5 + 9, -4,
                "L", 32, -4,
                "L", 32, -27 + 4,
                "L", 5 + 9, -27 + 4,
                "L", 5 + 9, -27 + 4 + 3,
                "L", 5, -27 + 4 + 3,
                "L", 5, -27 + 4,
                "L", 0, -27 + 4,
                "L", 0, -27 + 1,
                "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 7, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        //////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////// POSTS ////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        function drawHalfPost_Post50(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 50,
                "L", 9.53, 50,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    var path_b = ["M", 0, 50,
                        "L", 9.53, 50,
                        "L", 9.53, 50+buildoutHeight,
                        "L", -width/2, 50+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post70(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 70,
                "L", 9.53, 70,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    var path_b = ["M", 0, 70,
                        "L", 9.53, 70,
                        "L", 9.53, 70+buildoutHeight,
                        "L", -width/2, 70+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post50PVC(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;
            //        scale = 2;
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2,
                "C", 0, 2, -2.5, -2.5, -5, 2,
                "L", -6, 2,
                "L", -7, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 50,
                "L", 9.53, 50,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 2
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    var path_b = ["M", 0, 50,
                        "L", 9.53, 50,
                        "L", 9.53, 50+buildoutHeight,
                        "L", -width/2, 50+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post70PVC(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2,
                "C", 0, 2, -2.5, -2.5, -5, 2,
                "L", -6, 2,
                "L", -7, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 70,
                "L", 9.53, 70,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 2
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    var path_b = ["M", 0, 70,
                        "L", 9.53, 70,
                        "L", 9.53, 70+buildoutHeight,
                        "L", -width/2, 70+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_TPostBL90(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", -width / 2, 6,

                "M", -width / 2, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6 - 26,
                "L", 16, 59.5 + 30.5 - 6 - 26,
                // "L", 16, 30.5,
                "L", 16, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 20.5,
                "L", 16, 27.5 + 30.5 - 20.5,
                "L", 16, 30.5,

                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5, -11,
                "L", -5, -5,
                "L", -5 - 7.7, -5,
                "M", -5 - 7.7, -11,
                "L", -5, -11,

                "M", 16 + 10, 30.5 + 8,
                "L", 16 + 15, 30.5 + 8,
                "L", 16 + 15, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 8
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    var path_b = ["M", 0, 90,
                        "L", 16, 90,
                        "L", 16, 90+buildoutHeight,
                        "L", -width/2, 90+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_PostBL90(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6 - 26,
                "L", 16, 59.5 + 30.5 - 6 - 26,
                // "L", 16, 30.5,
                "L", 16, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 20.5,
                "L", 16, 27.5 + 30.5 - 20.5,
                "L", 16, 30.5,

                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -6,
                "L", -5, -6,
                "L", -5, 0,
                "L", -5 - 15.4, 0,
                "L", -5 - 15.4, -6,

                "M", 16 + 10, 30.5 + 8,
                "L", 16 + 15, 30.5 + 8,
                "L", 16 + 15, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 8
            ];

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        //NOTE: controlType= "Centre Rod", "Centre Rod Split", "Clearview", "Clearview Split", "Off Centre Rod", "Off
        // Centre Rod Split",

        var paper1;

        var fitWidth = 0;
        var fitHeight = 0;

        function shutterConfig() {
            var availableWidth = fitWidth;
            var availableHeight = fitHeight;

            var myShutter = {
                "buildoutHeight": getPropertyBuiltout(),
                "scale": 1,
                "frameType": getFrameTypeCode(),
                "frameTypeBottom": getFrameTypeCodeBottom(),
                "stileType": getStileCode(),
                "description": getShutterDescription(),
                "width": getPropertyWidth(),
                "height": getPropertyHeight(),
                "layoutCode": getPropertyLayoutCode(),
                "b_positions": getPropertyLayoutcodeExtra('bp'),
                "b_angles": getPropertyLayoutcodeExtra('ba'),
                "c_positions": getPropertyLayoutcodeExtra('c'),
                "t_positions": getPropertyLayoutcodeExtra('t'),
                "g_positions": getPropertyLayoutcodeExtra('g'),
                "louvreHeight": getPropertyBladesize(),
                "totHeight": getPropertyTotHeight(),
                "totPostChecked": getPropertyHorizontaltpost(),
                "midrails": getPropertyMidrailheight(), //set distance from bottom for each
                "midrails2": getPropertyMidrailheight2(), //set distance from bottom for each
                "midrailstotal": getPropertyMidrailtotal(), //set distance from bottom for each
                "midrailsdivider": getPropertyMidrailDivider(), //set distance from bottom for each
                "midrailsdivider2": getPropertyMidrailDivider2(), //set distance from bottom for each
                "midrailscombi": getPropertyMidrailCombiPanel(), //set distance from bottom for each
                "controlType": getPropertyControltype(),
                "splitHeight": getPropertyControlsplitheight(),
                "secondSplitHeight": getPropertyControlsplitheight2(), //use array [from(midrail), to(splitHeight)] eg.
                                                                       // [400, 600]
                "stileWidth": getPropertyStile(),
                "frameLeft": getPropertyFramePosition("left"),
                "frameRight": getPropertyFramePosition("right"),
                "frameTop": getPropertyFramePosition("top"),
                "frameBottom": getPropertyFramePosition("bottom"),
                "frameSize": 30,
                "frameImage": getFrameImageInformation(),
                "postsWidth": 30,
                "railHeight": getRailHeight(),
                "at": {
                    "x": 30,
                    "y": 52
                },
                "fit": {
                    "width": availableWidth,
                    "height": availableHeight
                },
            };

            return myShutter;
        }

        window.onload = function () {


            var currentWidth = Math.floor($("#accordion").width());
            var height = Math.floor(currentWidth * 1.2);

            fitWidth = currentWidth - 130;
            fitHeight = height / 2.0 - 100;
            paper1 = new Raphael(document.getElementById('canvas_container1'), currentWidth, height);

            var shutter = shutterConfig();

            $("#drawingConfig").val(JSON.stringify(shutter, null, 2));

            if ($("#canvas_container1 svg").length > 1) {
                console.log('delete svg length: ' + $("#canvas_container1 svg").length);
                $("#canvas_container1 > svg:first-child").remove();
            }

        };

        //update the shutter drawing only if variables have changed
        function updateShutter() {
            var currentWidth = Math.floor($("#canvas_container1").width());
            var height = Math.floor(currentWidth * 1.2);

            fitWidth = currentWidth - 230;
            fitHeight = height / 2.0 - 100;

            if ($("#canvas_container1 svg").length > 0) {
                $("#canvas_container1 svg").remove();
            }

            var style = getStyleTitle();
            if (style.indexOf("Shaped") >= 0) {
                $("#canvas_container1").html("&nbsp;Drawing not available for shaped shutters. Manual drawing will be issued.");
                $(".print-drawing").hide();
                return;
            } else {
                $("#canvas_container1").html("");
                $(".print-drawing").show();
            }

            paper1 = new Raphael(document.getElementById('canvas_container1'), currentWidth, height);

            var shutter_new_json = shutterConfig();
            var shutter_new_string = JSON.stringify(shutter_new_json, null, 2);

            //always redraw shutter because drawing is done on click
            $("#drawingConfig").val(shutter_new_string);
            drawing(paper1, shutter_new_json, true);

            var shutterScaled = scaleShutterConfig(shutter_new_json);
            $("#drawingConfigScaled").val(JSON.stringify(shutterScaled, null, 2));
        }


        function shutterInit(shutter) {
            if (shutter.midrails == null) {
                shutter.midrails = [];
            }
            if (shutter.midrails2 == null) {
                shutter.midrails2 = [];
            }
        };

        function drawPanels(rPaper, x, y, shutter) {
            var drawSet = [];
            var elem1 = null;
            var elem2 = null;
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();

            var xPanel = x;
            var yPanel = y;
            var totPostWidth = (shutter.totPostChecked ? shutter.frameSize : 1);

            var midRails = shutter.midrails.slice(); // copy array
            midRails.push(shutter.height - shutter.railHeight / 2.0);
            if (shutter.totHeight != null && shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                midRails.push(shutter.totHeight);
            }
            midRails.sort(sortNumber);


            var midRails2 = shutter.midrails2.slice(); // copy array
            midRails2.push(shutter.height - shutter.railHeight / 2.0);
            if (shutter.totHeight != null && shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                midRails2.push(shutter.totHeight);
            }
            midRails2.sort(sortNumber);
            for (var i = 0, len = layoutCode.length; i < len; i++) {
                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                    elem1 = drawPanelStiles(rPaper, xPanel, yPanel, panelWidth, shutter.stileWidth, shutter.height, layoutCode, i);
                    // var rect = rPaper.rect(xPanel, yPanel, panelWidth, shutter.height);
                    var fromHeight = shutter.railHeight;
                    for (var j = 0; j < midRails.length; j++) {
                        var toHeight = midRails[j] - shutter.railHeight / 2.0;
                        //var toHeight2 = midRails2[j] - shutter.railHeight / 2.0;
                        if (midRails[j] == shutter.totHeight) {
                            toHeight -= shutter.railHeight / 2.0 - totPostWidth / 2.0; //if this midrail is totHeight
                                                                                       // increase toHeight
                        }
                        if (j - 1 >= 0 && midRails[j - 1] == shutter.totHeight) {
                            fromHeight += shutter.railHeight + totPostWidth / 2.0; //if last midrail was totHeight
                                                                                   // increase fromHeight
                        }

                        [elem2, firstY, lastY] = drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter);

                        //[elemm2, firstYm2, lastYm2] = drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth,
                        // fromHeight, toHeight2, shutter);

                        if (j > 0 && (midRails[j] == shutter.totHeight || shutter.totHeight == 0)) {
                            elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter);
                        }

                        // // Deseneaza midrail2
                        // if (j > 0 && (midRails2[j] == shutter.totHeight || shutter.totHeight == 0)) {
                        //     elem_midrail2 = drawPanelMidrail(rPaper, xPanel, lastYm2, panelWidth, lastYm2,
                        // previousFirstYm2, shutter); }

                        elem3 = false;
                        if (shutter.controlType != '') {
                            elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);
                        }

                        previousFirstY = firstY;
                        //previousFirstYm2 = firstYm2;
                        fromHeight = toHeight + shutter.railHeight;
                    }
                    xPanel += panelWidth;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T' || layoutCode[i] == 'G') {

                    xPanel += shutter.postsWidth;
                }
                drawSet = drawSet.concat(elem1);
                drawSet = drawSet.concat(elem2);
                if (typeof elemMidrail !== 'undefined') {
                    drawSet = drawSet.concat(elemMidrail);
                }
                if (elem3) {
                    drawSet = drawSet.concat(elem3);
                }

            }


            // draw midrail2 Marian Desen Midrail2

            var drawSet = [];
            var elem1 = null;
            var elem2 = null;
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();

            var xPanel = x;
            var yPanel = y;
            var totPostWidth = (shutter.totPostChecked ? shutter.frameSize : 1);

            var midRails2 = shutter.midrails2.slice(); // copy array
            midRails2.push(shutter.height - shutter.railHeight / 2.0);
            if (shutter.totHeight != null && shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                //midRails2.push(shutter.totHeight);
            }
            midRails2.sort(sortNumber);
            for (var i = 0, len = layoutCode.length; i < len; i++) {
                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                    elem1 = drawPanelStiles(rPaper, xPanel, yPanel, panelWidth, shutter.stileWidth, shutter.height, layoutCode, i);
                    // var rect = rPaper.rect(xPanel, yPanel, panelWidth, shutter.height);
                    var fromHeight = shutter.railHeight;
                    for (var j = 0; j < midRails2.length; j++) {
                        var toHeight = midRails2[j] - shutter.railHeight / 2.0;
                        if (midRails2[j] == shutter.totHeight) {
                            toHeight -= shutter.railHeight / 2.0 - totPostWidth / 2.0; //if this midrail is totHeight
                                                                                       // increase toHeight
                        }
                        if (j - 1 >= 0 && midRails2[j - 1] == shutter.totHeight) {
                            fromHeight += shutter.railHeight + totPostWidth / 2.0; //if last midrail was totHeight
                                                                                   // increase fromHeight
                        }
                        // deseneaza linii
                        [elem2, firstY, lastY] = drawPanelLouvresMidrail2(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter);

                        // Deseneaza midrail2
                        if (j > 0 && (midRails2[j] == shutter.totHeight || shutter.totHeight == 0)) {
                            elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter);
                        }


                        elem3 = false;
                        if (shutter.controlType != '') {
                            elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);
                        }

                        previousFirstY = firstY;
                        fromHeight = toHeight + shutter.railHeight;
                    }
                    xPanel += panelWidth;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T' || layoutCode[i] == 'G') {

                    xPanel += shutter.postsWidth;
                }
                // drawSet = drawSet.concat(elem1);
                // drawSet = drawSet.concat(elem2);
                // if (typeof elemMidrail !== 'undefined') {
                //     drawSet = drawSet.concat(elemMidrail);
                // }
                // if (elem3) {
                //     drawSet = drawSet.concat(elem3);
                // }

            }


            // Draw ToT post
            if (shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                var yToT = shutter.at.y + shutter.height - shutter.totHeight - totPostWidth / 2;
                //            drawSet.push( drawRect(rPaper, shutter.at.x, yToT, shutter.width, totPostWidth, true,
                // true, true, true)  );
                var rect = rPaper.rect(shutter.at.x, yToT, shutter.width, totPostWidth);
                rect.attr("fill", "#fff");
                rect.attr("stroke", "#000");
            }
            // drawXLine(rPaper, x+0, y+shutter.fit.height/2.0, shutter.fit.width, {color:"#FF0000"});
            // drawYLine(rPaper, x+shutter.fit.width/2.0, y+0, shutter.fit.height, {color:"#FF0000"});
            return drawSet;
        };

        function sortNumber(a, b) {
            return a - b;
        };

        function drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter) {
            var drawSet = [];
            var height = toHeight - fromHeight;
            var louvreHeight = shutter.louvreHeight;
            if (louvreHeight == 0)
                louvreHeight = 10;

            var louvres = height / parseFloat(louvreHeight);
            var louvreWidth = panelWidth - 2 * shutter.stileWidth;
            var yLouvre = yPanel + shutter.height - toHeight;
            var firstYLouvre = yLouvre;

            for (var j = 0; j < louvres; j += 1) {
                var xLouvre = xPanel + shutter.stileWidth;
                var widthLouvre = louvreWidth;

                if (shutter.louvreHeight > 0) {
                    louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, louvreHeight);
                    //louvre.attr("stroke-width", "2"); //make the line look stronger
                    drawSet.push(louvre);
                } else {
                    if (j == 0) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, 1);
                    } else if (j + 1 == parseInt(louvres)) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre + louvreHeight, widthLouvre, 1);
                    }

                    //louvre.attr("stroke-width", "2"); //make the line look stronger
                    drawSet.push(louvre);
                }
                yLouvre += louvreHeight;
            }

            return [drawSet, firstYLouvre, yLouvre];
        };


        function drawPanelLouvresMidrail2(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter) {
            var drawSet = [];
            var height = toHeight - fromHeight;
            var louvreHeight = shutter.louvreHeight;
            if (louvreHeight == 0)
                louvreHeight = 10;

            var louvres = height / parseFloat(louvreHeight);
            var louvreWidth = panelWidth - 2 * shutter.stileWidth;
            var yLouvre = yPanel + shutter.height - toHeight;
            var firstYLouvre = yLouvre;

            for (var j = 0; j < louvres; j += 1) {
                var xLouvre = xPanel + shutter.stileWidth;
                var widthLouvre = louvreWidth;

                if (shutter.louvreHeight > 0) {
                    louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, louvreHeight);
                    louvre.attr("stroke-width", "0"); //make the line look stronger
                    drawSet.push(louvre);
                } else {
                    if (j == 0) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, 1);
                    } else if (j + 1 == parseInt(louvres)) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre + louvreHeight, widthLouvre, 1);
                    }

                    louvre.attr("stroke-width", "0"); //make the line look stronger
                    drawSet.push(louvre);
                }
                yLouvre += louvreHeight;
            }

            return [drawSet, firstYLouvre, yLouvre];
        };

        function drawPanelMidrail(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter) {
            var drawSet = [];
            var height = fromHeight - toHeight;

            var louvreWidth = panelWidth - 2 * shutter.stileWidth;
            var yLouvre = yPanel;

            var xLouvre = xPanel + shutter.stileWidth;
            var heightLouvre = toHeight - fromHeight;
            louvre = drawRect(rPaper, xLouvre, yLouvre, louvreWidth, heightLouvre);
            louvre.attr("stroke-width", "2"); //make the line look stronger
            drawSet.push(louvre);

            return drawSet;
        };

        function drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, panelType, shutter) {
            var drawSet = [];
            if (shutter.controlType != null && !(shutter.controlType.substring(0, "Concealed".length) === "Concealed") && !(shutter.controlType.substring(0, "Hidden".length) === "Hidden")) {
                var rodWidth = 3;
                var rodDistanceUp = 5;
                var splitDistance = shutter.louvreHeight;
                var rodHeight = toHeight - fromHeight;
                var louvres = rodHeight / parseFloat(shutter.louvreHeight);
                if (louvres > 0) {
                    var actualHeight = Math.ceil(louvres) * shutter.louvreHeight;
                    var louvreWidth = panelWidth - 2 * shutter.stileWidth;
                    var leftDistance = louvreWidth / 2.0; // "Centre Rod" is at the center
                    if (shutter.controlType.substring(0, "Offset Tilt Rod".length) === "Offset Tilt Rod") { //starts with "Offset Tilt Rod"
                        leftDistance = panelType == 'L' ? louvreWidth / 5.0 : louvreWidth * 4.0 / 5.0;
                    }
                    var xRod = xPanel + shutter.stileWidth + leftDistance;
                    var yRod = yPanel + shutter.height - toHeight;
                    var splitHeight1 = shutter.splitHeight;
                    var splitHeight2 = (shutter.secondSplitHeight != null && shutter.secondSplitHeight.length >= 2) ? shutter.secondSplitHeight[1] : null;
                    var selectedSplitHeight = null;
                    if (!((splitHeight1 == null ||
                            splitHeight1 <= shutter.railHeight ||
                            splitHeight1 >= shutter.height - shutter.railHeight) ||
                        !(splitHeight1 >= fromHeight - rodDistanceUp + splitDistance / 2.0 &&
                            splitHeight1 <= toHeight + rodDistanceUp - splitDistance / 2.0))) {
                        selectedSplitHeight = splitHeight1;
                    }
                    if (selectedSplitHeight == null &&
                        !((splitHeight2 == null ||
                                splitHeight2 <= shutter.railHeight ||
                                splitHeight2 >= shutter.height - shutter.railHeight) ||
                            !(splitHeight2 >= fromHeight - rodDistanceUp + splitDistance / 2.0 &&
                                splitHeight2 <= toHeight + rodDistanceUp - splitDistance / 2.0))) {
                        selectedSplitHeight = splitHeight2;
                    }
                    // draw rod
                    if (selectedSplitHeight == null) { //without split
                        drawSet.push(drawRect(rPaper, xRod, yRod - rodDistanceUp, rodWidth, actualHeight));
                    } else { //with split
                        var relativeSplitHeight = toHeight - selectedSplitHeight;
                        var upperPartHeight = relativeSplitHeight + rodDistanceUp - splitDistance / 2.0;
                        if (upperPartHeight >= splitDistance / 2.0) {
                            drawSet.push(drawRect(rPaper, xRod, yRod - rodDistanceUp, rodWidth, upperPartHeight));
                        }
                        var bottomPartHeight = actualHeight - relativeSplitHeight - rodDistanceUp - splitDistance / 2.0;
                        if (bottomPartHeight >= splitDistance / 2.0) {
                            drawSet.push(drawRect(rPaper, xRod, yRod + relativeSplitHeight + splitDistance / 2.0, rodWidth, bottomPartHeight));
                        }
                    }
                }
            }
            return drawSet;
        }

        function drawPanelStiles(rPaper, x, y, panelWidth, stileWidth, height, layoutCode, index) {
            var showLeft = true;
            var showRight = true;
            if (index > 0 && "BC".indexOf(layoutCode[index - 1]) >= 0) {
                showLeft = false;
            }
            if (index < layoutCode.length - 2 && "BC".indexOf(layoutCode[index + 1]) >= 0) {
                showRight = false;
            }
            var stileLeft = drawRect(rPaper, x, y, stileWidth, height,
                showLeft, false, true, false);
            var stileRight = drawRect(rPaper, x + panelWidth - stileWidth, y, stileWidth, height,
                true, false, showRight, false);
            // stileLeftRight.attr("stroke", "#FF0000");
            return [stileLeft, stileRight];
        };

        function drawShutterFrame(rPaper, x, y, shutter) {
            var rectPanel = drawRect(rPaper, x, y, shutter.width, shutter.height,
                shutter.frameLeft,
                shutter.frameTop,
                shutter.frameRight,
                shutter.frameBottom,
                shutter.frameSize);

            return [rectPanel];
        };

        function drawHeader(rPaper, shutter) {
            if (shutter.description) {
                rPaper.text(shutter.at.x - shutter.frameSize / 2, 10, shutter.description).attr({
                    'text-anchor': 'start',
                    "font-size": 12
                });
            }
        };

        function drawShutter(rPaper, shutter) {
            var x = shutter.at.x;
            var y = shutter.at.y;
            // console.log("Shutter: "+x+", "+y+", "+shutter.width+", "+shutter.height);

            var drawSet = [];
            var elem1 = drawShutterFrame(rPaper, x, y, shutter);
            var elem2 = drawPanels(rPaper, x, y, shutter);

            drawSet = drawSet.concat(elem1);
            drawSet = drawSet.concat(elem2);
            var set = rPaper.set(drawSet);
            // set.transform("s0.5");
            // set.attr("stroke", "#000000");
            // set.attr("fill", "#FF0000");
            // set.attr({"transform": "S0.8"});
            return drawSet;
        };

        function drawing(rPaper, shutterConfig, toScale) {
            shutterInit(shutterConfig);
            var shutter = shutterConfig;
            if (toScale) {
                shutter = scaleShutterConfig(shutterConfig);
            }
            rPaper.clear(); //clear everything
            drawHeader(rPaper, shutter);
            var maxYpos = drawTopView(rPaper, shutter);
            shutter.at.y = maxYpos + 100;
            maxYpos += 500 + shutter.height;
            $("#canvas_container1 svg").attr("height", (maxYpos));
            drawShutter(rPaper, shutter);
            drawRulers(rPaper, shutter, shutterConfig);
            drawFrameImage(rPaper, shutter);
            drawSideView(rPaper, shutter);
        }

        /*/////////////////////////////////////////////////////////////////////// */

        /*//////////////////////////// SCALING ////////////////////////////////// */
        function scaleShutterConfig(config, scale) {
            if (typeof scale == 'undefined') {
                var scale = getShutterScale(config);
            }
            if (config == null) {
                return null
            }
            ;
            // console.log("Object.prototype.toString.call(config) = "+Object.prototype.toString.call(config));
            if (Object.prototype.toString.call(config) == "[object String]") {
                return config;
            }
            if (Object.prototype.toString.call(config) == "[object Boolean]") {
                return config;
            }
            if (Object.prototype.toString.call(config) == "[object Number]") {
                return config * parseFloat(scale);
            }
            if (Object.prototype.toString.call(config) == "[object Array]") {
                var copyArray = [];
                for (var i = 0; i < config.length; i++) {
                    copyArray.push(scaleShutterConfig(config[i], scale));
                }
                return copyArray;
            }
            // console.log("scale = "+scale);

            var scaledShutter = jQuery.extend(true, {}, config); //deep copy object
            for (var propertyName in scaledShutter) {
                // console.log("unscaled:: "+propertyName);
                if (propertyName != "at" && propertyName != "fit" && propertyName != "b_angles" && propertyName != "buildoutHeight") {
                    scaledShutter[propertyName] = scaleShutterConfig(scaledShutter[propertyName], scale);
                    // console.log('scaled:: '+propertyName +" > "+scaledShutter[propertyName]);
                }
            }
            if ((typeof scaledShutter['frameSize'] != 'undefined') && scaledShutter['frameSize'] > 15) {
                scaledShutter['frameSize'] = 15;
            }
            return scaledShutter;
        };

        function getShutterScale(config) {
            var xScale = config.fit.width / parseFloat(config.width);
            var yScale = config.fit.height / parseFloat(config.height);
            return Math.min(xScale, yScale);
        }

        /*//////////////////////////// SCALING ////////////////////////////////// */
        /*/////////////////////////////////////////////////////////////////////// */


        /*/////////////////////////////////////////////////////////////////////// */

        /*////////////////////////// PANEL WIDTH //////////////////////////////// */
        function getPanelWidth(layoutCode, index, shutter) {
            if ("BCTG".indexOf(layoutCode[index]) >= 0) {
                return shutter.postsWidth;
            }

            var positionFrom = getPanelSpacePositionFrom(layoutCode, index, shutter);
            var positionTo = getPanelSpacePositionTo(layoutCode, index, shutter);
            var divideWith = getPanelSpaceDivideWith(layoutCode, index, shutter);
            // console.log("positionFrom: "+positionFrom);
            // console.log("positionTo: "+positionTo);
            // console.log("divideWith: "+divideWith);
            return (positionTo - positionFrom) / parseFloat(divideWith);
            //////////////////////////////////////////////////////
            // posts_count = (layoutCode.match(/B/g)||[]).length +
            //			   (layoutCode.match(/C/g)||[]).length +
            //			   (layoutCode.match(/T/g)||[]).length;
            // lr_count = (layoutCode.match(/L/g)||[]).length +
            //			(layoutCode.match(/R/g)||[]).length;
            // return (shutter.width - posts_count*shutter.postsWidth) / parseFloat(lr_count);
        };

        function getPanelSpacePositionFrom(layoutCode, index, shutter) {
            var bctPostIndex = -1;
            var b_posts_before = 0;
            var c_posts_before = 0;
            var t_posts_before = 0;
            var g_posts_before = 0;
            var lr_count_before = 0;
            for (var i = 0; i < index; i++) {
                if (layoutCode[i] == "B") {
                    b_posts_before++;
                }
                if (layoutCode[i] == "C") {
                    c_posts_before++;
                }
                if (layoutCode[i] == "T") {
                    t_posts_before++;
                }
                if (layoutCode[i] == "G") {
                    g_posts_before++;
                }
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    bctPostIndex = i;
                    lr_count_before = 0;
                } else {
                    lr_count_before++;
                }
            }
            var positionFrom = 0;
            if (bctPostIndex >= 0) {
                if (layoutCode[bctPostIndex] == "B") {
                    positionFrom = shutter.b_positions[b_posts_before - 1];
                }
                if (layoutCode[bctPostIndex] == "C") {
                    positionFrom = shutter.c_positions[c_posts_before - 1];
                }
                if (layoutCode[bctPostIndex] == "T") {
                    positionFrom = shutter.t_positions[t_posts_before - 1];
                }
                if (layoutCode[bctPostIndex] == "G") {
                    positionFrom = shutter.g_positions[g_posts_before - 1];
                }
                positionFrom += shutter.postsWidth / 2.0;
            }
            return positionFrom;
        };

        function getPanelSpacePositionTo(layoutCode, index, shutter) {
            var bctPostIndex = -1;
            var b_posts_after = 0;
            var c_posts_after = 0;
            var t_posts_after = 0;
            var g_posts_after = 0;
            for (var i = layoutCode.length; i > index; i--) {
                if (layoutCode[i] == "B") {
                    b_posts_after++;
                }
                if (layoutCode[i] == "C") {
                    c_posts_after++;
                }
                if (layoutCode[i] == "T") {
                    t_posts_after++;
                }
                if (layoutCode[i] == "G") {
                    g_posts_after++;
                }
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    bctPostIndex = i;
                }
            }
            var positionTo = shutter.width;
            if (bctPostIndex >= 0) {
                if (layoutCode[bctPostIndex] == "B") {
                    positionTo = shutter.b_positions[shutter.b_positions.length - b_posts_after];
                }
                if (layoutCode[bctPostIndex] == "C") {
                    positionTo = shutter.c_positions[shutter.c_positions.length - c_posts_after];
                }
                if (layoutCode[bctPostIndex] == "T") {
                    positionTo = shutter.t_positions[shutter.t_positions.length - t_posts_after];
                }
                if (layoutCode[bctPostIndex] == "G") {
                    positionTo = shutter.g_positions[shutter.g_positions.length - g_posts_after];
                }
                positionTo -= shutter.postsWidth / 2.0;
            }
            return positionTo;
        };

        function getPanelSpaceDivideWith(layoutCode, index, shutter) {
            var lr_count_before = 0;
            for (var i = index - 1; i >= 0; i--) {
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    break;
                }
                lr_count_before++;
            }
            var lr_count_after = 0;
            for (var i = index + 1; i < layoutCode.length; i++) {
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    break;
                }
                lr_count_after++;
            }
            return lr_count_before + 1 + lr_count_after;
        };
        /*////////////////////////// PANEL WIDTH //////////////////////////////// */
        /*/////////////////////////////////////////////////////////////////////// */


        /*/////////////////////////////////////////////////////////////////////// */

        /*///////////////////////// DRAW RECTANGLE ////////////////////////////// */
        function drawRect(rPaper, x, y, width, height,
                          left, top, right, bottom, size, color) {
            var left = typeof left !== 'undefined' ? left : true;
            var top = typeof top !== 'undefined' ? top : true;
            var right = typeof right !== 'undefined' ? right : true;
            var bottom = typeof bottom !== 'undefined' ? bottom : true;
            var size = size || "normal";
            // var color = typeof color !== 'undefined' ? color : "#FFFFFF";
            // var color = "#FF0000";
            // console.log("color: "+color+" size: "+size);

            if (size == "normal") {
                var line = rPaper.path(["M", x, y, //M for move, L for line
                    (left == true ? "L" : "M"), x, y + height,
                    (bottom == true ? "L" : "M"), x + width, y + height,
                    (right == true ? "L" : "M"), x + width, y,
                    (top == true ? "L" : "M"), x, y
                ]);
            } else {
                var t = size; //thickness

                //draw inside line first
                var line = rPaper.path(["M", x, y, //M for move, L for line
                    "L", x, y + height,
                    "L", x + width, y + height,
                    "L", x + width, y,
                    "L", x, y
                ]);
                line.attr("stroke-width", "2"); //make the line look stronger

                //Draw outer line one side at a time
                //draw left side
                var xLeft = (left == true ? x - t : x);
                var path = ["M", xLeft, y - t,
                    (top == true ? "L" : "M"), xLeft, y,
                    (left == true ? "L" : "M"), xLeft, y + height,
                    (bottom == true ? "L" : "M"), xLeft, y + height + t
                ];
                rPaper.path(path).attr("stroke-width", "2");
                //draw right side
                var xRight = (right == true ? x + width + t : x + width);
                var path = ["M", xRight, y - t,
                    (top == true ? "L" : "M"), xRight, y,
                    (right == true ? "L" : "M"), xRight, y + height,
                    (bottom == true ? "L" : "M"), xRight, y + height + t
                ];
                rPaper.path(path).attr("stroke-width", "2");
                //draw top side
                var yTop = (top == true ? y - t : y);
                var path = ["M", x - t, yTop,
                    (left == true ? "L" : "M"), x, yTop,
                    (top == true ? "L" : "M"), x + width, yTop,
                    (right == true ? "L" : "M"), x + width + t, yTop
                ];
                rPaper.path(path).attr("stroke-width", "2");
                //draw bottom side
                var yBottom = (bottom == true ? y + height + t : y + height);
                var path = ["M", x - t, yBottom,
                    (left == true ? "L" : "M"), x, yBottom,
                    (bottom == true ? "L" : "M"), x + width, yBottom,
                    (right == true ? "L" : "M"), x + width + t, yBottom
                ];
                rPaper.path(path).attr("stroke-width", "2");
            }
            return line;
        };
        /*///////////////////////// DRAW RECTANGLE ////////////////////////////// */

        /*/////////////////////////////////////////////////////////////////////// */

        function drawXLine(rPaper, x, y, width, options) {
            var defaultOptions = {
                distance: 0,
                gap: 0,
                color: "#000000",
                strokeWidth: null,
                strokeStyle: "",
                text: null, //text at the level of the line
                textUp: null, //text above the line
                textDown: null, //text below the line
                textAngle: null, //text below the line
                skipLine: false //skip line drawing, draw text only
            };
            options = jQuery.extend(true, defaultOptions, options);

            if (options.skipLine == false) {
                // // console.log("xLine x:"+x+" y:"+y+" width:"+width+" options.color:"+options.color+"
                // options.text:"+options.text);
                var line = rPaper.path(["M", x, y - options.distance,
                    "L", x, y, //M for move, L for line
                    "L", x + width / 2.0 - options.gap / 2.0, y,
                    "M", x + width / 2.0 + options.gap / 2.0, y,
                    "L", x + width, y,
                    "L", x + width, y - options.distance
                ]);
                line.attr("stroke", options.color).attr({
                    'stroke-dasharray': options.strokeStyle
                }); // http://stackoverflow.com/a/13884772/5562559
            }
            if (options.strokeWidth != null) {
                line.attr("stroke-width", options.strokeWidth);
            }
            if (options.text != null) {
                rPaper.text(x + width / 2.0, y, options.text);
            }
            if (options.textUp != null) {
                rPaper.text(x + width / 2.0, y - 8, options.textUp);
            }
            if (options.textDown != null) {
                rPaper.text(x + width / 2.0, y + 8, options.textDown);
            }
            if (options.textAngle != null) {
                rPaper.image('/wp-content/uploads/2021/03/angle.png', x + width - 55 / 2.0, y + 6, 17, 17);
                rPaper.text(x + width + 3 / 2.0, y + 16, options.textAngle).attr("font-size", "14");
            }
        };

        function drawYLine(rPaper, x, y, height, options) {
            var defaultOptions = {
                distance: 0,
                gap: 0,
                color: "#FFFFFF",
                strokeWidth: null,
                strokeStyle: "",
                text: null
            };
            options = jQuery.extend(true, defaultOptions, options);

            // console.log("yLine x:"+x+" y:"+y+" height:"+height+" options.color:"+options.color+"
            // options.text:"+options.text);
            var line = rPaper.path(["M", x - options.distance, y,
                "L", x, y, //M for move, L for line
                "L", x, y + height / 2.0 - options.gap / 2.0,
                "M", x, y + height / 2.0 + options.gap / 2.0,
                "L", x, y + height,
                "L", x - options.distance, y + height
            ]);
            line.attr("stroke", options.color).attr({
                'stroke-dasharray': options.strokeStyle
            }); // http://stackoverflow.com/a/13884772/5562559
            if (options.strokeWidth != null) {
                line.attr("stroke-width", options.strokeWidth);
            }
            if (options.text != null) {
                rPaper.text(x, y + height / 2.0, options.text)
            }
        };

        function drawPanelRulers(rPaper, x, y, shutter, originalShutter, leftFrameLength, rightFrameLength) {
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();

            var xFrom = x;
            var xPanel = x;
            var totalWidth = 0;
            var scaledTotalWidth = 0;
            var property_g = 0;
            var width_g = 0;

            var middleLR = findMiddleLRIndex(layoutCode, shutter);
            var widthMiddleLR = widthBeforeMiddleLR(layoutCode, middleLR, shutter);

            var b_pos = findMiddleBIndex(layoutCode, middleLR);
            var pos = {
                x: x + widthMiddleLR,
                y: y,
                angle: 180
            };

            for (var i = 0, len = layoutCode.length; i < len; i++) {
                var angle = 180;
                var scaledPanelWidth = getPanelWidth(layoutCode, i, shutter);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                    drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                        skipLine: true,
                        textUp: layoutCode[i]
                    });
                    scaledTotalWidth += scaledPanelWidth;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                    angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                    angle = (layoutCode[i] == 'C') ? 90 : angle;
                    var currentPostPosition = getPostPosition(layoutCode, i, originalShutter);
                    var panelsSpace = currentPostPosition - totalWidth;
                    drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                        skipLine: true,
                        textUp: layoutCode[i],
                        // textAngle: angle,
                    });
                    drawXLine(rPaper, xFrom - leftFrameLength, y, scaledTotalWidth + leftFrameLength, {
                        color: "#FF0000",
                        distance: 15,
                        textDown: panelsSpace,
                        strokeStyle: "-"
                    });
                    leftFrameLength = 0; //apply only on leftmost ruler
                    xFrom += scaledTotalWidth + scaledPanelWidth;
                    scaledTotalWidth = 0;
                    totalWidth = currentPostPosition;
                } else if (layoutCode[i] == "G") {
                    property_g++;
                    width_g = $('#property_g' + property_g).val();
                    var currentPostPosition = getPostPosition(layoutCode, i, originalShutter);
                    var panelsSpace = currentPostPosition - totalWidth;
                    // afisare G pe desen intre L si R
                    drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                        skipLine: true,
                        textUp: layoutCode[i],
                        // textDown: angle,
                    });
                    drawXLine(rPaper, xFrom - leftFrameLength, y, scaledTotalWidth + leftFrameLength, {
                        color: "#FF0000",
                        distance: 25,
                        textDown: panelsSpace,
                        strokeStyle: "-"
                    });
                    leftFrameLength = 0; //apply only on leftmost ruler
                    xFrom += scaledTotalWidth + scaledPanelWidth;
                    scaledTotalWidth = 0;
                    totalWidth = currentPostPosition;
                }
                xPanel += scaledPanelWidth;
            }
            var panelsSpace = originalShutter.width - totalWidth;
            drawXLine(rPaper, xFrom - leftFrameLength, y, scaledTotalWidth + leftFrameLength + rightFrameLength, {
                color: "#FF0000",
                distance: 15,
                textDown: panelsSpace,
                strokeStyle: "-"
            });
        };

        function getPostPosition(layoutCode, index, shutter) {
            if (index >= layoutCode.length) {
                return shutter.width;
            }
            var b_posts_before = 0;
            var c_posts_before = 0;
            var t_posts_before = 0;
            var g_posts_before = 0;
            for (var i = 0; i <= index; i++) {
                if (layoutCode[i] == "B") {
                    b_posts_before++;
                }
                if (layoutCode[i] == "C") {
                    c_posts_before++;
                }
                if (layoutCode[i] == "T") {
                    t_posts_before++;
                }
                if (layoutCode[i] == "G") {
                    g_posts_before++;
                }
            }
            var postPosition = 0;
            if (layoutCode[index] == "B") {
                postPosition = shutter.b_positions[b_posts_before - 1];
            }
            if (layoutCode[index] == "C") {
                postPosition = shutter.c_positions[c_posts_before - 1];
            }
            if (layoutCode[index] == "T") {
                postPosition = shutter.t_positions[t_posts_before - 1];
            }
            if (layoutCode[index] == "G") {
                postPosition = shutter.g_positions[g_posts_before - 1];
            }
            return postPosition;
        };

        function drawXRulers(rPaper, shutter, originalShutter) {
            var leftFrameLength = (shutter.frameLeft ? shutter.frameSize : 0);
            var rightFrameLength = (shutter.frameRight ? shutter.frameSize : 0);

            // Draw Top ruler
            var xLineYPosition = shutter.at.y - (shutter.frameTop ? shutter.frameSize : 2) - 10;
            var xLineXPosition = shutter.at.x - leftFrameLength;
            var xLineTotalWidth = shutter.width + leftFrameLength + rightFrameLength;
            drawXLine(rPaper, xLineXPosition, xLineYPosition, xLineTotalWidth, {
                color: "#FF0000",
                distance: -10,
                gap: 25,
                text: originalShutter.width
            });

            // Draw bottom rulers
            var panelRulersYPos = shutter.at.y + shutter.height + shutter.frameSize + 15;
            drawPanelRulers(rPaper, shutter.at.x, panelRulersYPos, shutter, originalShutter, leftFrameLength, rightFrameLength);
        };

        function drawYRulers(rPaper, shutter, originalShutter) {
            var topFrameLength = (shutter.frameTop ? shutter.frameSize : 0);
            var bottomFrameLength = (shutter.frameBottom ? shutter.frameSize : 0);

            // Draw total Height ruler
            var yLineXPosition = shutter.at.x + shutter.width + (shutter.frameRight ? shutter.frameSize : 2) + 17;
            var yLineYPosition = shutter.at.y - topFrameLength;
            var yLineTotalHeight = shutter.height + topFrameLength + bottomFrameLength;
            drawYLine(rPaper, yLineXPosition, yLineYPosition, yLineTotalHeight, {
                color: "#FF0000",
                distance: 17,
                gap: 15,
                text: originalShutter.height
            });


            // "totHeight": getPropertyTotHeight() //set distance from bottom for each
            // "midrails2": getPropertyMidrailheight2(), //set distance from bottom for each
            // "midrailsdivider": getPropertyMidrailDivider(), //set distance from bottom for each
            // "midrailsdivider2": getPropertyMidrailDivider2(), //set distance from bottom for each
            // "midrailscombi": getPropertyMidrailCombiPanel(), //set distance from bottom for each
            // Draw first midrail height divider - desen midrail custom marian
            if (shutter.totHeight !== 0) {
                var totHeight2 = 0;
                var totHeightposition2 = 0;
                //if (shutter.totHeight.length > 0 && shutter.totHeight[0] > 0 && shutter.totHeight[0] <
                // shutter.height) {

                yLineXPosition += 25;
                totHeight2 = shutter.totHeight;
                //totHeightposition2 = midRailYposition - totHeight2;
                totHeightposition2 = shutter.at.y + shutter.height - shutter.totHeight;
                console.log('shutter.at.y ' + shutter.at.y);
                console.log('shutter.height ' + shutter.height);
                console.log('getPropertyTotHeight ' + shutter.totHeight);


                drawYLine(rPaper, yLineXPosition, totHeightposition2, totHeight2 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "T-o-T:\n" + getPropertyTotHeight().toString(),
                    strokeStyle: "- "
                });
            }
            //}


            // Draw first midrail ruler
            var midRailPos = 0;
            var midRailYposition = 0;
            if (shutter.midrails.length > 0 && shutter.midrails[0] > 0 && shutter.midrails[0] < shutter.height) {
                yLineXPosition += 25;
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                drawYLine(rPaper, yLineXPosition, midRailYposition, midRailPos + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "midrail:\n" + originalShutter.midrails[0].toString(),
                    strokeStyle: "- "
                });
            }


            // "midrails": getPropertyMidrailheight(), //set distance from bottom for each
            // "midrails2": getPropertyMidrailheight2(), //set distance from bottom for each
            // "midrailsdivider": getPropertyMidrailDivider(), //set distance from bottom for each
            // "midrailsdivider2": getPropertyMidrailDivider2(), //set distance from bottom for each
            // "midrailscombi": getPropertyMidrailCombiPanel(), //set distance from bottom for each
            // Draw first midrail height divider - desen midrail custom marian
            var midRailPos2 = 0;
            var midRailYposition2 = 0;
            if (shutter.midrails2.length > 0 && shutter.midrails2[0] > 0 && shutter.midrails2[0] < shutter.height) {
                /////
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                /////
                yLineXPosition += 30;
                midRailPos2 = shutter.midrails2[0];
                //midRailYposition2 = midRailYposition - midRailPos2;
                midRailYposition2 = shutter.at.y + shutter.height - midRailPos2;

                drawYLine(rPaper, yLineXPosition, midRailYposition2, midRailPos2 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 45,
                    gap: 22,
                    text: "midrail 2:\n" + originalShutter.midrails2[0].toString(),
                    strokeStyle: "- "
                });
            }

            var midRailPos3 = 0;
            var midRailYposition2 = 0;
            if (shutter.midrailsdivider.length > 0 && shutter.midrailsdivider[0] > 0 && shutter.midrailsdivider[0] < shutter.height) {
                /////
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                /////
                yLineXPosition += 30;
                midRailPos3 = shutter.midrailsdivider[0];
                //midRailYposition2 = midRailYposition - midRailPos2;
                midRailYposition2 = shutter.at.y + shutter.height - midRailPos3;

                drawYLine(rPaper, yLineXPosition, midRailYposition2, midRailPos3 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 53,
                    gap: 22,
                    text: "divider:\n" + originalShutter.midrailsdivider[0].toString(),
                    strokeStyle: "- "
                });
            }

            var midRailPos4 = 0;
            var midRailYposition2 = 0;
            if (shutter.midrailsdivider2.length > 0 && shutter.midrailsdivider2[0] > 0 && shutter.midrailsdivider2[0] < shutter.height) {
                /////
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                /////
                yLineXPosition += 30;
                midRailPos4 = shutter.midrailsdivider2[0];
                //midRailYposition2 = midRailYposition - midRailPos2;
                midRailYposition2 = shutter.at.y + shutter.height - midRailPos4;

                drawYLine(rPaper, yLineXPosition, midRailYposition2, midRailPos4 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 81,
                    gap: 22,
                    text: "divider 2:\n" + originalShutter.midrailsdivider2[0].toString(),
                    strokeStyle: "- "
                });
            }


            // END Marian custom tot


            // Draw split Height rulers
            if (shutter.splitHeight != null && shutter.splitHeight > 0 && shutter.splitHeight < shutter.height) {
                var splitYposition = shutter.at.y + shutter.height - shutter.splitHeight;
                drawYLine(rPaper, yLineXPosition + 25, splitYposition, shutter.splitHeight + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "split:\n" + originalShutter.splitHeight.toString(),
                    strokeStyle: "- "
                });
            }
            if (shutter.secondSplitHeight != null && shutter.secondSplitHeight.length >= 2 && shutter.secondSplitHeight[1] > 0 && shutter.secondSplitHeight[1] < shutter.height) {
                var splitYposition = shutter.at.y + shutter.height - shutter.secondSplitHeight[1];
                var splitLength = shutter.secondSplitHeight[1] - shutter.secondSplitHeight[0];
                drawYLine(rPaper, yLineXPosition + 25, splitYposition, splitLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "split:\n" + originalShutter.secondSplitHeight[1].toString(),
                    strokeStyle: "- "
                });
            }
        };

        function drawRulers(rPaper, shutter, originalShutter) {

            drawXRulers(rPaper, shutter, originalShutter);

            drawYRulers(rPaper, shutter, originalShutter);
        };


        /*///////////////////////////////////////////////////////////////////////////////////////////////////// */
        /*/////////////////////////////////////////// TOP VIEW //////////////////////////////////////////////// */

        /*///////////////////////////////////////////////////////////////////////////////////////////////////// */


        function drawTopView(rPaper, shutterConfig, toScale) {
            shutterInit(shutterConfig);
            var shutter = shutterConfig;
            if (toScale) {
                shutter = scaleShutterConfig(shutterConfig);
            }
            var x = shutter.at.x;
            var y = 100;

            // drawTopRect(rPaper, {x: x+200, y: y, angle:180}, 100.0, 160.0, false, shutter);
            // drawTopRect(rPaper, {x: x+200, y: y, angle:180}, 100.0, 160.0, true, shutter);
            var maxYpos = drawTopPanels(rPaper, x, y, shutter);
            return maxYpos;
        };


        function drawFrameImage(rPaper, shutterConfig, toScale) {
            shutterInit(shutterConfig);
            var shutter = shutterConfig;
            if (toScale) {
                shutter = scaleShutterConfig(shutterConfig);
            }
            var x = shutter.at.x;
            var y = shutter.at.y + shutter.height + 50;
            var imageText = shutter.frameImage.text;
            var imagePath = shutter.frameImage.path;
            var img_src = '';
            $('input[name="property_frametype"]').each(function () {
                if (this.checked) {
                    console.log($(this).val());
                    img_src = $(this).parent().find('img').attr('src');
                }
            });

            //var img_src = $('input[name="property_frametype"]').is(":checked").parent().find('img').attr('src');


            rPaper.image(img_src, x, y, 100, 100);
            rPaper.text(x + 120, y + 50, "Frame:\n" + imageText).attr({
                'text-anchor': 'start'
            });

            var message = rPaper.text(shutter.at.x + shutter.fit.width / 2.0, y + 125, "* Drawing shown is an indication only. Actual product may vary.");

            message.attr("stroke-width", "0.5");
            message.attr("stroke", "#777777");

        };

        function findMiddleLRIndex(layoutCode, shutter) {
            var shutterWidth = shutter.width;
            width = 0;
            for (var i = 0, len = layoutCode.length; i < len; i++) {
                panelWidth = getPanelWidth(layoutCode, i, shutter);
                if ("LR".indexOf(layoutCode[i]) >= 0 && width + panelWidth >= shutterWidth / 2.0) {
                    return i;
                }
                width += panelWidth;
            }
            return 0;
        };

        function findMiddleBIndex(layoutCode, middleLR) {
            var b_index = -1;

            for (var i = 0; i < middleLR; i++) {
                if (layoutCode[i] == 'B') {
                    b_index++;
                }
            }
            return b_index;
        };

        function widthBeforeMiddleLR(layoutCode, middleLR, shutter) {
            var width = 0;
            for (var i = 0; i < middleLR; i++) {
                width += getPanelWidth(layoutCode, i, shutter);
            }
            return width;
        };

        function drawTopPanels(rPaper, x, y, shutter) {
            var scale = shutter.scale;
            //// console.log(scale);
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();
            //// console.log(layoutCode);
            var middleLR = findMiddleLRIndex(layoutCode, shutter);
            var widthMiddleLR = widthBeforeMiddleLR(layoutCode, middleLR, shutter);
            var positions = [];

            var b_pos = findMiddleBIndex(layoutCode, middleLR) + 1;
            var pos = {
                x: x + widthMiddleLR,
                y: y,
                angle: 180
            };
            positions.push(pos);

            //draw right panels
            var angle;
            for (var i = middleLR, len = layoutCode.length; i < len; i++) {

                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                var angle = 180;
                // // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R' || layoutCode[i] == 'G') {
                    var newPos = drawTopPanel_LR(rPaper, pos, panelWidth, angle, false, shutter, scale, i);
                    pos = newPos;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                    angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                    angle = (layoutCode[i] == 'C') ? 90 : angle;
                    var newPos = drawTopPanelPost(rPaper, pos, angle, false, scale, layoutCode[i], shutter.frameType, shutter.buildoutHeight);
                    pos = newPos;
                    b_pos += (layoutCode[i] == 'B') ? 1 : 0;
                    var scaledPanelWidth = getPanelWidth(layoutCode, i, shutter);
                    if (layoutCode[i] != 'T') {
                        drawXLine(rPaper, pos.x, pos.y - 50, scaledPanelWidth, {
                            skipLine: true,
                            // textUp: layoutCode[i],
                            textAngle: angle,
                        });
                    }
                }
                positions.push(pos);
            }
            if (shutter.frameRight) {
                drawFrame_by_type(shutter.frameType, rPaper, pos.x, pos.y, 180 - pos.angle, true, true, scale, shutter.buildoutHeight);
                positions.push(pos);
            }

            var b_pos = findMiddleBIndex(layoutCode, middleLR);
            var pos = {
                x: x + widthMiddleLR,
                y: y,
                angle: 180
            };

            for (var i = middleLR - 1; i >= 0; i--) {

                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                var angle = 180;
                // // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R' || layoutCode[i] == 'G') {
                    var newPos = drawTopPanel_LR(rPaper, pos, panelWidth, 180, true, shutter, scale, i);

                    pos = newPos;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                    angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                    angle = (layoutCode[i] == 'C') ? 90 : angle;
                    var newPos = drawTopPanelPost(rPaper, pos, angle, true, scale, layoutCode[i], shutter.frameType, shutter.buildoutHeight);
                    pos = newPos;
                    b_pos -= (layoutCode[i] == 'B') ? 1 : 0;
                    var scaledPanelWidth = getPanelWidth(layoutCode, i, shutter);
                    if (layoutCode[i] != 'T') {
                        drawXLine(rPaper, pos.x, pos.y - 50, scaledPanelWidth, {
                            skipLine: true,
                            // textUp: layoutCode[i],
                            textAngle: angle,
                        });
                    }
                }
                positions.push(pos);
            }
            if (shutter.frameLeft) {
                drawFrame_by_type(shutter.frameType, rPaper, pos.x, pos.y, pos.angle, true, false, scale, shutter.buildoutHeight);
                positions.push(pos);
            }

            var maxYPos = Math.max.apply(Math, positions.map(function (o) {
                return o.y;
            }));

            return maxYPos;
        };

        function getPostType(panelType, frameType) {

            if (frameType.indexOf('BL90') != -1 && (panelType == 'T')) {
                return 'TPostBL90';
            }

            if (frameType.indexOf('BL90') != -1 && (panelType == 'B' || panelType == 'C' || panelType == 'G')) {
                return 'PostBL90';
            }

            if (frameType.indexOf('PVC') > 0 && (panelType == 'T')) {
                if (frameType.indexOf('70') > 0) {
                    return 'Post70PVC';
                } else {
                    return 'Post50PVC';
                }
            }
            //else
            if (frameType.indexOf('70') > 0) {
                return 'Post70';
            } else {
                return 'Post50';
            }
        };

        function drawTopPanelPost(rPaper, pos, angle, trueForLeft, scale, panelType, frameType, buildoutHeight) {
            var width = 25.4;
            var postType = getPostType(panelType, frameType);
            if (postType == 'PostBL90') {
                width = width * 2;
            }

            //find the middle and end (down) position of the post
            var middlePos = findNextPos(pos, width / 2.0, 180, trueForLeft, scale);
            var endPos = findNextPos(middlePos, width / 2.0, angle, trueForLeft, scale);
            //draw two halves of the post, in the correct position/angle
            if (trueForLeft) {
                eval("drawHalfPost_" + postType + "(rPaper, pos, pos.angle, true, false, scale, buildoutHeight);");
                eval("drawHalfPost_" + postType + "(rPaper, endPos, endPos.angle, false, false, scale, buildoutHeight);");
            } else {
                eval("drawHalfPost_" + postType + "(rPaper, pos, 180-pos.angle, true, true, scale, buildoutHeight);");
                eval("drawHalfPost_" + postType + "(rPaper, endPos, 180-endPos.angle, false, true, scale, buildoutHeight);");
            }
            //Draw connector line of two halves:
            //find the middle and end (up) position of the post
            //draw the upper line of the post that connects the two half posts
            if (panelType != 'T' || panelType != 'G') {
                var height = (postType.indexOf('70') > 0) ? 70 : 50;
                if (postType.indexOf('70') > 0) {
                    height = 70;
                } else if (postType.indexOf('BL90') > 0) {
                    height = 90;
                } else {
                    height = 50;
                }
                var middlePosUp1 = findNextPos(middlePos, -height, 180 - 90, trueForLeft, scale);
                var middlePosUp2 = findNextPos(middlePos, -height, angle - 90, trueForLeft, scale);
                drawLineBetweenPos(rPaper, middlePosUp1, middlePosUp2);
                //Draw buildout if needed
                /*if (buildoutHeight && buildoutHeight > 0) {
                        height += buildoutHeight;
                        middlePosUp1 = findNextPos(middlePos, -height, 180-90, trueForLeft, scale);
                        middlePosUp2 = findNextPos(middlePos, -height, angle-90, trueForLeft, scale);
                        drawLineBetweenPos(rPaper, middlePosUp1, middlePosUp2);
                    }*/
            }

            return endPos;
        };

        function findNextPos(pos, width, angle, trueForLeft, scale) {
            var x = pos.x;
            var y = pos.y;
            angle = pos.angle - 180 + angle;

            var rotateAngle = angle;
            if (trueForLeft == false) {
                width *= -1;
                rotateAngle *= -1;
            }
            var newPos = {
                x: x - width * scale * Math.cos(toRad(180 - rotateAngle)),
                y: y + width * scale * Math.sin(toRad(180 - rotateAngle)),
                angle: angle
            };
            return newPos;
        }

        function drawLineBetweenPos(rPaper, pos, newPos) {
            var path = [
                "M", pos.x, pos.y,
                "L", newPos.x, newPos.y
            ];
            var line = rPaper.path(path);
            //        line.attr("stroke", "#FF0000");
        }


        function drawTopPanel_LR(rPaper, pos, width, angle, trueForLeft, shutter, scale, layoutIndex) {
            var height = 3;
            var x = pos.x;
            var y = pos.y;
            angle = pos.angle - 180 + angle;

            var rotateAngle = angle;

            if (trueForLeft == false) {
                width *= -1;
                rotateAngle *= -1;
            }

            var endPos = {
                x: x - width * Math.cos(toRad(180 - rotateAngle)),
                y: y + width * Math.sin(toRad(180 - rotateAngle)),
                angle: angle
            };
            // drawXLine(rPaper, endPos.x, endPos.y, shutter.width, {color:"#FF0000"});
            // drawYLine(rPaper, endPos.x, endPos.y, shutter.height, {color:"#FF0000"});
            var actualWidth = Math.abs(width / scale);
            var leftStileFlat = isLeftStileFlat(shutter, layoutIndex);
            var rightStileFlat = isRightStileFlat(shutter, layoutIndex);
            if (trueForLeft) {
                drawPanelStile_by_type(shutter.stileType, rPaper, endPos.x, endPos.y, 180 + rotateAngle, false, false, scale, actualWidth, leftStileFlat, rightStileFlat);
            } else {
                drawPanelStile_by_type(shutter.stileType, rPaper, pos.x, pos.y, 180 + rotateAngle, false, false, scale, actualWidth, leftStileFlat, rightStileFlat);
            }
            return endPos;
        };

        function isLeftStileFlat(shutter, layoutIndex) {
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();
            if (layoutIndex <= 0 || "BCTG".indexOf(layoutCode[layoutIndex - 1]) >= 0) {
                return true;
            }
            return false;
        };

        function isRightStileFlat(shutter, layoutIndex) {
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();
            if (layoutIndex >= layoutCode.length - 1 || "BCTG".indexOf(layoutCode[layoutIndex + 1]) >= 0) {
                return true;
            }
            return false;
        };

        function drawTopRect(rPaper, pos, width, angle, trueForLeft) {
            var height = 5;
            var x = pos.x;
            var y = pos.y;
            angle = pos.angle - 180 + angle;

            var rotateAngle = angle;
            if (trueForLeft == false) {
                width *= -1;
                rotateAngle *= -1;
            }

            var line = rPaper.path(["M", x, y,
                "L", x - width, y,
                "L", x - width, y - height,
                "L", x, y - height,
                "L", x, y
            ]);
            line.transform("r" + (180 + rotateAngle) + " " + x + " " + y); //Rotate!

            var newPos = {
                x: x - width * Math.cos(toRad(180 - rotateAngle)),
                y: y + width * Math.sin(toRad(180 - rotateAngle)),
                angle: angle
            };

            // drawXLine(rPaper, newPos.x, newPos.y, shutter.width, {color:"#FF0000"});
            // drawYLine(rPaper, newPos.x, newPos.y, shutter.height, {color:"#FF0000"});
            return newPos;
        };

        function toRad(degrees) {
            return degrees * Math.PI / 180.0;
        };

        function arc(center, radius, startAngle, endAngle) {
            angle = startAngle;
            coords = toCoords(center, radius, angle);
            path = "M " + coords[0] + " " + coords[1];
            while (angle <= endAngle) {
                coords = toCoords(center, radius, angle);
                path += " L " + coords[0] + " " + coords[1];
                angle += 1;
            }
            return path;
        };

        function toCoords(center, radius, angle) {
            var radians = (angle / 180) * Math.PI;
            var x = center[0] + Math.cos(radians) * radius;
            var y = center[1] + Math.sin(radians) * radius;
            return [x, y];
        };

        ///////////////////////////////////////////////////
        ///////////////////// FRAMES //////////////////////
        ///////////////////////////////////////////////////


        // Desen lateral distanta Marian

        function drawSideView(rPaper, shutter) {
            var x_pos = shutter.width + 180 + 100 * shutter.scale;
            var y_pos = shutter.at.y;

            var line = ["M", 0, 0,
                "L", -27 * shutter.scale, 0,
                "L", -27 * shutter.scale, shutter.height,
                "L", 0, shutter.height,
                "L", 0, 0
            ];
            pathRelocation(line, {
                "x": x_pos,
                "y": y_pos
            });
            rPaper.path(line);

            if (shutter.frameTop) {
                drawFrame_by_type(shutter.frameType, rPaper, x_pos, y_pos, 90, false, false, shutter.scale, shutter.buildoutHeight);
            }
            if (shutter.frameBottom) {
                drawFrame_by_type(shutter.frameTypeBottom, rPaper, x_pos, y_pos + shutter.height, 90, true, false, shutter.scale, shutter.buildoutHeight);
            }

        };

        function drawFrame_by_type(type, rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            if (typeof relocationPos !== "undefined") {
                eval("drawFrame_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos);");
            } else {
                eval("drawFrame_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight);");
            }
        };

        function drawPanelStile_by_type(type, rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            if (typeof relocationPos !== "undefined") {
                eval("drawPanelStile_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos);");
            } else {
                eval("drawPanelStile_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos);");
            }
        };

        function drawBuildoutPath(x, y, width, buildoutHeight) {
            var path_b = ["M", x, y,
                "L", x + width, y,
                "L", x + width, y + buildoutHeight,
                "L", x, y + buildoutHeight,
                "L", x, y
            ]
            return path_b;
        };

        function drawFrames_by_type(rPaper, type) {
            rPaper.text(rPaper.width / 2.0, 5, type);
            eval("drawFrame_" + type + "(rPaper, 100, 100, 0, false, false, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 100, 200, 0, false, false, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 100, 300, 0, false, false, 1.5);");

            eval("drawFrame_" + type + "(rPaper, 200, 100, 0, true, false, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 200, 200, 0, true, false, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 200, 300, 0, true, false, 1.5);");

            eval("drawFrame_" + type + "(rPaper, 300, 100, 0, true, true, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 300, 200, 0, true, true, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 300, 300, 0, true, true, 1.5);");

            eval("drawFrame_" + type + "(rPaper, 400, 100, 20, true, true, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 400, 200, 20, true, true, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 400, 300, 20, true, true, 1.5);");
        };

        function drawFrames_custom(path, rPaper) {
            drawFrame_custom(path, rPaper, 100, 100, 0, false, false, 1.0);
            drawFrame_custom(path, rPaper, 100, 200, 0, false, false, 0.5);
            drawFrame_custom(path, rPaper, 100, 300, 0, false, false, 1.5);

            drawFrame_custom(path, rPaper, 200, 100, 0, true, false, 1.0);
            drawFrame_custom(path, rPaper, 200, 200, 0, true, false, 0.5);
            drawFrame_custom(path, rPaper, 200, 300, 0, true, false, 1.5);

            drawFrame_custom(path, rPaper, 300, 100, 0, true, true, 1.0);
            drawFrame_custom(path, rPaper, 300, 200, 0, true, true, 0.5);
            drawFrame_custom(path, rPaper, 300, 300, 0, true, true, 1.5);

            drawFrame_custom(path, rPaper, 400, 100, 20, true, true, 1.0);
            drawFrame_custom(path, rPaper, 400, 200, 20, true, true, 0.5);
            drawFrame_custom(path, rPaper, 400, 300, 20, true, true, 1.5);
        }

        /* *** Helper functions *** */
        function drawCirle(rPaper, x, y, color) {
            var circle = rPaper.circle(x, y, 1);
            circle.attr("stroke", color);
            circle.attr("stroke-width", 2);
        }

        function transform_and_draw_path(rPaper, path, x, y, rotation, scaleX, scaleY, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            if (typeof relocationPos !== "undefined") {
                pathRelocation(path, relocationPos);
            }
            var line = rPaper.path(path);

            if (rotation != 0) {
                line.transform("r" + rotation + " " + x + " " + y);
            }
            line.translate(x, y);
            line.scale(scaleX, scaleY, 0, 0);
            // line.attr("stroke", "#0000FF");
            return line;
        };

        function pathRelocation(path, relocationPos) {
            var pathType = "y"; //can be "M","L","C","Z","x","y"
            var lastPathType = "y";
            for (var i = 0, pathLength = path.length; i < pathLength; i++) {
                pathType = path[i];
                // check if we have a letter path type
                if ("MLCZ".indexOf(path[i]) >= 0) {
                } else { // we have a coordinate x or y
                    //check if it is x or y
                    if ("MLCZ".indexOf(lastPathType) >= 0 || lastPathType == "y") { // it is a x
                        pathType = "x";
                        path[i] += relocationPos.x;
                    } else {
                        pathType = "y";
                        path[i] += relocationPos.y;
                    }
                }
                lastPathType = pathType;
                //            console.log(pathType + " = " + path[i]);
            }
        }

        function removeEmptyFromArray(actual) {
            var newArray = new Array();
            for (var i = 0; i < actual.length; i++) {
                var val = actual[i].trim();
                if (val != null && val != "" && val.indexOf('//') < 0) {
                    newArray.push(actual[i]);
                }
            }
            return newArray;
        }

        //the following functions are used in order to create the json for creating the drawings
        function getPropertyWidth() {
            return parseInt($("#property_width").val());
        }

        function getPropertyHeight() {
            return parseInt($("#property_height").val());
        }

        function getPropertyLayoutCode() {
            return $("#property_layoutcode").val();
        }

        function getPropertyHorizontaltpost() {
            if ($("#property_horizontaltpost").length > 0 && $("#property_horizontaltpost").prop("checked")) {
                return true;
            }
            return false;
        }

        function getPropertyStile() {
            if ($('input[name=property_stile]:checked').attr('data-title')) {
                return parseFloat($('input[name=property_stile]:checked').attr('data-title'));
            } else {
                return 0;
            }
        }

        function getPropertyFramePosition(position) {
            value = false;
            data = $("#property_frame" + position).select2('data');
            if (data && data.value) {
                if (data.value.indexOf("Yes") == 0 || data.value.indexOf("Sill") == 0) {
                    value = true;
                }
            }
            return value;
        }

        function getPropertyFramePositionText(position) {
            value = false;
            data = $("#property_frame" + position).select2('data');
            if (data && data.value) {
                value = data.value;
            }
            return value;
        }

        function getPropertyLayoutcodeExtra(extra_field) {
            var i = 1;
            var data = [];
            while ($("#property_" + extra_field + i).length > 0) {
                data.push(parseInt($("#property_" + extra_field + i).val()));
                i++;
            }

            return data;
        }

        function getFrameImageInformation() {
            chosenFrameType = $('input[type="radio"]:checked', '#choose-frametype');
            if (chosenFrameType.size() > 0) {
                return {
                    "text": chosenFrameType.parent().text().trim(),
                    "path": '/' + chosenFrameType.next().attr('src')
                };
            } else {
                return null;
            }
        }

        function getShutterDescription() {
            room = '';
            if ($("#property_room").val() == '94') {
                room = $("#property_room_other").val();
            } else {
                if ($("#property_room").select2('data') && $("#property_room").select2('data').value != 'undefined') {
                    room = $("#property_room").select2('data').value;
                }
            }
            if (room.length > 0)
                room = room + ' - ';

            return room + getPropertyLayoutCode().toUpperCase();
        }

        function getFrameTypeCode() {
            value = $('input[name=property_frametype]:checked').val();
            material_id = $("#property_material").select2('data').id;
            material_code = getCodeByPropertyValueId(material_id);

            code = getCodeByPropertyValueId(value);
            if (material_code == 'upvc') {
                code = code + "PVC";
            }
            return code;
        }

        function getFrameTypeCodeBottom() {
            value = $('input[name=property_frametype]:checked').val();
            material_id = $("#property_material").select2('data').id;
            material_code = getCodeByPropertyValueId(material_id);

            code = getCodeByPropertyValueId(value);
            if (material_code == 'upvc') {
                code = code + "PVC";
            }

            if (getPropertyFramePositionText("bottom") == 'Sill' && code.charAt(0) == 'Z') {
                code = 'L50';
            }

            return code;
        }

        function getStileCode() {
            //value = $("#property_stile").select2('data').id;
            value = $('input[name=property_stile]:checked').val();

            material_id = $("#property_material").select2('data').id;
            material_code = getCodeByPropertyValueId(material_id);

            code = getCodeByPropertyValueId(value);
            code = code.replace(".", "");
            code = code.replace(" ", "");
            if (material_code == 'upvc') {
                code = code + "PVC";
            }
            return code;
        }


        function getCodeByPropertyValueId(property_value_id) {
            code = '';
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].id == property_value_id) {
                    code = property_values[i].code;
                    break;
                }
            }
            return code;
        }

        function getRailHeight() {
            if (getPropertyBladesize() == 0) {
                return 60;
            } else {
                return 110;
            }
        }

    });
})(jQuery);
