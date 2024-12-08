"use strict";
jQuery(function($){
    
    onload_body();
    function onload_body(){

        var addres_input_val = '';
      
        $('.fee').block({
            message: null,
            overlayCSS: {
                background: "#fff",
                opacity: .6
            }
        });
        var store_opt = $('.cm-store-options').find(":selected").val();
        var data = {
            'action' : 'adding_address_cost_to_order',
            'addres_input_val' : addres_input_val,
            'store_opt' : store_opt,
        }
        $.post(ajax_vars.ajax_url, data, function(resp){
            $('body').trigger("update_checkout");
            $('.fee').unblock();
        });
    }

    //on checking the checkbox will copy the biilling address from above
    $("#cm-copy-billing").click(function(){
        if($(this).is(":checked")){
            var wc_street_address = $('#billing_address_1').val();
            var wc_billing_city = $('#billing_city').val();
            var wc_country = $('#billing_country').find(":selected").text();
            var wc_billing_postcode = $('#billing_postcode').val();

            $('.custom-address').val(wc_street_address);
            $('.cm-town-city').val(wc_billing_city);
            $('.cm-fe-country').val(wc_country);
            $('.cm-fe-postcode').val(wc_billing_postcode);
        }else{
            $('.cm-empty-copy-billing').each(function(){
                $(this).val('');
            })
        }
    })


    $(document).ready(function(){
        wccpd_initAutocomplete();
        initinitialMap();
    })
    function initinitialMap() {
      const uluru = { lat: Number(ajax_vars.latitude), lng: Number(ajax_vars.longitude) };
      // The map, centered at Uluru
      const map = new google.maps.Map(
        document.getElementById("map"),
        {
          zoom: 16,
          center: uluru,
        }
      );

      // The marker, positioned at Uluru
      const marker = new google.maps.Marker({
        position: uluru,
        map: map,
      });
    }


    var store_opt = $('.cm-store-options').find(":selected").val();
    change_labels_option(store_opt);

    function change_labels_option(store_opt){

        if (store_opt=='Delivery') {

            $('.cm-address-checkout-fee').show();
            $('.storeanddelivery-pickup').hide();
            $('.cm-store-date-label').text('Delivery Date');
            $('.cm-store-time-label').text('Delivery Time');
        
        }
        else if (store_opt=='StorePickup') {
            onload_body();
            $('.cm-address-checkout-fee').hide();
            $('.cm-store-date-label').text('Store Pickup Date');
            $('.cm-store-time-label').text('Store Pickup Time');

        }

        else if (store_opt=='pickupanddelivery') {
            
            onload_body();
            $('.cm-address-checkout-fee').show();
            $('.storeanddelivery-pickup').show();
            $('.cm-store-date-label').text('Pick Up and Delivery Date');
            $('.cm-store-time-label').text('Pick Up and Delivery Time');

        }

    }
    //hide and show oncheckout //several fields and changing the labels
    $('.cm-store-options').change(function(){
        
        var store_opt = $(this).val();
        change_labels_option(store_opt);
    })

    //adding and calculating address
    $(document).on('click' , '.cm-calculate-fee', function(e){
        e.preventDefault();

        var submit_btn = $(this);

        var addres_input_val = $('.custom-address').val();
        var city             = $('.cm-town-city').val();
        var country          = $('.cm-fe-country').val();
        var postcode         = $('.cm-fe-postcode').val();


        if (addres_input_val=='' || city=='' || country=='' || postcode=='') {
            //alert('Please Fill All Address Fields');
            swal({
                title: 'Please Fill All Delivery Address Fields',
                icon: "error",
                timer: 4000,
                buttons: true
            })
            return;
        }

        var store_opt = $('.cm-store-options').find(":selected").val();
        
        var addres_input_val_pu = $('.custom-address-pickup').val();
        var city_pu             = $('.cm-town-city-pickup').val();
        var country_pu          = $('.cm-fe-country-pickup').val();
        var postcode_pu         = $('.cm-fe-postcode-pickup').val();
        
        if (store_opt=='pickupanddelivery') {



            if (addres_input_val_pu=='' || city_pu=='' || country_pu=='' || postcode_pu=='') {
                //alert('Please Fill All Address Fields');
                swal({
                    title: 'Please Fill All Pickup Address Fields',
                    icon: "error",
                    timer: 4000,
                    buttons: true
                })
                return;
            }

        }


        submit_btn.text("Please Wait...").attr('disabled', true);
      
        $('.fee').block({
            message: null,
            overlayCSS: {
                background: "#fff",
                opacity: .6
            }
        });
        var addres_string       = addres_input_val +' '+city+' '+country+' '+postcode; //Delivery Address
        var addres_string_pu    = addres_input_val_pu +' '+city_pu+' '+country_pu+' '+postcode_pu; //Pickup address
        
        var data = {
            'action' : 'adding_address_cost_to_order',
            'addres_input_val' : addres_string,
            'addres_string_pu' : addres_string_pu,
            'store_opt' : store_opt,
        }
        $.post(ajax_vars.ajax_url, data, function(resp){
            $('body').trigger("update_checkout");
            $('.fee').unblock();
            
            if (resp.status=='limit_exceeds') {
                onload_body();
                swal({
                    title: resp.message,
                    icon: "error",

                })
                submit_btn.text("Calculate Delivery Fee").attr('disabled', false);
                initinitialMap();                
            }

            else if (resp.status=='success') {
                swal({
                    title: 'Delivery Fee Added '+resp.price_symbol+resp.total_amount,
                    icon: "success",

                })
                var total_dist  = resp.total_dist;
                var unit        = resp.unit;
                $('#cm-distance').text('Total Distance :'+total_dist+unit);
                submit_btn.text("Calculate Delivery Fee").attr('disabled', false);
                calculateAndDisplayRoute(resp , resp.one_line_customer_address , resp.one_line_admin_address); //For direction/routing Purpose

            }
        });
    });
    
    /*ADDING THE DISPLAY ROUTE*/
    function calculateAndDisplayRoute(resp , origin , destination) {

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer();
        const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
            center: { lat: 41.85, lng: -87.65 },
          });

          directionsRenderer.setMap(map);

      directionsService
        .route({
          origin: {
            query: destination,
          },
          destination: {
            query: origin,
          },
          travelMode: google.maps.TravelMode.DRIVING,
        })
        .then((response) => {
          directionsRenderer.setDirections(response);
        })
        .catch((e) => initMap(resp.long_lat_arr , resp.user_lat , resp.user_long));
    }

    //loading Maps
    function initMap(loc_arr , lat_marker, long_marker ){

      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: new google.maps.LatLng(lat_marker, long_marker),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var infowindow = new google.maps.InfoWindow();

      var marker, i;

      $.each(loc_arr , function(i , v){


            var bus_site = v.title;
            var latitude = v.latitude;
            var longitude = v.longitude;
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(latitude, longitude),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
              return function() {
                infowindow.setContent(bus_site);
                infowindow.open(map, marker);
              }
            })(marker, i));
      })
      
    }

    //MultiDate picker
    var dateToday = new Date(); 
    // //custom Date picker
    $( ".custom-date-picker" ).datepicker({
        numberOfMonths: 1,
        showButtonPanel: true,
        minDate: dateToday,
        beforeShowDay: unavailable

    });

       //disabling the days which are slected on admin side
    var dates_arr = ajax_vars.datepicker_vals;
    
    var custom_dates = ajax_vars.custom_dates;
    function unavailable(date) {
        
        var dmy = date.getDate() + "-"+(date.getMonth() + 1) + "-" + date.getFullYear();
        var day = date.getDay();
        var days_in_arr = days_in_array();
        
        if ($.inArray(day, days_in_arr) == -1) {

            if ($.inArray(dmy, custom_dates) == -1) {
                return [true, ""];
            } else {
                return [false, "", "Unavailable"];
            }

            return [true, ""];
        } else {
            return [false, "", "Unavailable"];
        }

    }

    //returning days in arr
    console.log(ajax_vars.datepicker_vals);
    function days_in_array(){

        var days_arr_in_no = [];
        $.each(dates_arr, function(key, value){
            
            if (Number(value)||value=='0') {
                days_arr_in_no.push(Number(value));
            }

        });
        return days_arr_in_no;
    }

    if (ajax_vars.cm_select_format == '24Hour') {
        var time_format = 'HH:mm';
    }
    else{
        var time_format = 'h:mm p';
    }
    var time_interval = ajax_vars.cm_time_interval;
    var start_time = ajax_vars.cm_start_time;
    var end_time = ajax_vars.cm_end_time;
    if (time_interval==null||time_interval==''||time_interval==0) {
        time_interval = 15;
    }

    if (start_time==null||start_time=='') {
        start_time = '00:00';
    }
    if (end_time==null||end_time=='') {
        end_time = '12:00';
    }
    $('.custom-time-picker').timepicker({
        timeFormat: time_format,
        interval: time_interval,
        minTime: start_time,
        maxTime: end_time,
    });

    var time_arr = ajax_vars.cm_disable_time;
    
    var myArrayNew = time_arr.filter(function (el) {
        return el != null && el != "";
    });
    $('.custom-time-picker').click(function(e){

        e.preventDefault();
        $(this).attr('readonly' , true);
        $('.ui-corner-all').each(function() {
            var text = $(this).text();
            var updated_text = text.replaceAll(/\s/g,'');
            var n = myArrayNew.includes(updated_text);
            if (n) {
                $(this).parent().remove();
            }
            

        });
    })

    //AutoComplete
    var autocomplete;

    function wccpd_initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('wccpd-street-address'),
            { types: ['address'], }
        );

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            var addressComponents = place.address_components;

            if (addressComponents) {
                var address = {
                    street_number: '',
                    route: '',
                    locality: '',
                    administrative_area_level_1: '',
                    country: '',
                    postal_code: ''
                };

                addressComponents.forEach(function(component) {
                    var componentType = component.types[0];
                    if (componentType === 'street_number') {
                        address.street_number = component.short_name;
                    } else if (componentType === 'route') {
                        address.route = component.short_name;
                    } else if (componentType === 'locality') {
                        address.locality = component.long_name;
                    } else if (componentType === 'administrative_area_level_1') {
                        address.administrative_area_level_1 = component.short_name;
                    } else if (componentType === 'country') {
                        address.country = component.long_name;
                    } else if (componentType === 'postal_code') {
                        address.postal_code = component.short_name;
                    }
                });

                $('#wccpd-street-address').val(address.street_number + ' ' + address.route);
                $('#wccpd-towncity').val(address.locality);
                $('#wccpd-country').val(address.country);
                $('#wccpd-postalcode').val(address.postal_code);
            }
        });
    }


})