<?php
	
	$ballot_id = $_POST['bid'];
	$voter_id = $_POST['vid'];
	$vote_id = $_POST['vote'];
	
	$uri = substring($_SERVER['SCRIPT_URI'],7);
	$uri_array = explode("/",$uri);
	$uri_count = count($uri_array);
	unset($uri_array[$uri_count-1]);
	unset($uri_array[0]);
	print_r($uri_array);
	echo '<br />' . implode("/",$uri_array);
	//$new_uri = implode("/",$uri_array) . "/Ballot.class.php";
	
	//echo $new_uri;
	
	//include ballot class
	//include_once($new_uri);
	
	$process_ballot = new Ballot();
	$process_ballot->process_vote($vote_id,$ballot_id,$voter_id);
	

?>