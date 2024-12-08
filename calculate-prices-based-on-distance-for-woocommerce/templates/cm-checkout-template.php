<?php 

	$save_vals = get_option('sm_saved_admin_settings');

	$main_label = isset($save_vals['address_settings']['cm_delivery_label']) ? $save_vals['address_settings']['cm_delivery_label'] : 'Delivery And Store Pickup Options';
	
	//miles/km
	$unit = isset($save_vals['address_settings']['cm_select_unit']) ? $save_vals['address_settings']['cm_select_unit'] : '';
	$cm_cost_per_unit = isset($save_vals['address_settings']['cm_cost_per_unit']) ? $save_vals['address_settings']['cm_cost_per_unit'] : '';

	// PRO
	$cm_show_hide_datepicker = isset($save_vals['cm_show_hide_datepicker']) ? $save_vals['cm_show_hide_datepicker'] : '';
	$cm_show_hide_timepicker = isset($save_vals['cm_timepicker_settings']['cm_show_hide_timepicker']) ? $save_vals['cm_timepicker_settings']['cm_show_hide_timepicker'] : '';
	// PRO

	$cm_delivery_opt = isset($save_vals['address_settings']['cm_delivery_opt']) ? $save_vals['address_settings']['cm_delivery_opt'] : 'cm_delivery_opt';
	$cm_store_pickup_opt = isset($save_vals['address_settings']['cm_store_pickup_opt']) ? $save_vals['address_settings']['cm_store_pickup_opt'] : 'cm_store_pickup_opt';
	$cm_delivery_and_store_pickup_opt = isset($save_vals['address_settings']['cm_delivery_and_store_pickup_opt']) ? $save_vals['address_settings']['cm_delivery_and_store_pickup_opt'] : '';
	$cm_fix_price = isset($save_vals['address_settings']['cm_fix_price']) ? $save_vals['address_settings']['cm_fix_price'] : '';

	$html = '';
	if (!empty($cm_delivery_opt) && $cm_delivery_opt == 'cm_delivery_opt') {
		$html .= '<option value="Delivery">Delivery</option>';
	}

	if (!empty($cm_store_pickup_opt) && $cm_store_pickup_opt == 'cm_store_pickup_opt') {
		$html .= '<option value="StorePickup">Store Pickup</option>';
	}
	
	//showing from address and to address
	if(wccpd_is_pro_installed() && wccpd_is_license_valid()){
    	if (!empty($cm_delivery_and_store_pickup_opt) && $cm_delivery_and_store_pickup_opt == 'cm_delivery_and_store_pickup_opt') {
    		$html .= '<option value="pickupanddelivery">Pick Up and Delivery</option>';
    	}   
	}

	//Changing the text if delivery is fixed other wise showing cost per mile/KM
	$text = 'Cost Per ';
	if(!empty($cm_fix_price) && $cm_fix_price){
		$text = 'Delivery Charges :';
	}


 ?>
 <div class="custom-container">
	
	<div class="cm-date-time-input">
		<?php 

			if (!$cm_show_hide_datepicker == 'showhide') {
				?>

					<!-- datepicker -->
				    <label class="cm-store-date-label"><?php echo esc_attr__('Store Pickup Date' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>
				    <input type="text" name="custom_date_picker" class="cm-checkout-inputs custom-date-picker" readonly><br>
				<?php 
			}


		 ?>

		<?php 

			if (!$cm_show_hide_timepicker == 'showhidetime') {
				?>

					<!-- //timepicker -->
				    <label class="cm-store-time-label"><?php echo esc_attr__('Store Pickup Time' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>
				    <input type="text" name="custom_time_picker" class="custom-time-picker cm-checkout-inputs">
				<?php 
			}


		?>

	</div>
	<h3 class="cm-main-label-checkout"><?php  esc_attr_e($main_label); ?></h3>
	<label><?php echo esc_attr__('Delivery Option' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>

	<select class="cm-store-options" name="cm_select_del_option">
		<?php echo $html; ?>
	</select><br><br>
	<div class="cm-address-checkout-fee">
		<h3 class="cm-main-label-checkout"><?php echo esc_attr__('Delivery Address' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></h3>
		
		<input type="checkbox" class="cm-checkbox" id="cm-copy-billing">
		<label for="cm-copy-billing"><?php echo esc_attr__('Copy Billing Address' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>

		<!-- //Address Field	 -->
		<label><?php echo esc_attr__('Street Address' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>
		<input type="text" id="wccpd-street-address" name="custom_address" class="cm-empty-copy-billing cm-checkout-inputs custom-address" ><br>

		<!-- //Town/city Field	 -->
		<label><?php echo esc_attr__('City' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>
		<input type="text" id="wccpd-towncity" name="cm_town/city" class="cm-empty-copy-billing cm-checkout-inputs cm-town-city"><br>

		<!-- //Country Field	 -->
		<label><?php echo esc_attr__('Country' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>
		<input type="text" id="wccpd-country" name="cm_fe_country" class="cm-empty-copy-billing cm-checkout-inputs cm-fe-country"><br>

		<!-- //PostCode	 -->
		<label><?php echo esc_attr__('Zip/Postal Code' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><br>
		<input type="text" id="wccpd-postalcode" name="cm_fe_postcode" class="cm-empty-copy-billing cm-checkout-inputs cm-fe-postcode"><br>

		<?php 

			if (wccpd_is_pro_installed() && wccpd_is_license_valid()) {
				wccpdpro_load_templates('wccpd-delivery-store-pickup.php');
			}

		 ?>


		<button class="cm-calculate-fee btn btn-primary"><?php echo esc_attr__('Calculate Delivery Fee' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></button><br>

		<?php 

			if (!wccpd_is_pro_installed()) {
				?>

				<span class="cm-cost-per-unit"><i><?php echo esc_attr__($text.strtoupper($unit).' '.get_woocommerce_currency_symbol().$cm_cost_per_unit); ?></i></span><br>
				<br><br>
				
				<?php 
			}


		 ?>
		<i><h3 id="cm-distance"></h3></i>
		<div id="map"></div>
	</div>
</div>