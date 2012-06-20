<?php //include ballots class
require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/Ballot.class.php");

//display ballots page
function ballots_by_social_ballots_page() {
	global $wpdb; ?>
	
	<div class="wrap">
	
		<?php if($_GET['action'] == "edit") { ?>
	
			<?php require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/edit-ballot.php");
			ballots_by_social_edit_ballot_page($_GET['id']); ?>
	
		<?php } elseif($_GET['action'] == "results") { ?>
		
			<?php require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/ballot-results.php");
			ballots_by_social_ballot_results_page($_GET['id']); ?>
		
		<?php } elseif($_GET['action'] == "deactivate") { ?>

			<?php require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/ballot-deactivate.php");
			ballots_by_social_ballot_results_page($_GET['id']); ?>

		<?php } elseif($_GET['action'] == "activate") { ?>

			<?php require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/ballot-activate.php");
			ballots_by_social_ballot_results_page($_GET['id']); ?>

		<?php } else { ?>
	
			<h2>Ballots by Social</h2>
			<div class="tablenav">
				<div class="alignleft actions">
					<a class="row-title" href="/wp-admin/admin.php?page=social-ballots-options">Settings</a>
					&nbsp;|&nbsp;
					<a class="row-title" href="/wp-admin/admin.php?page=social-ballots-new">New Ballot</a>
				</div>
			</div>
			<h3>Ballot Info</h3>
		
			<?php //get ballots from db
			$ballots = $wpdb->get_results("SELECT * FROM `wp_ballots_by_social_ballots`");
			$total_ballots = $wpdb->num_rows;
			if($total_ballots > 0) {
				$output = '<table class="widefat" style="margin-top: .5em">';
					$output .= '<thead>';
						$output .= '<tr>';
							$output .= '<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>';
							$output .= '<th width="5%"><center>ID</center></th>';
							$output .= '<th width="15%">Question</th>';
							$output .= '<th width="15%">Start Date</th>';
							$output .= '<th width="15%">End Date</th>';
							$output .= '<th width="15%">Shortcode</th>';
							$output .= '<th width="35%">Actions</th>';
						$output .= '</tr>';
					$output .= '</thead>';
					$output .= '<tbody>';
					foreach($ballots as $ballot) {
						$this_ballot = new Ballot();
						$output .= $this_ballot->output_ballot_menu($ballot->ballot_id);
					}
					$output .= '</tbody>';
				$output .= '</table>';
				echo $output;
			} else {
				echo '<p>There are currently no ballots.</p>';
			} ?>
	
		<?php } ?>
		
	</div>
	
<?php } ?>