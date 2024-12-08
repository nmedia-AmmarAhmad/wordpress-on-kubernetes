<?php 

/*
Plugin Name:  Calculate Prices based on Distance For WooCommerce
Author: Ammar Ahmad
Plugin URI: https://woo-solutions.ca/wordpress-plugins/
Author URI: https://woo-solutions.ca
Text Domain: calculate-prices-based-on-distance-for-woocommerce
Description: Delivery Slot and Distance Calculator plugin for WooCommerce, adds a delivery fee to the order by calculating the miles/kilometers from your store to the delivery address using Google Maps API.
Version: 1.3.4
*/


/* 
**========== Direct access not allowed =========== 
*/ 
if( ! defined('ABSPATH') ) die('Not Allowed');

/* 
**========== Constant Variable =========== 
*/ 
define( 'WCCPD_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define( 'WCCPD_URL' ,  untrailingslashit(plugin_dir_url( __FILE__ )) );

/* 
**========== Files Load =========== 
*/
if( file_exists( dirname(__FILE__).'/inc/helpers.php' )) include_once dirname(__FILE__).'/inc/helpers.php';
if( file_exists( dirname(__FILE__).'/inc/admin.php' )) include_once dirname(__FILE__).'/inc/admin.php';

class WCCPD_Calculate_Miles {
	
	function __construct() {
	    
		add_action( 'admin_menu', 'wccpd_custom_address_option_on_wc_tab' );
		add_action( 'admin_enqueue_scripts', array($this , 'load_admin_files') );
		add_action( 'wp_enqueue_scripts', array($this , 'load_script_style_files') );
		add_action( 'woocommerce_before_order_notes', array($this , 'adding_custom_fields_on_checkout'), 20 );
		add_action( 'woocommerce_checkout_process', array($this , 'custom_checkout_field_process') );
		add_action( 'woocommerce_checkout_create_order', array($this , 'custom_checkout_field_update_meta'), 10, 2 );
		add_action('wp_ajax_cm_save_form_fields' , array($this , 'cm_save_form_fields'));
		add_action('wp_ajax_adding_address_cost_to_order' , array($this , 'cm_add_address_to_order'));
		add_action('wp_ajax_nopriv_adding_address_cost_to_order' , array($this , 'cm_add_address_to_order'));
		add_action( 'woocommerce_cart_calculate_fees', array($this , 'add_custom_delivery_charges'), 10, 1 );
		add_filter( 'woocommerce_email_customer_details_fields', array($this , 'wc_customer_details'), 10, 3 );
		
	}

	//woocommerce Email Customization
	function wc_customer_details( $fields, $sent_to_admin, $order ) {
	    
	    if ( empty( $fields ) ) {


			$date = $order->get_meta('custom_date_picker');
			$time = $order->get_meta('custom_time_picker');
			$address = $order->get_meta('delivery_address');
      
	    	//Date
            $fields['customer_selected_date'] = array(
                'label' => __( 'Selected Date', 'woocommerce' ),
                'value' => wptexturize( $date ),
            );

	    	//Time
            $fields['customer_selected_time'] = array(
                'label' => __( 'Selected Time', 'woocommerce' ),
                'value' => wptexturize( $time ),
            );
	        
	        // Address
            $fields['customer_address'] = array(
                'label' => __( 'Customer Address', 'woocommerce' ),
                'value' => __($address , 'woocommerce'),
            );
	    }
	    
	    return $fields;

	}

	/****=====Adding Address Cost To Order====***/
    function add_custom_delivery_charges ( $cart ) {

        global $woocommerce;

        $tip = WC()->session->get( 'cm_delivery_charges' );


        if ($tip) {

            WC()->cart->add_fee(__('Delivery Charges', 'calculate-prices-based-on-distance-for-woocommerce'), floatval($tip));
         
        }
    }

	/****=====Adding Address To Order====***/
	function cm_add_address_to_order(){

	    $cm_settings = get_option('sm_saved_admin_settings');

	    $cm_select_unit = isset($cm_settings['address_settings']['cm_select_unit']) ? $cm_settings['address_settings']['cm_select_unit'] : '';
	    
	    $cm_cost_per_unit = isset($cm_settings['address_settings']['cm_cost_per_unit']) ? $cm_settings['address_settings']['cm_cost_per_unit'] : 0;
	    
	    // PRO
	    $cm_restrict_per_unit = isset($cm_settings['address_settings']['cm_restrict_per_unit']) ? $cm_settings['address_settings']['cm_restrict_per_unit'] : '';
	    // PRO

	    $cm_user_address = sanitize_text_field( isset($_REQUEST['addres_input_val']) ? $_REQUEST['addres_input_val'] : '');
    
    
        $cm_pickup_address = '';
        if (wccpd_is_pro_installed() && wccpd_is_license_valid()) {
            $cm_pickup_address = sanitize_text_field( isset($_REQUEST['addres_string_pu']) ? $_REQUEST['addres_string_pu'] : ''); //coming from checkout page if option is delievery and store pickup option is enabled
        }

    	$unit = '';
	    if ($cm_select_unit=='km') {
	    	$unit = 'K';
	    }

	    //admin address
	    $cm_shop_address = '';
	    if ($cm_pickup_address) {
	        $cm_shop_address = $cm_pickup_address;
	    }
	    else{
	    	$cm_shop_address = $this->get_formated_admin_address();
	    }
	    
	    $long_lat_arr = array();
	    
	    $admin_lat_long = wccpd_get_long_lat($cm_shop_address);
	    $admin_lat = isset($admin_lat_long['latitude']) ? $admin_lat_long['latitude'] : '';
	    $admin_long = isset($admin_lat_long['longitude']) ? $admin_lat_long['longitude'] : '';
	    $long_lat_arr[] = array(
	    	'title'  	=> 'Shop Location',
	    	'latitude'  => $admin_lat,
	    	'longitude'  => $admin_long,
	    );
	    if (!empty($cm_user_address)) {
	    	$cm_user_address_long_lat =  wccpd_get_long_lat($cm_user_address);
	    	$user_lat = isset($cm_user_address_long_lat['latitude']) ? $cm_user_address_long_lat['latitude'] : '';
	    	$user_long = isset($cm_user_address_long_lat['longitude']) ? $cm_user_address_long_lat['longitude'] : '';
	    	$long_lat_arr[] = array(
		    	'title'  	=> 'Your Location',
		    	'latitude'  => $user_lat,
		    	'longitude'  => $user_long,
		    );
		    WC()->session->set( 'cm_delivery_address_setteled', true); //setting the session for checking if required from admin settings or no

	    }else{
        	WC()->session->set( 'cm_delivery_address_setteled', false); //setting the session for checking if required from admin settings or no
	    }

	    $amount = 0;
	    $total_miles = wccpd_getDistance($cm_shop_address , $cm_user_address , $unit); //Miles or KM


	    if (wccpd_is_pro_installed() && wccpd_is_license_valid()) { //PRO Version (Data Coming from PRO files)
	    	
	    	if (floatval($total_miles) < $cm_restrict_per_unit ) {
		     	$amount = wccpd_pro_cost_calculator(floatval($total_miles));
	     		if ($cm_restrict_per_unit==0) {
	     			$amount = 0;
	     		}
		    
		    }else{
		    
		    	$ob = new WCCPD_Calculate_Miles_Pro;
		    	$resp = $ob->get_restriction_response($cm_restrict_per_unit , $cm_select_unit);
	    		wp_send_json($resp);
	    		die(0);exit;
		    }

	    }
		// wccpd_pa($total_miles);
	    else{//free version
	    	if (floatval($total_miles) > 0 ) {
				
				$cm_fix_price = isset($cm_settings['address_settings']['cm_fix_price']) ? $cm_settings['address_settings']['cm_fix_price'] : '';
		     	if(!empty($cm_fix_price) && $cm_fix_price){
					$amount = floatval($cm_cost_per_unit);
				}
				else{
					$amount = (floatval($cm_cost_per_unit)*floatval($total_miles));
				}
				
		    
		    }else{
		    	$amount = 0; //if only free is installed
		    } 
	    }
    
        
    	WC()->session->set( 'cm_save_user_address', $cm_user_address); //saving the User Address too for the WooCommerce Order

	    if (empty($cm_user_address)) {
	    	$amount = 0;
	    }

	    /*SELECTED OPTION*/
	    $store_opt = isset($_REQUEST['store_opt']) ? $_REQUEST['store_opt'] : '';
	    if ($store_opt=='StorePickup') {
	    	WC()->session->set( 'cm_delivery_option', 'StorePickup'   );
	    }
	    else{
	    	WC()->session->set( 'cm_delivery_option', 'delivery'   );
	    }


    	WC()->session->set( 'cm_delivery_charges', floatval($amount)   );
    	
    	$resp = array(
    					'status'		=>'success' ,
    				  	'total_dist'	=> floatval($total_miles),
    				  	'unit'			=>strtoupper($cm_select_unit),
    				  	'cost'			=>floatval($cm_cost_per_unit),
    				  	'user_lat' 		=> floatval($user_lat),
    				  	'user_long' 	=> floatval($user_long),
    				  	'price_symbol' 	=> html_entity_decode(get_woocommerce_currency_symbol()),
    				  	'long_lat_arr'	=>$long_lat_arr,
    				  	'total_amount'	=>round($amount , 2),
    				  	'one_line_customer_address' => $cm_user_address,
    				  	'one_line_admin_address' 	=> $cm_shop_address,
    				);
    	wp_send_json($resp);
	    die(0);
	}


	/****=====Load Admin JS And CSS files====***/
	function load_admin_files($hook){

		if ($hook=='woocommerce_page_address_and_date_time_picker') {
			

			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_style('jquery-ui-css' , WCCPD_URL."/css/jquery-ui.css");
			//plugin css
			wp_enqueue_style('cm_stlye_css', WCCPD_URL."/css/cm-admin-style.css",  true);
			
			//bootstrap css
			wp_enqueue_style('bootstrap-css', WCCPD_URL."/css/bootstrap.min.css",  true);
			
			// Fontawsome Cdn
			
			wp_enqueue_style('font-aws', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",  true);
			
			//plugin script
			wp_enqueue_script('cm_script_js', WCCPD_URL."/js/cm-admin-script.js",  array('jquery'));
			
			//sweet alert
			wp_enqueue_script('sweetalert', WCCPD_URL."/js/sweetalert.js",  array('jquery'));

			// localizing own script file
		    wp_localize_script('cm_script_js', 'ajax_vars', array(
		    	'ajax_url'      		=> 	admin_url( 'admin-ajax.php' ),
		    	)
			);		
			
		}

	}

	/****=====Saving Form Fields From Admin Side====***/
	function cm_save_form_fields(){

		//Timepicker settings
		$cm_disable_time = isset($_REQUEST['cm_disable_time']) ? array_map( 'sanitize_text_field', $_REQUEST['cm_disable_time'] ) : '';
		
		$cm_start_time 		= sanitize_text_field(isset($_REQUEST['cm_start_time']) ? $_REQUEST['cm_start_time'] : '');
		
		$cm_end_time 		= sanitize_text_field(isset($_REQUEST['cm_end_time']) ? $_REQUEST['cm_end_time'] : '');
		
		$cm_select_format 	= sanitize_text_field(isset($_REQUEST['cm_select_format']) ? $_REQUEST['cm_select_format'] : '');
		
		$cm_time_interval 	= floatval(isset($_REQUEST['cm_time_interval']) ? $_REQUEST['cm_time_interval'] : '');

		// PRO
		$cm_show_hide_timepicker 	= sanitize_text_field(isset($_REQUEST['cm_show_hide_timepicker']) ? $_REQUEST['cm_show_hide_timepicker'] : '');
		$cm_required_timepicker 	= sanitize_text_field(isset($_REQUEST['cm_required_timepicker']) ? $_REQUEST['cm_required_timepicker'] : '');
			
		// PRO

		//Date picker Settings
		$cm_disable_custom_dates = isset($_REQUEST['cm_disable_custom_dates']) ? array_map( 'sanitize_text_field', $_REQUEST['cm_disable_custom_dates'] ) : '';

		
		$sunday 	= sanitize_text_field(isset($_REQUEST['sunday']) ? $_REQUEST['sunday'] : '');
		$monday 	= sanitize_text_field(isset($_REQUEST['monday']) ? $_REQUEST['monday'] : '');

		$tuesday 	= sanitize_text_field(isset($_REQUEST['tuesday']) ? $_REQUEST['tuesday'] : '');

		$wednesday 	= sanitize_text_field(isset($_REQUEST['wednesday']) ? $_REQUEST['wednesday'] : '');

		$thursday 	= sanitize_text_field(isset($_REQUEST['thursday']) ? $_REQUEST['thursday'] : '');
		

		$friday 	=  sanitize_text_field( isset($_REQUEST['friday']) ? $_REQUEST['friday'] : '');
		
		$saturday 	= sanitize_text_field( isset($_REQUEST['saturday']) ? $_REQUEST['saturday'] : '');

		// PRO
		$cm_show_hide_datepicker 	= sanitize_text_field( isset($_REQUEST['cm_show_hide_datepicker']) ? $_REQUEST['cm_show_hide_datepicker'] : '');
		$cm_required_datepicker 	= sanitize_text_field( isset($_REQUEST['cm_required_datepicker']) ? $_REQUEST['cm_required_datepicker'] : '');
			
		//PRO

		//Address Settings
		$cm_address_apikey = sanitize_text_field( isset($_REQUEST['cm_address_apikey']) ? $_REQUEST['cm_address_apikey'] : '');
		

		$cm_cost_per_unit 	= floatval(isset($_REQUEST['cm_cost_per_unit']) ? $_REQUEST['cm_cost_per_unit'] : 0);

		//PRO
		$cm_restrict_per_unit 	= floatval(isset($_REQUEST['cm_restrict_per_unit']) ? $_REQUEST['cm_restrict_per_unit'] : '');
		
		$wccpd_from_cost_pro = isset($_REQUEST['wccpd_from_cost_pro']) ? $_REQUEST['wccpd_from_cost_pro']  : '';
		

		/*CUSTOMIZATION SHOWING OPTIONS ON CHECKOUT PAGE*/
		$cm_delivery_opt = isset($_REQUEST['cm_delivery_opt']) ? $_REQUEST['cm_delivery_opt']  : '';
		$cm_store_pickup_opt = isset($_REQUEST['cm_store_pickup_opt']) ? $_REQUEST['cm_store_pickup_opt']  : '';


		$cm_delivery_and_store_pickup_opt = isset($_REQUEST['cm_delivery_and_store_pickup_opt']) ? $_REQUEST['cm_delivery_and_store_pickup_opt']  : '';
		$cm_map_calculator_req = isset($_REQUEST['cm_map_calculator_req']) ? $_REQUEST['cm_map_calculator_req']  : '';
        

		if (!wccpd_is_pro_installed() || !wccpd_is_license_valid()) {
			$cm_restrict_per_unit = '';
			$wccpd_from_cost_pro  = array();
			$cm_show_hide_datepicker = '';
			$cm_required_datepicker = '';
			$cm_show_hide_timepicker = '';
			$cm_required_timepicker = '';
			$cm_delivery_and_store_pickup_opt = '';
			$cm_map_calculator_req = '';
		}

		$cm_select_unit 	= sanitize_text_field( isset($_REQUEST['cm_select_unit']) ? $_REQUEST['cm_select_unit'] : '');
		
		$cm_shop_address 	= sanitize_text_field( isset($_REQUEST['cm_shop_address']) ? $_REQUEST['cm_shop_address'] : '');
		
		$cm_delivery_label 	= sanitize_text_field( isset($_REQUEST['cm_delivery_label']) ? $_REQUEST['cm_delivery_label'] : '');
		$cm_fix_price 		= isset($_REQUEST['cm_fix_price']) ? $_REQUEST['cm_fix_price'] : '';
		
		$cm_shop_address_two = sanitize_text_field( isset($_REQUEST['cm_shop_address_two']) ? $_REQUEST['cm_shop_address_two'] : '');
		
		$cm_shop_address_town_city = sanitize_text_field( isset($_REQUEST['cm_shop_address_town_city']) ? $_REQUEST['cm_shop_address_town_city'] : '');
		
		$cm_shop_address_country = sanitize_text_field( isset($_REQUEST['cm_shop_address_country']) ? $_REQUEST['cm_shop_address_country'] : '');
		
		$cm_shop_address_postcode = sanitize_text_field( isset($_REQUEST['cm_shop_address_postcode']) ? $_REQUEST['cm_shop_address_postcode'] : '');
		
		$saving_arr = array(
			'sunday' 	=> $sunday,
			'monday' 	=> $monday,
			'tuesday' 	=> $tuesday,
			'wednesday' => $wednesday,
			'thursday' 	=> $thursday,
			'friday' 	=> $friday,
			'saturday'	=> $saturday,
			'cm_show_hide_datepicker'	=> $cm_show_hide_datepicker,
			'cm_required_datepicker'	=> $cm_required_datepicker,
		);

		// cm_disable_custom_dates
		$saving_arr['cm_disable_custom_dates'] = $cm_disable_custom_dates; 

		//timepicker settings saving
		$saving_arr['cm_timepicker_settings'] = array(
			'cm_start_time' 	=> $cm_start_time,
			'cm_end_time' 		=> $cm_end_time,
			'cm_select_format' 	=> $cm_select_format,
			'cm_time_interval' 	=> $cm_time_interval,
			'cm_show_hide_timepicker' 	=> $cm_show_hide_timepicker,
			'cm_required_timepicker' 	=> $cm_required_timepicker,
		);
		$saving_arr['cm_timepicker_settings']['cm_disable_time'] = $cm_disable_time; 

		//address settings being saved
		$saving_arr['address_settings'] = array(
			'cm_address_apikey' 	=> $cm_address_apikey,
			'cm_cost_per_unit' 		=> $cm_cost_per_unit,
			'cm_select_unit' 		=> $cm_select_unit,
			'cm_shop_address' 		=> $cm_shop_address,
			'cm_delivery_label' 	=> $cm_delivery_label,
			'cm_fix_price' 			=> $cm_fix_price,
			'cm_shop_address_two' 	=> $cm_shop_address_two,
			'cm_shop_address_town_city' 	=> $cm_shop_address_town_city,
			'cm_shop_address_country' 	=> $cm_shop_address_country,
			'cm_shop_address_postcode' 	=> $cm_shop_address_postcode,
			'cm_restrict_per_unit' 	=> $cm_restrict_per_unit,
			'wccpd_from_cost_pro' 	=> $wccpd_from_cost_pro,
			'cm_delivery_opt' 		=> $cm_delivery_opt,
			'cm_store_pickup_opt' 	=> $cm_store_pickup_opt,
			'cm_delivery_and_store_pickup_opt' 	=> $cm_delivery_and_store_pickup_opt,
			'cm_map_calculator_req' 	=> $cm_map_calculator_req,
		);

		update_option('sm_saved_admin_settings' , $saving_arr);
		wp_send_json(array('resp'=>'success'));
	}

	/*****=======Getting Formatted Address From Admin Side=======****/
	function get_formated_admin_address(){

		$settings_option = get_option('sm_saved_admin_settings');
		$admin_address = isset($settings_option['address_settings']) ? $settings_option['address_settings'] : '';

		$street_add_1 = isset($admin_address['cm_shop_address']) ? $admin_address['cm_shop_address'] : '';		

		$street_add_2 = isset($admin_address['cm_shop_address_two']) ? $admin_address['cm_shop_address_two'] : '';		

		$street_add_town_city = isset($admin_address['cm_shop_address_town_city']) ? $admin_address['cm_shop_address_town_city'] : '';		

		$country = isset($admin_address['cm_shop_address_country']) ? $admin_address['cm_shop_address_country'] : '';
		$postcode = isset($admin_address['cm_shop_address_postcode']) ? $admin_address['cm_shop_address_postcode'] : '';

		$string = $street_add_1.' '.$street_add_2.' '.$street_add_town_city.' '.$country.' '.$postcode;

		return $string;

	}

	/*****=======Adding The Time And Date Values to Order=======****/
	function custom_checkout_field_update_meta( $order, $data ){
		//datepicker
	    if( sanitize_text_field(isset($_POST['custom_date_picker']) ? $_POST['custom_date_picker'] : '') )
	        $order->update_meta_data( 'custom_date_picker',  sanitize_text_field($_POST['custom_date_picker']) );

		//timepicker
	    if( sanitize_text_field( isset($_POST['custom_time_picker']) ? $_POST['custom_time_picker'] : '') )
	        $order->update_meta_data( 'custom_time_picker', sanitize_text_field($_POST['custom_time_picker']) );


	    //selector
	    if( sanitize_text_field(isset($_POST['cm_select_del_option']) ? $_POST['cm_select_del_option'] : '') )
	        $order->update_meta_data( 'cm_select_del_option', sanitize_text_field($_POST['cm_select_del_option']) );

	    // address
	       
	        $address = WC()->session->get( 'cm_save_user_address' );
	        if(isset($address)){
	            $order->update_meta_data( 'delivery_address', $address );
	        }
	        

	}

	/****======Validating the Date And picker Field ======****/
	function custom_checkout_field_process() {
    	
    	$save_vals = get_option('sm_saved_admin_settings');
		//datepicker
	    if ( isset($_POST['custom_date_picker']) && empty($_POST['custom_date_picker']) )
	        

			// PRO
			$cm_required_datepicker = isset($save_vals['cm_required_datepicker']) ? $save_vals['cm_required_datepicker'] : '';
			// PRO

			if ($cm_required_datepicker == 'required' && wccpd_is_pro_installed() && wccpd_is_license_valid()) {
	        	wc_add_notice( __( 'Please fill in "The Date Field".' ), 'error' );
			}

		//timepicker
	    if ( isset($_POST['custom_time_picker']) && empty($_POST['custom_time_picker']) )

	    	$cm_required_timepicker = isset($save_vals['cm_timepicker_settings']['cm_required_timepicker']) ? $save_vals['cm_timepicker_settings']['cm_required_timepicker'] : '';

	    	if ($cm_required_timepicker == 'requiredtime' && wccpd_is_pro_installed() && wccpd_is_license_valid()) {
	        	wc_add_notice( __( 'Please fill in "The Time Field".' ), 'error' );
			}



        if (wccpd_is_pro_installed() && wccpd_is_license_valid()) {
        	

	        $option = WC()->session->get( 'cm_delivery_option' );

	        $cm_map_calculator_req = isset($save_vals['address_settings']['cm_map_calculator_req']) ? $save_vals['address_settings']['cm_map_calculator_req'] : '';

	        if (!empty($cm_map_calculator_req) && $option!= 'StorePickup') {
	        	
    			global $woocommerce;
    	        $check_address = WC()->session->get( 'cm_delivery_address_setteled' ); //check if address is being entered or no
                
		        if (!$check_address) {
		        	wc_add_notice( __( 'Please "Add the Delivery Address to Proceed".' ), 'error' );
		        }

	        }

        }



	}

	/****======Adding fields on checkout page======****/
	function adding_custom_fields_on_checkout(){

		wccpd_load_templates('cm-checkout-template.php');

	}

	/****=======load files on checkout page======****/
	function load_script_style_files(){

		if (function_exists('is_checkout')) {

			if (is_checkout()) {

				if (wccpd_is_pro_installed() && wccpd_is_license_valid()) {
					wp_enqueue_script('cm_pro_checkoutscript_js', WCCPD_PRO_URL."/js/wccpd-pro-script.js",  array('jquery')); //Coming FROM PRO VERSION
				}


				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-datepicker');

				wp_enqueue_style('cm_multiple_date_picker', WCCPD_URL."/css/jquery-ui.multidatespicker.css",  true);


				
				wp_enqueue_style('cm_stlye_css', WCCPD_URL."/css/cm-front-end-style.css",  true);
				wp_enqueue_script('cm_checkoutscript_js', WCCPD_URL."/js/cm-checkout-script.js",  array('jquery'));

				//timepicker
				wp_enqueue_style('cm_style_timepicker', WCCPD_URL."/css/timepicker.min.css",  true);
				wp_enqueue_script('cm_script_timepicker', WCCPD_URL."/js/timepicker.min.js",  array('jquery'));
				
				//sweet alert
				wp_enqueue_script('sweetalert', WCCPD_URL."/js/sweetalert.js",  array('jquery'));


				//TimePicker
				$datepicker_vals = get_option('sm_saved_admin_settings');
				$cm_address_apikey = isset($datepicker_vals['address_settings']['cm_address_apikey']) ? $datepicker_vals['address_settings']['cm_address_apikey'] : '';

				wp_enqueue_script('google_maps','https://maps.googleapis.com/maps/api/js?key='.$cm_address_apikey.'&libraries=places&v=weekly');


				$cm_start_time = isset($datepicker_vals['cm_timepicker_settings']['cm_start_time']) ? $datepicker_vals['cm_timepicker_settings']['cm_start_time'] : '';

				$cm_end_time = isset($datepicker_vals['cm_timepicker_settings']['cm_end_time']) ? $datepicker_vals['cm_timepicker_settings']['cm_end_time'] : '';

				$cm_select_format = isset($datepicker_vals['cm_timepicker_settings']['cm_select_format']) ? $datepicker_vals['cm_timepicker_settings']['cm_select_format'] : '';

				$cm_time_interval = isset($datepicker_vals['cm_timepicker_settings']['cm_time_interval']) ? $datepicker_vals['cm_timepicker_settings']['cm_time_interval'] : '';

				//DatePicker Settings
				$custom_dates    = isset($datepicker_vals['cm_disable_custom_dates']) ? $datepicker_vals['cm_disable_custom_dates'] : ''; 

				$updated_dates_arr = array();
				if (!empty($custom_dates)) {
					
					foreach ($custom_dates as $date) {

						$now = new DateTime($date);
						$timestring = $now->format('j-n-Y');
						$updated_dates_arr[] = $timestring;

					}
					
				}

				//timpicker
				$cm_disable_time = isset($datepicker_vals['cm_timepicker_settings']['cm_disable_time']) ? $datepicker_vals['cm_timepicker_settings']['cm_disable_time'] : '';


				if ($cm_select_format!='24Hour') {
					
					$update_format = array();
					if (!empty($cm_disable_time)) {
						foreach ($cm_disable_time as $d_time) {
						
							$date 		= new DateTime($d_time);
							$trim_zer = $date->format('h:iA');
							$trim_zer = ltrim($trim_zer, '0');				
							$update_format[] = $trim_zer;
						
						}
					}
					$cm_disable_time = $update_format;
				}

				//admin address
				$final_address = $this->get_formated_admin_address();
				
				$long_lat = wccpd_get_long_lat($final_address);
				$latitude = isset($long_lat['latitude']) ? $long_lat['latitude'] : '';
				$longitude = isset($long_lat['longitude']) ? $long_lat['longitude'] : '';

				$sunday = isset($datepicker_vals['sunday']) ? $datepicker_vals['sunday'] : '';
				$monday = isset($datepicker_vals['monday']) ? $datepicker_vals['monday'] : '';
				$tuesday = isset($datepicker_vals['tuesday']) ? $datepicker_vals['tuesday'] : '';
				$wednesday = isset($datepicker_vals['wednesday']) ? $datepicker_vals['wednesday'] : '';
				$thursday = isset($datepicker_vals['thursday']) ? $datepicker_vals['thursday'] : '';
				$friday = isset($datepicker_vals['friday']) ? $datepicker_vals['friday'] : '';
				$saturday = isset($datepicker_vals['saturday']) ? $datepicker_vals['saturday'] : '';

				//days array which are going to be disabled on checkout
				$dates_arr 	 = array();
				$dates_arr[] = $sunday; 
				$dates_arr[] = $monday; 
				$dates_arr[] = $tuesday; 
				$dates_arr[] = $wednesday; 
				$dates_arr[] = $thursday; 
				$dates_arr[] = $friday; 
				$dates_arr[] = $saturday; 
				
				// localizing own script file
			    wp_localize_script('cm_checkoutscript_js', 'ajax_vars', array(
			    	'ajax_url'      		=> 	admin_url( 'admin-ajax.php' ),
			    	'datepicker_vals'      	=> 	$dates_arr,
			    	'custom_dates'      	=> 	$updated_dates_arr,
			    	'cm_start_time'      	=> 	$cm_start_time,
			    	'cm_end_time'      		=> 	$cm_end_time,
			    	'cm_select_format'      => 	$cm_select_format,
			    	'cm_time_interval'      => 	$cm_time_interval,
			    	'cm_disable_time'      	=> 	$cm_disable_time,
			    	'latitude'      		=> 	$latitude,
			    	'longitude'      		=> 	$longitude,
			    	)
				);
			}	
		}
	}

}

new WCCPD_Calculate_Miles;