<?php //include ballot class
require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/Ballot.class.php");

//display edit ballot page
function ballots_by_social_ballot_results_page($id) { ?>

	<div class="wrap">
		<h2>Ballot Results</h2>

		<?php //set up form
		$result_ballot = new Ballot();
		$result_ballot->ballot_results($id); ?>

	</div>

<?php } ?>