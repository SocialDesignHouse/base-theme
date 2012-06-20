<?php //include ballot class
require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/Ballot.class.php");

//display add ballot page
function ballots_by_social_new_ballot_page() { ?>
	
	<div class="wrap">
		<h2>Add Ballot</h2>
		
		<?php //set up ballot
		$new_ballot = new Ballot();
		if($_REQUEST['submit']) {
			$new_ballot->process_form();
		}
		$new_ballot->new_ballot_form();
		?>
		
	</div>
	
<?php } ?>