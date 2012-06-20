<?php //include ballot class
require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/Ballot.class.php");

//display edit ballot page
function ballots_by_social_edit_ballot_page($id) { ?>

	<div class="wrap">
		<h2>Ballots by Social</h2>
		<div class="tablenav">
			<div class="alignleft actions">
				<a class="row-title" href="/wp-admin/admin.php?page=social-ballots-options">Settings</a>
				&nbsp;|&nbsp;
				<a class="row-title" href="/wp-admin/admin.php?page=social-ballots-new">New Ballot</a>
			</div>
		</div>
		<h3>Edit Ballot</h3>

		<?php //set up form
		$edit_ballot = new Ballot();
		if($_REQUEST['submit']) {
			$edit_ballot->process_form();
		}
		$edit_ballot->edit_ballot($id); ?>

	</div>

<?php } ?>