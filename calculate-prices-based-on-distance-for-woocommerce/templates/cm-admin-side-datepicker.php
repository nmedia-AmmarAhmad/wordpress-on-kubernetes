<?php 

	//datepicker
	$sunday 	= isset($checkbox_vals['sunday']) ? $checkbox_vals['sunday'] : '';
	$monday 	= isset($checkbox_vals['monday']) ? $checkbox_vals['monday'] : '';
	$tuesday 	= isset($checkbox_vals['tuesday']) ? $checkbox_vals['tuesday'] : '';
	$wednesday 	= isset($checkbox_vals['wednesday']) ? $checkbox_vals['wednesday'] : '';
	$thursday 	= isset($checkbox_vals['thursday']) ? $checkbox_vals['thursday'] : '';
	$friday 	= isset($checkbox_vals['friday']) ? $checkbox_vals['friday'] : '';
	$saturday 	= isset($checkbox_vals['saturday']) ? $checkbox_vals['saturday'] : '';
	
	//PRO
	$cm_show_hide_datepicker 	= isset($checkbox_vals['cm_show_hide_datepicker']) ? $checkbox_vals['cm_show_hide_datepicker'] : '';
	$cm_required_datepicker 	= isset($checkbox_vals['cm_required_datepicker']) ? $checkbox_vals['cm_required_datepicker'] : '';
	//PRO

	$cm_disable_custom_dates 	= isset($checkbox_vals['cm_disable_custom_dates']) ? $checkbox_vals['cm_disable_custom_dates'] : '';

	$sund_req 	= '';
	$mon_req 	= '';
	$tue_req 	= '';
	$wed_req 	= '';
	$thur_req 	= '';
	$fri_req 	= '';
	$sat_req 	= '';
	$showhide = '';
	$required = '';

	if ($sunday == '0') {
		$sund_req = 'checked';
	}
	if ($monday == 1) {
		$mon_req = 'checked';
	}
	if ($tuesday == 2) {
		$tue_req = 'checked';
	}
	if ($wednesday == 3) {
		$wed_req = 'checked';
	}
	if ($thursday == 4) {
		$thur_req = 'checked';
	}
	if ($friday == 5) {
		$fri_req = 'checked';
	}
	if ($saturday == 6) {
		$sat_req = 'checked';
	}
	if ($cm_show_hide_datepicker == 'showhide' && wccpd_is_pro_installed() && wccpd_is_license_valid()) {
		$showhide = 'checked';
	}
	if ($cm_required_datepicker == 'required' && wccpd_is_pro_installed() && wccpd_is_license_valid()) {
		$required = 'checked';
	}

 ?>

<!-- //datepicker settings -->
		<h2 class="wccpd-main-admin-heading"><?php echo esc_attr__('Date Picker Settings' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></h2><hr>
		<input type="hidden" name="action" value="cm_save_form_fields">

    	<div class="row">
			<div class="col-md-3">
				<input type="checkbox" class="cm-checkbox" value="showhide" name="cm_show_hide_datepicker" id="cm-show-hide-datepicker" <?php esc_attr_e($showhide); ?>>
				<label for="cm-show-hide-datepicker"><?php echo esc_attr__('Hide' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
			</div>
			<div class="col-md-7">
				<?php 

					if (!wccpd_is_pro_installed()) {
						?>	
						<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Upgrade To Premium)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
					<?php 
					}
					else if (!wccpd_is_license_valid()){
						?>
						<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Update/Renew Your License)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>

					<?php }

				?>
				<p><i><?php echo esc_attr__('This Feature Enables you to Control The Datepicker To Hide on The Checkout Page' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
			</div>
		</div>
		<br>
		<br><hr>
		<div class="row">
			<div class="col-md-3">
				<input type="checkbox" class="cm-checkbox" value="required" name="cm_required_datepicker" id="cm-required-datepicker" <?php esc_attr_e($required); ?>>
				<label for="cm-required-datepicker"><?php echo esc_attr__('Required' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
			</div>
			<div class="col-md-7">
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
				<p><i><?php echo esc_attr__('This Feature Enables you to Control The Datepicker To Be Mandatory/Not Mandatory To Place an Order on The Checkout Page' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
			</div>
		</div>
		<br>
		<br><hr>

		<h4><?php echo esc_attr__('Select the days you want to disable in the date picker on Checkout page' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></h4>
		<input type="checkbox" class="cm-checkbox" value="0" name="sunday" id="sunday" <?php esc_attr_e($sund_req); ?>>
		<label for="sunday"><?php echo esc_attr__('Sunday' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
		<br>

		<input type="checkbox" class="cm-checkbox" value="1" name="monday" id="monday" <?php esc_attr_e($mon_req); ?>>
		<label for="monday"><?php echo esc_attr__('Monday' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>	
		<br>
		
		<input type="checkbox" class="cm-checkbox" value="2" name="tuesday" id="tuesday" <?php esc_attr_e($tue_req); ?>>
		<label for="tuesday"><?php echo esc_attr__('Tuesday' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>	
		<br>
		
		<input type="checkbox" class="cm-checkbox" value="3" name="wednesday" id="wednesday" <?php esc_attr_e($wed_req); ?>>
		<label for="wednesday"><?php echo esc_attr__('Wednesday' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>	
		<br>
		
		<input type="checkbox" class="cm-checkbox" value="4" name="thursday" id="thursday" <?php esc_attr_e($thur_req); ?>>
		<label for="thursday"><?php echo esc_attr__('Thursday' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>	
		<br>
		
		<input type="checkbox" class="cm-checkbox" value="5" name="friday" id="friday" <?php esc_attr_e($fri_req); ?>>
		<label for="friday"><?php echo esc_attr__('Friday' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>	
		<br>
		
		<input type="checkbox" class="cm-checkbox" value="6" name="saturday" id="saturday" <?php esc_attr_e($sat_req); ?>>
		<label for="saturday"><?php echo esc_attr__('Saturday' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
		<br>
		<br>
		<hr>

<?php 

	if (!empty($cm_disable_custom_dates)) {
		foreach ($cm_disable_custom_dates as $date) {
			?>
			<div class="row cm-disable-date">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Disable Date Slots:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-9">
					<input type="date" name="cm_disable_custom_dates[]" value="<?php echo esc_attr__($date , 'calculate-prices-based-on-distance-for-woocommerce'); ?>"><span class="cm-add-icon cm-add-date-input dashicons dashicons-plus-alt"></span><span class="cm-remove-icon cm-remove-date-input dashicons dashicons-minus"></span>
					<br>
					<br>
				</div>
			</div>
		<?php }
	}
	else{?>
		<div class="row cm-disable-date">
			<div class="col-md-3">
				<label><?php echo esc_attr__('Disable Date Slots:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
			</div>
			<div class="col-md-9">
				<input type="date" name="cm_disable_custom_dates[]" value=""><span class="cm-add-icon cm-add-date-input dashicons dashicons-plus-alt"></span><span class="cm-remove-icon cm-remove-date-input dashicons dashicons-minus"></span>
				<br>
				<br>
			</div>
		</div>
<?php }