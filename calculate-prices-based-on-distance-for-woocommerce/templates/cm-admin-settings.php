<?php 
	
	// wccpd_pa($checkbox_vals);

	$cm_address_apikey = isset($checkbox_vals['address_settings']['cm_address_apikey']) ? $checkbox_vals['address_settings']['cm_address_apikey'] : '';
	
	$cm_cost_per_unit = isset($checkbox_vals['address_settings']['cm_cost_per_unit']) ? $checkbox_vals['address_settings']['cm_cost_per_unit'] : 1;
	
	$cm_select_unit = isset($checkbox_vals['address_settings']['cm_select_unit']) ? $checkbox_vals['address_settings']['cm_select_unit'] : '';

	//streeet address 2
	$cm_shop_address = isset($checkbox_vals['address_settings']['cm_shop_address']) ? $checkbox_vals['address_settings']['cm_shop_address'] : '';

	//streeet address 2
	$cm_shop_address_two = isset($checkbox_vals['address_settings']['cm_shop_address_two']) ? $checkbox_vals['address_settings']['cm_shop_address_two'] : '';

	//streeet address town city
	$cm_shop_address_town_city = isset($checkbox_vals['address_settings']['cm_shop_address_town_city']) ? $checkbox_vals['address_settings']['cm_shop_address_town_city'] : '';

	//streeet address country
	$cm_shop_address_country = isset($checkbox_vals['address_settings']['cm_shop_address_country']) ? $checkbox_vals['address_settings']['cm_shop_address_country'] : '';

	//streeet address postcode
	$cm_shop_address_postcode = isset($checkbox_vals['address_settings']['cm_shop_address_postcode']) ? $checkbox_vals['address_settings']['cm_shop_address_postcode'] : '';

	//dielivery label on checkout
	$cm_delivery_label = isset($checkbox_vals['address_settings']['cm_delivery_label']) ? $checkbox_vals['address_settings']['cm_delivery_label'] : '';
	$cm_fix_price = isset($checkbox_vals['address_settings']['cm_fix_price']) ? $checkbox_vals['address_settings']['cm_fix_price'] : '';

	//PRO
	$cm_restrict_per_unit = isset($checkbox_vals['address_settings']['cm_restrict_per_unit']) ? $checkbox_vals['address_settings']['cm_restrict_per_unit'] : '';
	$wccpd_from_cost_pro = isset($checkbox_vals['address_settings']['wccpd_from_cost_pro']) ? $checkbox_vals['address_settings']['wccpd_from_cost_pro'] : '';

	$cm_delivery_opt = isset($checkbox_vals['address_settings']['cm_delivery_opt']) ? $checkbox_vals['address_settings']['cm_delivery_opt'] : 'cm_delivery_opt';
	$cm_store_pickup_opt = isset($checkbox_vals['address_settings']['cm_store_pickup_opt']) ? $checkbox_vals['address_settings']['cm_store_pickup_opt'] : 'cm_store_pickup_opt';
	

	if (wccpd_is_pro_installed() && wccpd_is_license_valid()) {
		$cm_delivery_and_store_pickup_opt = isset($checkbox_vals['address_settings']['cm_delivery_and_store_pickup_opt']) ? $checkbox_vals['address_settings']['cm_delivery_and_store_pickup_opt'] : '';
		$cm_map_calculator_req = isset($checkbox_vals['address_settings']['cm_map_calculator_req']) ? $checkbox_vals['address_settings']['cm_map_calculator_req'] : '';
	}
	else{
		$cm_delivery_and_store_pickup_opt = '';
		$cm_map_calculator_req = '';
	}


 ?>
	<div id="cm-tabs" class="cm-container">
	<form class="cm-save-admin-settings">
	  <ul class="nav nav-tabs">
	    <li class="active"><a data-toggle="tab" class="active" href="#datepicker"><div class="wk_icon"><i class="fa fa fa-calendar"></i></div><?php echo esc_attr__('Date Picker' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></a></li>
	    <li><a data-toggle="tab" href="#cm_timepicker"><div class="wk_icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div><?php echo esc_attr__('Time Picker' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></a></li>
	    <li><a data-toggle="tab" href="#address-sett"><div class="wk_icon"><i class="fa fa-road" aria-hidden="true"></i></div><?php echo esc_attr__('Distance Calculator' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></a></li>
	    <?php 

	    	if (wccpd_is_pro_installed()) {
	    		?>
	    			<li><a data-toggle="tab" href="#activate-license"><div class="wk_icon"><span class="dashicons dashicons-unlock"></span></div><?php echo esc_attr__('Activate License' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></a></li>
	    		<?php 
	    	}


	     ?>

	  </ul>

	  <div class="tab-content">
	    <div id="datepicker" class="show tab-pane fade in active">
	    			<!-- datepicker -->
	    	<?php 
	    		wccpd_load_templates('cm-admin-side-datepicker.php' , array('checkbox_vals' => $checkbox_vals));
	    	?>
	    	<br>
	    </div>
	    <div id="cm_timepicker" class="show tab-pane fade">
	      
	    	<!-- timepicker -->
	    	<?php 
	    		wccpd_load_templates('cm-admin-side-timepicker.php' , array('checkbox_vals' => $checkbox_vals));
	    	?>
	    	<br>
	    </div>
	    <div id="address-sett" class="show tab-pane fade">
	    	<h2 class="wccpd-main-admin-heading"><?php echo esc_attr__('Delivery Distance' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></h2><hr>

	    	<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Google Maps API Key:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-5">
					<input type="text" class="cm-api-key" name="cm_address_apikey" value="<?php  esc_attr_e($cm_address_apikey); ?>">
				</div>
				<div class="col-md-4">
					<a href="https://developers.google.com/maps/documentation/javascript/get-api-key"><?php echo esc_attr__('How to get an API key' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></a>
				</div>
			</div>
			<br>

			<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Select Distance Unit:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-4">
					<select name="cm_select_unit">
			    		<option value="km"<?php echo selected($cm_select_unit,'km', false); ?> ><?php echo esc_attr__('KM' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></option>
			    		<option value="mile" <?php echo selected($cm_select_unit,'mile', false); ?> ><?php echo esc_attr__('Mile' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></option>
			    	</select>
				</div>
			</div>
			<br>

			<?php 

				if (!wccpd_is_pro_installed() || !wccpd_is_license_valid()) {
					?>
						<div class="row">
							<div class="col-md-3">
								<label><?php echo esc_attr__('Cost Per('.strtoupper($cm_select_unit).'):' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
							</div>
							<div class="col-md-4">
								<?php echo ' '.get_woocommerce_currency_symbol(); ?>
								<input type="number" class="cm-restrict-width" name="cm_cost_per_unit" step="0.01" value="<?php esc_attr_e($cm_cost_per_unit) ?>">
							</div>
						</div>

						<br>

						<div class="row">
							<div class="col-md-3">
								<label><?php echo esc_attr__('Fixed Price:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
							</div>
							<div class="col-md-4">
								<input type="checkbox" class="cm-fix-price" name="cm_fix_price" value="cm_fix_price" <?php echo checked($cm_fix_price , 'cm_fix_price' , false); ?>>
							</div>
							<div class="col-md-4">
								<p><i><?php echo esc_attr__('If you check this there will be FIXED delivery fee and NOT per '.strtoupper($cm_select_unit).' ' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
							</div>
						</div>

						<br>
					<?php 
				}



			 ?>
	    	<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Set Cost Per Your Need:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-9">
					<?php 

						if (!wccpd_is_pro_installed()) {
							?>	
							<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Upgrade To Premium)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
						<?php 
						}
						else if (!wccpd_is_license_valid()){
							?>
							<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Update/Renew Your License)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
						<?php 
						}

					?>
					<table class="table">
						<tr>
							<th><?php echo esc_attr__('From ('.strtoupper($cm_select_unit).')' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></th>
							<th><?php echo esc_attr__('To ('.strtoupper($cm_select_unit).')' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></th></th>
							<th><?php echo esc_attr__('Cost Per ('.strtoupper($cm_select_unit).')' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></th></th>
							<th><?php echo esc_attr__('Fixed Price' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></th></th>
							<th><?php echo esc_attr__('Action' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></th></th>
						</tr>
							<?php 

								$lenght = isset($wccpd_from_cost_pro['from']) ? $wccpd_from_cost_pro['from'] : array();
		  
								$count = count($lenght);
								
								if (empty($wccpd_from_cost_pro) || $count==0 ) {
									?>
									
									<tr class="wccpd-clone-tr">
										<td><input type="number" class="cm-restrict-width" name="wccpd_from_cost_pro[from][]" value=""></td>
										<td><input type="number" class="cm-restrict-width" name="wccpd_from_cost_pro[to][]" value=""></td>
										<td><input type="number" class="cm-restrict-width" name="wccpd_from_cost_pro[cost][]" step="0.01" value=""><?php echo get_woocommerce_currency_symbol(); ?></td>
										<td><input type="checkbox" name="wccpd_from_cost_pro[fixed_price][]"></td>
										<td>
											<span class="cm-add-icon wccpd-add-cost dashicons dashicons-plus-alt"></span><span class="cm-remove-icon wccpd-remove-cost dashicons dashicons-minus"></span>
										</td>
									</tr>
									<?php 
								
								}
								else{
									
									if (wccpd_is_pro_installed() && wccpd_is_license_valid()) {
										$ob = new WCCPD_Calculate_Miles_Pro;
										$ob->get_miles_cost_html($wccpd_from_cost_pro , $count);
									}	
									
								}


							 ?>
						
					</table>
					
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Restrict User By('.strtoupper($cm_select_unit).'):' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-4">
					<input type="number" class="cm-restrict-width" name="cm_restrict_per_unit" step="0.01" value="<?php esc_attr_e($cm_restrict_per_unit); ?>">
					<?php esc_attr_e(strtoupper($cm_select_unit)); ?>
					<?php 

						if (!wccpd_is_pro_installed()) {
							?>	
							<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Upgrade To Premium)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
						<?php 
						}
						else if(!wccpd_is_license_valid()){
							?>
							<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Update/Renew Your License)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
						<?php }

					?>
					
				</div>
				<div class="col-md-4">
					<p><i><?php echo esc_attr__('This Feature Allows you to Display A message To the Customer "Sorry We Do Not Deliver More Than X Miles/KM"' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
				</div>
			</div><br>
	    	<div class="row">
				<div class="col-md-3">
					<label for="cm_map_calculator_req"><?php echo esc_attr__('Required:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-4">
					<input type="checkbox" id="cm_map_calculator_req" name="cm_map_calculator_req" value="cm_map_calculator_req" <?php echo checked($cm_map_calculator_req , 'cm_map_calculator_req' , false); ?>><?php 

						if (!wccpd_is_pro_installed()) {
							?>	
							<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Upgrade To Premium)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
						<?php 
						}

					 ?>
				</div>
				<div class="col-md-5">
					<p><i><?php echo esc_attr__('This Feature Allows you to Force the User to Add Delivery Fee to the Order. Without Delivery Fee they cannot Place the Order.' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
				</div>
			</div>

			<br>

			<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Street Address:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-5">
					<input type="text" class="wccpd-address-width-adjust" id="cm_shop_address" name="cm_shop_address" value="<?php esc_attr_e($cm_shop_address); ?>">
				</div>
			</div>

			<br>
	    	<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Street Address 2:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-5">
					<input type="text" class="wccpd-address-width-adjust" name="cm_shop_address_two" value="<?php esc_attr_e($cm_shop_address_two); ?>">
				</div>
			</div>

			<br>
	    	<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('City:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-5">
					<input type="text" class="wccpd-address-width-adjust" id="cm_shop_address_town_city" name="cm_shop_address_town_city" value="<?php esc_attr_e($cm_shop_address_town_city); ?>">
				</div>
			</div>

			<br>
	    	<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Country:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-5">
					<input type="text" class="wccpd-address-width-adjust" id="cm_shop_address_country" name="cm_shop_address_country" value="<?php  esc_attr_e($cm_shop_address_country); ?>">
				</div>
			</div>

			<br>
	    	<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Zip/Postal code:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-5">
					<input type="text" class="wccpd-address-width-adjust" id="cm_shop_address_postcode" name="cm_shop_address_postcode" value="<?php esc_attr_e($cm_shop_address_postcode); ?>">
				</div>
			</div>

			<br>
	    	<div class="row">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Delivery Label (Checkout):' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-5">
					<input type="text" class="wccpd-address-width-adjust" placeholder="Delivery" name="cm_delivery_label" value="<?php esc_attr_e($cm_delivery_label); ?>">
				</div>
			</div>

			<br>
			<hr>
			<h2 class="wccpd-main-admin-heading"><?php echo esc_attr__('Delivery Options:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></h2><br>
	    	<div class="row">
				<div class="col-md-3">
					<label for="cm_delivery_opt"><?php echo esc_attr__('Delivery:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-4">
					<input id="cm_delivery_opt" type="checkbox" name="cm_delivery_opt" value="cm_delivery_opt" <?php echo checked($cm_delivery_opt , 'cm_delivery_opt' , false); ?> >
				</div>
				<div class="col-md-5">
					<p><i><?php echo esc_attr__('This Feature Allows you to Display a Map with Location of the admin side address.' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3">
					<label for="cm_store_pickup_opt"><?php echo esc_attr__('Store Pickup:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-4">
					<input type="checkbox" id="cm_store_pickup_opt" name="cm_store_pickup_opt" value="cm_store_pickup_opt" <?php echo checked($cm_store_pickup_opt , 'cm_store_pickup_opt' , false); ?>>
				</div>
				<div class="col-md-5">
					<p><i><?php echo esc_attr__('This Feature Allows you to Display Only Date and timepicker if enabled by admin' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3">
					<label for="cm_delivery_and_store_pickup_opt"><?php echo esc_attr__('Delivery And Pickup:'  , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label><?php 

						if (!wccpd_is_pro_installed()) {
							?>	
							<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Upgrade To Premium)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
						<?php 
						}
						else if (!wccpd_is_license_valid()){
							?>
							<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Update/Renew Your License)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
						<?php 
						}

					?>
				</div>
				<div class="col-md-4">
					<input type="checkbox" id="cm_delivery_and_store_pickup_opt" name="cm_delivery_and_store_pickup_opt" value="cm_delivery_and_store_pickup_opt" <?php echo checked($cm_delivery_and_store_pickup_opt , 'cm_delivery_and_store_pickup_opt' , false); ?>>
				</div>
				<div class="col-md-5">
					<p><i><?php echo esc_attr__('This Features allows the user to add both the addresses to calculate the distance between two addresses.' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
				</div>
			</div>
	    	
	    </div>
	    <div id="activate-license" class="show tab-pane fade">
	    	<h2 class="wccpd-main-admin-heading"><?php echo esc_attr__('Activate License' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></h2>
	    	<?php 

	    		if (wccpd_is_pro_installed()) {
			        wccpdpro_load_templates('wccpd-pro-licence-template.php'); //COMING FROM PRO PLUGIN
			    }

	    	 ?>
	    </div>
		 <div class="document_btns"><a href="http://woo-solutions.ca/wordpress-plugins/"><?php echo esc_attr__('Documentation' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></a>
          <input type="submit" class="btn btn-primary cm-custom-btn" value="Save"></div>
  </div>
  </form>
</div>