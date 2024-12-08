"use strict";
jQuery(function($){

    $(document).ready(function() {
        wccpd_initAutocomplete_adminside();
    })
    //AutoComplete
    var autocomplete;
    function wccpd_initAutocomplete_adminside() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('cm_shop_address'),
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

                $('#cm_shop_address').val(address.street_number + ' ' + address.route);
                $('#cm_shop_address_town_city').val(address.locality);
                $('#cm_shop_address_country').val(address.country);
                $('#cm_shop_address_postcode').val(address.postal_code);
            }
        });
    }

    var cm_time_interval = $('input[name="cm_time_interval"]').val();
    if (cm_time_interval==0||cm_time_interval=='0') {
        $('input[name="cm_time_interval"]').val('');
    }

    //saving the form fields
    $('.cm-save-admin-settings').submit(function(e){
        e.preventDefault();
        var data = $(this).serialize();
        var submit_btn = $("input[type='submit']", this);
        submit_btn.css({'width' : '100px'});
        submit_btn.val("Please Wait...").attr('disabled', true);
        $.post(ajax_vars.ajax_url, data, function(resp){
            swal({
                title: 'Settings Saved Successfully',
                icon: "success",
            }).then(function(){
                window.location.reload();
            })
        });
    });

    $( function() {
    $( "#cm-tabs" ).tabs();
  } );

    //Appending The Cost/distance Input
    $(document).on('click', '.wccpd-add-cost', function(e){
        e.preventDefault();

        var selector = $(this).closest('.wccpd-clone-tr');
        
        var clone_div = $(this).closest(selector);
        var clone_item = clone_div.clone();
        clone_item.find(':input').val('');
        clone_item.find(':checkbox').prop('checked', false);
        clone_div.after(clone_item);
    });

    // hiding the appending input of cost/distance on admin side
    $(document).on('click', '.wccpd-remove-cost', function(e){
        e.preventDefault();
        var hide_check = $('.wccpd-clone-tr').length;
        
        if (hide_check == 1) {
            swal({
                title: 'Oopss... Cannot Remove This!',
                icon: "error",
            })
        }
        else{
            var selector = $(this).closest('.wccpd-clone-tr');
            selector.remove();
        }
    })

    //Appending The Time(Disable) Input
    $(document).on('click', '.cm-add-time-input', function(e){
        e.preventDefault();

        var selector = $(this).closest('.cm-disable-time');
        
        var clone_div = $(this).closest(selector);
        var clone_item = clone_div.clone();
        clone_item.find(':input').val('');
        clone_div.after(clone_item);
    });

    // hiding the appending input of time on admin side
    $(document).on('click', '.cm-remove-time-input', function(e){
        e.preventDefault();
        var hide_check = $('.cm-remove-time-input').length;
        
        if (hide_check == 1) {
            swal({
                title: 'Oopss... Cannot Remove This!',
                icon: "error",
            })
        }
        else{
            var selector = $(this).closest('.cm-disable-time');
            selector.remove();
        }
    })

    //Appending The Date(Disable) Input
    $(document).on('click', '.cm-add-date-input', function(e){
        e.preventDefault();

        var selector = $(this).closest('.cm-disable-date');
        
        var clone_div = $(this).closest(selector);
        var clone_item = clone_div.clone();
        clone_item.find(':input').val('');
        clone_div.after(clone_item);
    });

    // hiding the appending input of Date on admin side
    $(document).on('click', '.cm-remove-date-input', function(e){
        e.preventDefault();
        var hide_check = $('.cm-remove-date-input').length;
        
        if (hide_check == 1) {
            swal({
                title: 'Oopss... Cannot Remove This!',
                icon: "error",
            })
        }
        else{
            var selector = $(this).closest('.cm-disable-date');
            selector.remove();
        }
    })

})