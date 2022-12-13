(function ($) {
    "use strict";

    $(function () {

        $( ".get_order_ref_data" ).on('click',function(e){
            e.preventDefault();
            
            var order_ref = $('#stgh-contact-order-crm-autocomplete').val();

            $.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/ajax/autocomplete-orders-json.php",
                data: { 
                    order_ref: order_ref,
                }
            })
            .done(function( data ) {
                console.log( "Data Saved: " + data );
                //return data;
                var json = $.parseJSON(data);
                $(json).each(function(i,val){
                    $.each(val,function(k,v){
                        //console.log(k+" : "+ v);     
                        if( k === 'customer_id'){
                            console.log('Customer id: '+v);
                            $('#stgh-crm-contact-value').val(v);
                        }  
                        if( k === 'customer_name'){
                            console.log('Name: '+v);
                            var name = 
                            $('#stgh-contact-crm-autocomplete').val(v);
                        }
                        if( k === 'customer_email'){
                            console.log('Email: '+v);
                            //$('').val();
                        }
                        if( k === 'order_name'){
                            console.log('Order Name: '+v);
                            $('label[for="title"]').addClass('title-prompt-text screen-reader-text');
                            //$('label[for="title"]').addClass('title-prompt-text');
                            $('#title').val(v);
                        }
                });
                });
            });
        });


          

        $('#stgh-contact-crm-autocomplete').autocomplete({
            source: function (request, response) {
                $.getJSON(ajaxurl + "?callback=?&action=stgh-autocomplete", request, function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.id,
                            label: item.label,
                            first: item.first_name,
                            last: item.last_name,
                            email: item.email
                        }
                    }));
                });
            },
            select: function (event, ui) {
                event.preventDefault();

                $("#stgh-contact-crm-autocomplete").val(ui.item.label);
                $("#stgh-crm-contact-value").val(ui.item.value);


                $('select[name = \'stg_ticket_edd_order_id\']').val(null).trigger('change');
                $('select[name = \'stg_ticket_edd_order_id\']').find('option').not(':first').remove();

                $('select[name = \'stg_ticket_wc_order_id\']').val(null).trigger('change');
                $('select[name = \'stg_ticket_wc_order_id\']').find('option').not(':first').remove();

                $('#stgh-ticket-wc-order-list-box').css('display', 'none');
                $('#stgh-ticket-edd-order-list-box').css('display', 'none');


                $.getJSON(ajaxurl + "?callback=?&action=stgh-get-orders", ui, function (data) {
                    if(data) {

                        if(data.woo){
                            $('#stgh-ticket-wc-order-list-box').css('display', 'inline-block');
                            for (var current in data.woo) {
                                var newOption = new Option(data.woo[current].text, data.woo[current].id, false, false);
                                $('select[name = \'stg_ticket_wc_order_id\']').append(newOption).trigger('change');
                            }
                        }

                        if(data.edd){
                            $('#stgh-ticket-edd-order-list-box').css('display', 'inline-block');
                            for (var current in data.edd) {
                                var newOption = new Option(data.edd[current].text, data.edd[current].id, false, false);
                                $('select[name = \'stg_ticket_edd_order_id\']').append(newOption).trigger('change');
                            }
                        }
                    }
                });
            },
            focus: function (event, ui) {
                event.preventDefault();

                $("#stgh-contact-crm-autocomplete").val(ui.item.label);
            },
            minLength: 3
        }).autocomplete("instance")._renderItem = function (ul, item) {

            if (item.first != "" && item.last != "") {
                return $("<li>")
                    .append("<b>" + item.last + " " + item.first + "</b><br>" + item.email)
                    .appendTo(ul);
            }

            if (item.email != item.label) {
                return $("<li>")
                    .append("<b>" + item.label + "</b><br>" + item.email)
                    .appendTo(ul);
            }

            return $("<li>")
                .append("<b>" + item.label + "</b>")
                .appendTo(ul);
        };

    });


}(jQuery));

