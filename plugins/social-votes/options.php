<?php //display options page
function ballots_by_social_options_page() { ?>
	
	<div class="wrap">
		<h2>Votes by Social Options</h2>
		
		<?php if($_REQUEST['submit']) {
			ballots_by_social_update_options();
		}
		ballots_by_social_display_option_form(); ?>
	
	</div>
	
<?php }

//display options form
function ballots_by_social_display_option_form() { 
	$default_answers_number_value = get_option('ballots_by_social_default_answers_number');
	$default_max_timeframe = get_option('ballots_by_social_years_in_timeframe'); ?>
	
	<form method="post">
		<label for="default_answers_number_value">Number of Default Answers:
			<input type="text" name="default_answers_number_value" id="default_answers_number_value" value="<?php echo $default_answers_number_value?>" />
		</label>
		<label for="max_years_in_timeframe">Maximum Years in Timeframe:
			<input type="text" name="max_years_in_timeframe" id="max_years_in_timeframe" value="<?php echo $default_max_timeframe?>" />
		</label>
		<input type="submit" name="submit" value="Update" />
	</form>
	
<?php }

//update options
function ballots_by_social_update_options() {
	$ok = false;
	
	if($_REQUEST['default_answers_number_value']) {
		update_option('ballots_by_social_default_answers_number',$_REQUEST['default_answers_number_value']);
		$ok = true;
	}
	
	if($_REQUEST['max_years_in_timeframe']) {
		update_option('max_years_in_timeframe',$_REQUEST['max_years_in_timeframe']);
		$ok = true;
	}

	if($ok) { ?>
		
		<div id="message" class="updated fade">
			<p>Options saved.</p>
		</div>
	
	<?php } else { ?>
		
		<div id="message" class="error fade">
			<p>Failed to save options.</p>
		</div>
	
	<?php }	
} ?>