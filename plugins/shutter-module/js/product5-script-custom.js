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


            function format(item) {
                var row = item.value;
                if (item.image_file_name !== 'undefined' && item.image_file_name !== null) {
                    row = "<span><img src='/uploads/property_values/images/" + item.id + "/thumb_" + item.image_file_name + "' height='44' width='44' /> " + row + "</span>";
                }

                return row;
            };

            var names = [{
                "id": 40,
                "name": "Batten",
                "description": "",
                "part_number": "Batten",
                "is_active": true,
                "status_id": 1,
                "category_id": 1,
                "promote_category": false,
                "promote_front": false,
                "price1": null,
                "price2": null,
                "price3": null,
                "created_at": "2015-09-30T13:12:52.000+01:00",
                "updated_at": "2015-09-30T13:20:41.000+01:00",
                "image_file_name": "batten.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 5665,
                "image_updated_at": "2015-09-30T13:18:36.000+01:00",
                "old_id": null,
                "minimum_quantity": "0.0",
                "product_type": "Batten",
                "vat_class_id": 1
            }];

            var property_values = [{
                "id": 165,
                "property_id": 8,
                "value": "114mm",
                "created_at": "2016-01-18T17:15:44.000+00:00",
                "updated_at": "2016-05-18T17:35:18.000+01:00",
                "code": "",
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
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 164,
                "property_id": 8,
                "value": "47mm",
                "created_at": "2016-01-18T17:15:16.000+00:00",
                "updated_at": "2016-02-22T09:52:32.000+00:00",
                "code": "",
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
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 53,
                "property_id": 8,
                "value": "63mm",
                "created_at": "2015-09-07T20:05:51.000+01:00",
                "updated_at": "2018-03-02T00:29:12.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"292\"]}",
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
            }, {
                "id": 54,
                "property_id": 8,
                "value": "76mm",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-03-02T00:29:29.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"292\"]}",
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
            }, {
                "id": 55,
                "property_id": 8,
                "value": "89mm",
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
            }, {
                "id": 97,
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
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
                "id": 99,
                "property_id": 16,
                "value": "Centre Rod Split",
                "created_at": "2015-09-30T16:33:37.000+01:00",
                "updated_at": "2017-11-27T12:03:57.000+00:00",
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
                "id": 96,
                "property_id": 16,
                "value": "Clearview",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
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
                "id": 95,
                "property_id": 16,
                "value": "Clearview Split",
                "created_at": "2015-09-26T01:28:11.000+01:00",
                "updated_at": "2015-09-30T16:31:24.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":[\"2\"]}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
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
                "id": 223,
                "property_id": 16,
                "value": "Hidden Tilt",
                "created_at": "2017-07-21T02:51:01.000+01:00",
                "updated_at": "2017-11-27T12:04:38.000+00:00",
                "code": "",
                "uplift": "15.0",
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
                "id": 224,
                "property_id": 16,
                "value": "Hidden Tilt Split",
                "created_at": "2017-07-21T02:51:15.000+01:00",
                "updated_at": "2017-11-27T12:04:28.000+00:00",
                "code": "",
                "uplift": "15.0",
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
                "id": 98,
                "property_id": 16,
                "value": "Off Centre Rod",
                "created_at": "2015-09-26T01:29:43.000+01:00",
                "updated_at": "2017-11-27T12:04:18.000+00:00",
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
                "id": 100,
                "property_id": 16,
                "value": "Off Centre Rod Split",
                "created_at": "2015-09-30T16:34:18.000+01:00",
                "updated_at": "2017-11-27T12:04:08.000+00:00",
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
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"221\",\"227\",\"226\",\"222\",\"228\",\"31\",\"35\"]}",
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
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"221\",\"227\",\"226\",\"222\",\"228\",\"31\",\"35\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
                "id": 144,
                "property_id": 10,
                "value": "Bottom M Track",
                "created_at": "2015-10-22T09:26:58.000+01:00",
                "updated_at": "2016-06-12T21:40:12.000+01:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"35\"]}",
                "graphic": "image",
                "image_file_name": "Bottom_M_Track.png",
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
            }, {
                "id": 142,
                "property_id": 10,
                "value": "D50",
                "created_at": "2015-10-21T19:41:49.000+01:00",
                "updated_at": "2016-06-12T21:35:36.000+01:00",
                "code": "D50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "D50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 4364,
                "image_updated_at": "2015-10-21T19:41:48.000+01:00",
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
                "id": 61,
                "property_id": 10,
                "value": "F50",
                "created_at": "2015-09-07T20:37:53.000+01:00",
                "updated_at": "2016-06-12T21:35:38.000+01:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "image",
                "image_file_name": "F50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 4141,
                "image_updated_at": "2015-10-21T19:07:06.000+01:00",
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
                "id": 62,
                "property_id": 10,
                "value": "F70",
                "created_at": "2015-09-07T20:38:37.000+01:00",
                "updated_at": "2016-06-12T21:35:46.000+01:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "image",
                "image_file_name": "F70.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3826,
                "image_updated_at": "2015-10-21T19:07:20.000+01:00",
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
                "id": 216,
                "property_id": 10,
                "value": "F90",
                "created_at": "2016-12-10T15:56:47.000+00:00",
                "updated_at": "2017-12-01T14:22:20.000+00:00",
                "code": "F90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "F90_square.png",
                "image_content_type": "image/png",
                "image_file_size": 17228,
                "image_updated_at": "2016-12-10T15:56:46.000+00:00",
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
                "id": 59,
                "property_id": 10,
                "value": "L50",
                "created_at": "2015-09-07T20:35:24.000+01:00",
                "updated_at": "2018-02-03T01:34:19.000+00:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\",\"292\"]}",
                "graphic": "image",
                "image_file_name": "L50.jpg",
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
            }, {
                "id": 173,
                "property_id": 10,
                "value": "L50MF",
                "created_at": "2016-07-02T23:01:07.000+01:00",
                "updated_at": "2016-07-05T12:34:38.000+01:00",
                "code": "L50MF",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "l50mf.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 50499,
                "image_updated_at": "2016-07-05T12:34:37.000+01:00",
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
                "id": 141,
                "property_id": 10,
                "value": "L60SF",
                "created_at": "2015-10-21T19:41:28.000+01:00",
                "updated_at": "2016-06-12T21:35:58.000+01:00",
                "code": "L60SF",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "L60-SF.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3667,
                "image_updated_at": "2015-10-21T19:41:27.000+01:00",
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
                "id": 60,
                "property_id": 10,
                "value": "L70",
                "created_at": "2015-09-07T20:36:11.000+01:00",
                "updated_at": "2017-11-27T14:03:14.000+00:00",
                "code": "L70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "L70.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3371,
                "image_updated_at": "2015-10-21T19:09:40.000+01:00",
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
                "id": 150,
                "property_id": 10,
                "value": "L90",
                "created_at": "2015-11-15T19:16:18.000+00:00",
                "updated_at": "2016-06-14T09:25:30.000+01:00",
                "code": "L90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "L90.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3371,
                "image_updated_at": "2015-11-15T19:16:17.000+00:00",
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
                "id": 63,
                "property_id": 10,
                "value": "SBS 50",
                "created_at": "2015-09-07T20:39:28.000+01:00",
                "updated_at": "2016-06-12T21:38:52.000+01:00",
                "code": "SBS50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "SBS-50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3759,
                "image_updated_at": "2015-10-21T19:12:11.000+01:00",
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
                "id": 143,
                "property_id": 10,
                "value": "Track in Board",
                "created_at": "2015-10-22T09:26:04.000+01:00",
                "updated_at": "2016-06-12T21:40:08.000+01:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"35\"]}",
                "graphic": "image",
                "image_file_name": "Track_in_Board.png",
                "image_content_type": "image/png",
                "image_file_size": 5378,
                "image_updated_at": "2015-10-22T09:32:58.000+01:00",
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
                "id": 67,
                "property_id": 10,
                "value": "Z2BS",
                "created_at": "2015-09-07T20:44:18.000+01:00",
                "updated_at": "2016-06-12T21:39:01.000+01:00",
                "code": "Z2BS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "image",
                "image_file_name": "Z2BS.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3975,
                "image_updated_at": "2015-10-21T19:14:38.000+01:00",
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
                "id": 66,
                "property_id": 10,
                "value": "Z3CS",
                "created_at": "2015-09-07T20:43:15.000+01:00",
                "updated_at": "2016-06-12T21:39:08.000+01:00",
                "code": "Z3CS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":[\"2\",\"3\",\"4\"]}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "image",
                "image_file_name": "Z3CS.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 4190,
                "image_updated_at": "2015-10-21T19:15:18.000+01:00",
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
                "id": 64,
                "property_id": 10,
                "value": "Z40",
                "created_at": "2015-09-07T20:40:10.000+01:00",
                "updated_at": "2016-06-12T21:39:13.000+01:00",
                "code": "Z40",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                "graphic": "image",
                "image_file_name": "Z40.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3261,
                "image_updated_at": "2015-10-21T19:15:30.000+01:00",
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
                "id": 140,
                "property_id": 10,
                "value": "Z40SF",
                "created_at": "2015-10-21T19:41:09.000+01:00",
                "updated_at": "2016-06-12T21:39:18.000+01:00",
                "code": "Z40SF",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
                "graphic": "image",
                "image_file_name": "Z40-SF.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3413,
                "image_updated_at": "2015-10-21T19:41:07.000+01:00",
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
                "id": 65,
                "property_id": 10,
                "value": "Z50",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\",\"292\"]}",
                "graphic": "image",
                "image_file_name": "Z50.jpg",
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
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
                "value": "Nickel",
                "created_at": "2016-02-25T09:16:36.000+00:00",
                "updated_at": "2016-04-01T23:26:38.000+01:00",
                "code": "",
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
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"187\",\"188\"]}",
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
                "value": "Stainless Steel",
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
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
                }, {
                    "id": 137,
                    "property_id": 18,
                    "value": "Green",
                    "created_at": "2015-10-19T20:31:42.000+01:00",
                    "updated_at": "2015-10-19T20:31:42.000+01:00",
                    "code": "upvc",
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
                    "id": 172,
                    "property_id": 20,
                    "value": "-",
                    "created_at": "2016-06-16T13:59:23.000+01:00",
                    "updated_at": "2016-06-16T13:59:23.000+01:00",
                    "code": "-",
                    "uplift": "0.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\"]}",
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
                    "id": 405,
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
                    "value": "LS 602 SILK WHITE",
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
                    "value": "LS 910 JET BLACK",
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
                    "value": "LS 108 RUSTIC GREY",
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
                    "value": "LS 109 WEATHERED TEAK",
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
                    "value": "LS 110 CHIQUE WHITE",
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
                    "value": "LS 114 TAUPE",
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
                    "value": "LS 202 GOLDEN OAK",
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
                    "value": "LS 204 OAK MANTEL",
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
                    "value": "LS 205 GOLDENROD",
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
                    "value": "LS 211 CHERRY",
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
                    "value": "LS 212 DARK TEAK",
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
                    "value": "LS 214 COCOA",
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
                    "value": "LS 215 CORDOVAN",
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
                    "value": "LS 219 MAHOGANY",
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
                    "value": "LS 220 NEW EBONY",
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
                    "value": "LS 221 BLACK WALNUT",
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
                    "value": "LS 227 RED OAK",
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
                    "value": "LS 229 RICH WALNUT",
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
                    "value": "LS 230 OLD TEAK",
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
                    "value": "LS 232 RED MAHOGANY",
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
                    "value": "LS 237 WENGE",
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
                    "value": "LS 862 FRENCH OAK",
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
                    "value": "A103 (PEARL)",
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
                    "value": "A202 (LIGHT CEDAR)",
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
                    "value": "A203 (GOLDEN OAK )",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
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
                }, {
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
                    "id": 30,
                    "property_id": 7,
                    "value": "Café Style",
                    "created_at": "2015-09-07T19:22:28.000+01:00",
                    "updated_at": "2018-02-03T01:30:59.000+00:00",
                    "code": "cafe",
                    "uplift": "0.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
                    "graphic": "image",
                    "image_file_name": "Cafe_Style.png",
                    "image_content_type": "image/png",
                    "image_file_size": 42727,
                    "image_updated_at": "2015-10-04T21:09:25.000+01:00",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
                    "id": 33,
                    "property_id": 7,
                    "value": "Shaped \u0026 French Cut Out",
                    "created_at": "2016-01-06T09:03:52.000+00:00",
                    "updated_at": "2017-11-27T13:50:52.000+00:00",
                    "code": "shaped",
                    "uplift": "0.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
                    "graphic": "image",
                    "image_file_name": "Shaped_and_French_Cut_Out.png",
                    "image_content_type": "image/png",
                    "image_file_size": 55123,
                    "image_updated_at": "2015-10-04T21:10:12.000+01:00",
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
                    "id": 221,
                    "property_id": 7,
                    "value": "Solid Flat Panel",
                    "created_at": "2017-07-13T16:49:43.000+01:00",
                    "updated_at": "2017-07-13T16:49:43.000+01:00",
                    "code": "solid-flat",
                    "uplift": "0.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                    "graphic": "image",
                    "image_file_name": "Solid_Flat_Panel.png",
                    "image_content_type": "image/png",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
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
                    "id": 222,
                    "property_id": 7,
                    "value": "Solid Raised\u003cbr/\u003ePanel",
                    "created_at": "2017-07-13T16:50:20.000+01:00",
                    "updated_at": "2017-07-13T16:57:36.000+01:00",
                    "code": "solid-raised",
                    "uplift": "0.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                    "graphic": "image",
                    "image_file_name": "Solid_Raised_Panels.png",
                    "image_content_type": "image/png",
                    "image_file_size": 49290,
                    "image_updated_at": "2017-07-13T16:50:19.000+01:00",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
                    "id": 35,
                    "property_id": 7,
                    "value": "Tracked",
                    "created_at": "2016-01-06T09:03:52.000+00:00",
                    "updated_at": "2018-02-03T01:31:22.000+00:00",
                    "code": "tracked",
                    "uplift": "0.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
                    "graphic": "image",
                    "image_file_name": "Tracked.png",
                    "image_content_type": "image/png",
                    "image_file_size": 108320,
                    "image_updated_at": "2015-10-04T21:10:38.000+01:00",
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
                }];
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
            }];


            for (i = 0; i < property_values.length; i++) {
                if (property_values[i].all_property_values == 0) {
                    selected_property_values = JSON.parse(property_values[i].selected_property_values)
                    for (j = 0; j < selected_property_values.property_value_ids.length; j++) {
                        //alert(selected_property_values.property_value_ids[j]);
                    }
                    break;
                }
            }

            $(".property-select").each(function () {
                var id = $(this).attr('id');
                var property_id = getPropertyIdByCode(id);
                values = getAllFieldData(property_id);
                loadItems(id, values);
                console.log("Loaded values for element with id: " + id + " and property_id: " + property_id);
            });


            $('.property-select').on('change', function () {
                id = $(this).attr('id');
                field_id = getPropertyIdByCode(id);
                related_fields = getRelatedFields(field_id);

                for (var i = 0; i < related_fields.length; i++) {
                    field_data = getRelatedFieldData(related_fields[i], field_id, $(this).val());
                    property_code = getPropertyCodeById(related_fields[i]);
                    //// console.log("Loading to " + property_code + " data: " + field_data);


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
                //// console.log("Length: " + $("#choose-frametype label").filter(":visible").length);
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
                }

                if ($("#canvas_container1").filter(":visible").length > 0) {
                    updateShutter();
                }
            });


            $(".property-select").css('width', '100%');

            $('#property_width, #property_height, #property_depth').change(function () {
                calculateTotal();
            });

            $('input[name="batten_type"]').change(function () {
                console.log('change type');
                calculateTotal();
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

            function calculateTotal() {

                // custom Marian batten
                console.log('asdasd');
                //     var type = $('input[name="batten_type"]:checked').val();
                //     console.log('type:' + type);

                var height = $('#property_height').val();
                var width = $('#property_width').val();
                var depth = $('#property_depth').val();

                var material_id = jQuery("#property_material").val();

                if (material_id == 188 || material_id == 137) {
                    // if material - 139
                    //biowood-138, supreme-139, earth-187, ecowood-188, green-137
                    if (height < 3) {
                        height = 3;
                    }
                    if (width < 3) {
                        width = 3;
                    }
                    if (depth < 3) {
                        depth = 3;
                    }
                } else {
                    if (height < 5) {
                        height = 5;
                    }
                    if (width < 5) {
                        width = 5;
                    }
                    if (depth < 5) {
                        depth = 5;
                    }
                }


                console.log(width);
                console.log(height);
                console.log(depth);

                if (parseFloat(height) > parseFloat(width) && parseFloat(height) > parseFloat(depth)) {
                    console.log('height');

                    var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));

                } else if (parseFloat(width) > parseFloat(height) && parseFloat(width) > parseFloat(depth)) {
                    console.log('width');

                    var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));

                } else if (parseFloat(depth) > parseFloat(height) && parseFloat(depth) > parseFloat(width)) {

                    var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));

                }

                // var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));

                // total = total.toFixed(7);

                console.log('total: ' + total);

                $('input#property_total').val(parseFloat(total).toFixed(7));
                //  }


                //
                //     total = $("#property_width").val() * $("#property_height").val();
                //     if (isNaN(total) || (1 * $("#property_depth").val()) == 'NaN') {
                //         total = 0;
                //     } else {
                //         total = (parseFloat($("#property_width").val()) / parseFloat(1000)) * (parseFloat($("#property_height").val()) / parseFloat(1000)) * (parseFloat($("#property_depth").val()) / parseFloat(1000))
                //         total = total.toFixed(7);
                //     }
                //     if (isNaN(total)) total = 0;
                //     $("#property_total").val(parseFloat(total));

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

            //get the property code based on id of property eg: property with id 9 = property_fit
            function getPropertyCodeById(id) {
                code = '';
                for (i = 0; i < property_fields.length; i++) {
                    if (property_fields[i].id == id) {
                        code = property_fields[i].code;
                    }
                }
                return code;
            }


            //get all field data
            function getAllFieldData(property_id) {
                data = [];
                for (var i = 0; i < property_values.length; i++) {
                    if (property_values[i].property_id == property_id) {
                        data.push(property_values[i]);
                    }

                }
                return data;
            }

            calculateTotal();
            chooseOneBattenIfNoneChosen();

            function addError(field_id, error) {
                if ($("#" + field_id).prev().find('.select2-choice').length > 0) {
                    $("#" + field_id).prev().addClass("error-field");
                    $("#" + field_id).prev().css('display', 'block');
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).prev());
                } else {
                    $("#" + field_id).addClass("error-field");
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id));
                }
            }

            function resetErrors() {
                $(".error-field").removeClass("error-field");
                $("span.error-text").remove();
            }

            function chooseOneBattenIfNoneChosen() {
                if ($("input[name=product_id]:checked").length == 0) {
                    if ($("input[name=product_id]").length > 0) {
                        $("input[name=product_id]")[0].checked = true; //choose first one
                    }
                }
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

            $("#add-product-single-form").submit(function () {
                errors = 0;
                $(".select2-container").removeClass("error-field");
                $(".error-text").remove();
                if ($("input[name=product_id]:checked").length == 0) {
                    errors++;
                    $("<span class=\"error-text\">Please select a product</span>").insertAfter($("#choose-product-shutter"));
                }
                $(".required").each(function () {
                    if ($(this).val() == '') {
                        errors++;
                        addError($(this).attr('id'), 'Please fill in this field');
                    }
                });

                $("input.property-select").each(function () {
                    if ($(this).val() == '') {
                        errors++;
                        addError($(this).attr('id'), 'Please fill in this field');
                    }
                });


                if ($("#property_productquantity").val() != '' && parseFloat($("#property_height").val()) < 1) {
                    errors++;
                    addError("property_productquantity", 'Quantity should be greater than 0');
                }

                if (parseFloat($("#property_height").val()) < 3) {
                    errors++;
                    addError("property_height", 'The minimum size of the batten can not be less than 3mm.');
                }
                if (parseFloat($("#property_width").val()) < 3) {
                    errors++;
                    addError("property_width", 'The minimum size of the batten can not be less than 3mm.');
                }
                if (parseFloat($("#property_depth").val()) < 3) {
                    errors++;
                    addError("property_depth", 'The minimum size of the batten can not be less than 3mm.');
                }

                //we must look for the longest/biggest field of the bellow
                //largest value is the length of the batten, based on the length of the batten there are restrictions to the other two
                //dimensions
                largest_field = '';
                largest_field_value = 0;
                var fields = ['property_width', 'property_height', 'property_depth'];
                for (var i = 0; i < fields.length; i++) {
                    for (var j = 0; j < fields.length; j++) {
                        if (i != j) {
                            compare_a = parseFloat($("#" + fields[i]).val());
                            compare_b = parseFloat($("#" + fields[j]).val());
                            if (compare_a >= compare_b && compare_a >= largest_field_value) {
                                largest_field = fields[i];
                                largest_field_value = compare_a;
                            }
                        }
                    }
                }

                //if length<2500, then the other two dimensions should be more than 3
                if (largest_field_value < 2500) {
                    for (var i = 0; i < fields.length; i++) {
                        value = parseFloat($("#" + fields[i]).val());
                        if (value < 3) {
                            errors++;
                            addError(fields[i], 'Value should be more than 3mm');
                        }
                    }
                }

                //if length>=2500, then the other two dimensions should be more than 5
                if (largest_field_value >= 2500) {
                    for (var i = 0; i < fields.length; i++) {
                        value = parseFloat($("#" + fields[i]).val());
                        if (value < 5) {
                            errors++;
                            addError(fields[i], 'Value should be more than 5mm');
                        }
                    }
                }

                if (errors > 0) {
                    return false;
                } else {
                    return true;
                }
            });

            $(document).ready(function () {
                $("#property_room").trigger('change');
            });

            $('[data-toggle="tooltip"]').tooltip({
                'placement': 'top'
            });


        }
    );
})(jQuery);

