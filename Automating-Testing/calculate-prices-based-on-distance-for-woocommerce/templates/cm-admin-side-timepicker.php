<?php 

	$cm_start_time = isset($checkbox_vals['cm_timepicker_settings']['cm_start_time']) ? $checkbox_vals['cm_timepicker_settings']['cm_start_time'] : '';

	$cm_end_time = isset($checkbox_vals['cm_timepicker_settings']['cm_end_time']) ? $checkbox_vals['cm_timepicker_settings']['cm_end_time'] : '';

	$cm_select_format = isset($checkbox_vals['cm_timepicker_settings']['cm_select_format']) ? $checkbox_vals['cm_timepicker_settings']['cm_select_format'] : '';

	$cm_time_interval = isset($checkbox_vals['cm_timepicker_settings']['cm_time_interval']) ? $checkbox_vals['cm_timepicker_settings']['cm_time_interval'] : '';

	$cm_disable_time = isset($checkbox_vals['cm_timepicker_settings']['cm_disable_time']) ? $checkbox_vals['cm_timepicker_settings']['cm_disable_time'] : '';

	//PRO
	$cm_show_hide_timepicker 	= isset($checkbox_vals[
		'cm_timepicker_settings']['cm_show_hide_timepicker']) ? $checkbox_vals[
		'cm_timepicker_settings']['cm_show_hide_timepicker'] : '';
	$cm_required_timepicker 	= isset($checkbox_vals[
		'cm_timepicker_settings']['cm_required_timepicker']) ? $checkbox_vals[
		'cm_timepicker_settings']['cm_required_timepicker'] : '';
	//PRO
	$showhidetime = '';
	$requiredtime = '';
	if ($cm_show_hide_timepicker == 'showhidetime' && wccpd_is_pro_installed() && wccpd_is_license_valid()) {
		$showhidetime = 'checked';
	}
	if ($cm_required_timepicker == 'requiredtime' && wccpd_is_pro_installed() && wccpd_is_license_valid()) {
		$requiredtime = 'checked';
	}

?>

<h2 class="wccpd-main-admin-heading"><?php echo esc_attr__('Time Picker Settings' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></h2><hr>


<div class="row">
	<div class="col-md-3">
		<input type="checkbox" class="cm-checkbox" value="showhidetime" name="cm_show_hide_timepicker" id="cm-show-hide-timepicker" <?php esc_attr_e($showhidetime); ?>>
		<label for="cm-show-hide-timepicker"><?php echo esc_attr__('Hide' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
	</div>
	<div class="col-md-7">
		<?php 

			if (!wccpd_is_pro_installed()) {
				?>	
				<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Upgrade To Premium)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>
			<?php 
			}
			else if(!wccpd_is_license_valid()){?>
				
				<a class="wccpd-pro" href="http://woo-solutions.ca/wordpress-plugins/"><?php esc_attr_e('(Update/Renew Your License)' , 'calculate-prices-based-on-distance-for-woocommerce');  ?></a>

			<?php }

		?>
		<p><i><?php echo esc_attr__('This Feature Enables you to Control The TImepicker To Hide on The Checkout Page' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
	</div>
</div>
<br>
<br>
<hr>

<div class="row">
	<div class="col-md-3">
		<input type="checkbox" class="cm-checkbox" value="requiredtime" name="cm_required_timepicker" id="cm-required-timepicker" <?php esc_attr_e($requiredtime); ?>>
		<label for="cm-required-timepicker"><?php echo esc_attr__('Required' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
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
		<p><i><?php echo esc_attr__('This Feature Enables you to Control The Timepicker To Be Mandatory/Not Mandatory To Place an Order on The Checkout Page' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
	</div>
</div>
<br>
<br>
<hr>

<div class="row">
	<div class="col-md-3">
		<label><?php echo esc_attr__('Start Time:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?> </label>
	</div>
	<div class="col-md-3">
		<input type="time" name="cm_start_time" value="<?php esc_attr_e($cm_start_time); ?>">
	</div>
	<div class="col-md-3">
		<p><i><?php echo esc_attr__('By Default Start Time is 12:00AM/(00:00)' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
	</div>
</div>

<br>
<div class="row">
	<div class="col-md-3">
		<label><?php echo esc_attr__('End Time:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?> </label>
	</div>
	<div class="col-md-3">
		<input type="time" name="cm_end_time" value="<?php esc_attr_e($cm_end_time); ?>">	
	</div>
	<div class="col-md-3">
		<p><i><?php echo esc_attr__('By Default End Time is 12:00PM/(12:00)' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
	</div>
</div>

<br>
<div class="row">
	<div class="col-md-3">
		<label><?php echo esc_attr__('Time Format:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
	</div>
	<div class="col-md-4">
		<select name="cm_select_format">
			<option value="12Hour" <?php echo selected($cm_select_format,'12Hour', false); ?>><?php echo esc_attr__('12 Hours' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></option>
			<option value="24Hour" <?php echo selected($cm_select_format,'24Hour', false); ?>><?php echo esc_attr__('24 Hours' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></option>
		</select>	
	</div>
</div>

<br>
<div class="row">
	<div class="col-md-3">
		<label><?php echo esc_attr__('Time Interval (in minutes):' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
	</div>
	<div class="col-md-3">
		<input type="number" class="cm-restrict-width" name="cm_time_interval" min="1" value="<?php  esc_attr_e($cm_time_interval); ?>">
	</div>
	<div class="col-md-3">
		<p><i><?php echo esc_attr__('By Default Time Interval is 15(minutes)' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></i></p>
	</div>
</div>

<br>
<?php 

	if (!empty($cm_disable_time)) {
		foreach ($cm_disable_time as $time) {
			?>
			<div class="row cm-disable-time">
				<div class="col-md-3">
					<label><?php echo esc_attr__('Disable Time Slots:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
				</div>
				<div class="col-md-9">
					<input type="time" name="cm_disable_time[]" value="<?php echo esc_attr__($time , 'calculate-prices-based-on-distance-for-woocommerce'); ?>"><span class="cm-add-icon cm-add-time-input dashicons dashicons-plus-alt"></span><span class="cm-remove-icon cm-remove-time-input dashicons dashicons-minus"></span>
				</div>
				<br>
				<br>
			</div>
		<?php }
	}
	else{?>
		<div class="row cm-disable-time">
			<div class="col-md-3">
				<label><?php echo esc_attr__('Disable Time Slots:' , 'calculate-prices-based-on-distance-for-woocommerce'); ?></label>
			</div>
			<div class="col-md-9">
				<input type="time" name="cm_disable_time[]" value=""><span class="cm-add-icon cm-add-time-input dashicons dashicons-plus-alt"></span><span class="cm-remove-icon cm-remove-time-input dashicons dashicons-minus"></span>
				<br>
				<br>
			</div>
		</div>
	<?php }