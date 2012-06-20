<?php
	
	//remove DB tables
		global $wpdb;
		$remove_table_sql[0] = "DROP TABLE `wp_ballots_by_social_ballots`;";
		$remove_table_sql[1] = "DROP TABLE `wp_ballots_by_social_answers`;";
		$remove_table_sql[2] = "DROP TABLE `wp_ballots_by_social_votes`;";
		foreach($remove_table_sql as $sql) {
			$wpdb->query($sql);
		}

?>
