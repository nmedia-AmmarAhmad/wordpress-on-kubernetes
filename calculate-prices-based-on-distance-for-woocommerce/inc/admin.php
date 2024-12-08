<?php

/**======Creating Option in Woocommerce Tab======**/
function wccpd_custom_address_option_on_wc_tab() {
    
    add_submenu_page('woocommerce','WC Distance Calculater', 'WC Distance Calculater', 'manage_options', 'address_and_date_time_picker', 'wccpd_cm_custom_address_settings');
}

function wccpd_cm_custom_address_settings(){
    
    if (wccpd_is_pro_installed()) {
        
        $ob = new WCCPD_Calculate_Miles_Pro;
        $ob->admin_notices_check(); //PRO NOTICES CHECK
        
        if(wccpd_is_license_valid()){
            if(WCCPD_PRO_VERSION < 1.27){
                echo '<div class="error notice">
				    <p>You are using the older version.Please Update The PRO Version of <span style="font-weight:bold;">'.WCCPD_ITEM_REFERENCE.'</span> !! <p>You need to login to client portal to download the latest version</p> <a href="https://woo-solutions.ca/clients/wp-login.php">Login Here</a></p>
				</div>';
            }
        }
        
    }
    $checkbox_vals = get_option('sm_saved_admin_settings');
    $cm_address_apikey = isset($checkbox_vals['address_settings']['cm_address_apikey']) ? $checkbox_vals['address_settings']['cm_address_apikey'] : '';
    wp_enqueue_script('google_maps','https://maps.googleapis.com/maps/api/js?key='.$cm_address_apikey.'&libraries=places&v=weekly');

    wccpd_load_templates('cm-admin-settings.php' , array('checkbox_vals' => $checkbox_vals));
}


function wccpd_getDistance($addressFrom, $addressTo, $unit = '') {
    // Google API key
    $cm_settings = get_option('sm_saved_admin_settings');
    $apiKey = isset($cm_settings['address_settings']['cm_address_apikey']) ? $cm_settings['address_settings']['cm_address_apikey'] : '';

    // Change address format
    $formattedAddrFrom = urlencode($addressFrom);
    $formattedAddrTo = urlencode($addressTo);

    // Distance Matrix API request
    $distanceMatrixUrl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$formattedAddrFrom&destinations=$formattedAddrTo&key=$apiKey";
    $response = wp_remote_get($distanceMatrixUrl);
    
    if (is_wp_error($response)) {
        return 'Error occurred while fetching data from Google API.';
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if (!empty($data->error_message)) {
        return $data->error_message;
    }

    if (empty($data->rows[0]->elements[0]->distance->value)) {
        return 'Unable to calculate distance.';
    }

    // Get distance in meters
    $distanceInMeters = $data->rows[0]->elements[0]->distance->value;
    
    // Convert unit and return distance
    $unit = strtoupper($unit);
    if ($unit == "K") {
        return round($distanceInMeters / 1000, 2) . ' km';
    } elseif ($unit == "M") {
        return round($distanceInMeters, 2) . ' meters';
    } else {
        return round($distanceInMeters / 1609.344, 2) . ' miles';
    }
}



/****=====Get Longitude/latitude====***/
function wccpd_get_long_lat($address) {
    // Change address format
    $formattedAddress = urlencode($address);

    // Get API key from settings
    $cm_settings = get_option('sm_saved_admin_settings');
    $apiKey = isset($cm_settings['address_settings']['cm_address_apikey']) ? $cm_settings['address_settings']['cm_address_apikey'] : '';

    if (empty($apiKey)) {
        return 'API key is missing or invalid.';
    }

    // Geocoding API request with the address
    $geocodeUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddress . '&key=' . $apiKey;
    $response = wp_remote_get($geocodeUrl);

    if (is_wp_error($response)) {
        return 'Error occurred while fetching data from Google API.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if (!empty($data->error_message)) {
        return $data->error_message;
    }

    if (empty($data->results[0]->geometry->location)) {
        return 'Unable to get latitude and longitude for the provided address.';
    }

    // Get latitude and longitude from the geodata
    $latitude = $data->results[0]->geometry->location->lat;
    $longitude = $data->results[0]->geometry->location->lng;

    return array(
        'latitude' => $latitude,
        'longitude' => $longitude,
    );
}




/****=====Check IF is PRO Installed====***/
function wccpd_is_pro_installed(){
    $pluginList = get_option( 'active_plugins' );

        if (in_array('calculate-prices-based-on-distance-for-woocommerce-pro/calculate-prices-based-on-distance-for-woocommerce-pro.php', $pluginList)) {
            return true;
        }
        else return false;
}

//License Activation
function wccpd_is_license_valid(){
    
    $own_check = get_option('wccpd_pro_check_licenser');
    if ($own_check=='success') {
        return true;
    }
    else return false;
}