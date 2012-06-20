<?php
	/*
	Plugin Name: Ballots by Social
	Plugin URI: http://apps.socialdesignhouse.com/plugins/ballots/
	Description: A voting system for Wordpress sites that allows write-ins and uses shortcode embedding for polls.
	Version: 1.0b
	Author: Eric Allen of Social Design House
	Author URI: http://socialdesignhouse.com/
	License: GPL2
	*/
	
	/*--------------------------------------------------- Change Log -----------------------------------------------------
		
	 +	2012-04-24		v1.2b		Added limiting number of multiple choice selections.
	
	 +	2012-04-18		v1.0b		Made output fit in with Wordpress backend. Set up default text removal for Answer
									and Question inputs. All other base-level functionality is working as expected.
									
	--------------------------------------------------------------------------------------------------------------------*/

//VARS
	if(!defined("CURRENT_PAGE")) {
		define("CURRENT_PAGE", basename($_SERVER['PHP_SELF']));
	}

//INCLUDE CLASS
	require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/Ballot.class.php");

//SHORTCODE
	//display ballot shortcode output
	function ballots_by_social_display_ballot_by_shortcode($atts) {
		global $wpdb;
		//get id from short code
		extract(
			shortcode_atts(
				array(
					'id' => 'NULL'
				), $atts
			)
		);
		//make sure user is registered
		if(is_user_logged_in()) {
			$user = wp_get_current_user();
			$user_id = $user->ID;
			//set up ballot id and voter id for encoding
			$vote_id = "b" . $id . "|u" . $user_id;
			//encode for anonymity
			//create new ballot object
			$show_ballot = new Ballot();
			$show_this_ballot = $show_ballot->process_shortcode($id,$vote_id);
			return $show_this_ballot;
		} else {
			$ballot = new Ballot();
			$ballot->get_ballot_info($id);
			$msg = '<p>Sorry, you must log-in or register to vote on "<strong>' . $ballot->question . '</strong>"</p>';
			return $msg;
		}
	}
	
//INSTALLATION, ACTIVATION, & DEACTIVATION
	//set up shortcode for ballot display
	add_shortcode('ballot','ballots_by_social_display_ballot_by_shortcode');
	//set up front-end js for ballot display and enqueue it
	function load_ballot_js() {
		wp_register_script('ballot_js',plugins_url('/social-votes/js/process-ballot.js'),array('jquery'),'',true);
		wp_enqueue_script('ballot_js');
		//set up ajaxurl variable so we don't have to make our .js file a .js.php file to set paths
		wp_localize_script( 'ballot_js','BallotJS',array('ajaxurl' => admin_url('admin-ajax.php')));
	}
	//run this when queueing up scripts
	add_action('wp_enqueue_scripts','load_ballot_js');
	//set up back-end js for ballot display and enqueue it
	function load_ballot_admin_js() {
		wp_register_script('ballot_admin_js',plugins_url('/social-votes/js/admin-ballot.js'),array('jquery'),'',true);
		wp_enqueue_script('ballot_admin_js');
		//set up ajaxurl variable so we don't have to make our .js file a .js.php file to set paths
		wp_localize_script( 'ballot_admin_js','BallotJS',array('ajaxurl' => admin_url('admin-ajax.php')));
	}
	//run this when queueing up scripts
	add_action('admin_enqueue_scripts','load_ballot_admin_js');
	//set up back-end css for ballot display and enqueue it
	function load_ballot_admin_css() {
		wp_register_style('ballot_admin_css',plugins_url('/social-votes/css/admin-ballot.css'));
		wp_enqueue_style('ballot_admin_css');
	}
	//run this when queueing up scripts
	add_action('admin_print_styles','load_ballot_admin_css');
/*	//adding embed ballot button to post editor
	function wp_ballots_by_social_add_ballot_button($context) {
		$is_post_edit_page = in_array(CURRENT_PAGE,array('post.php', 'page.php', 'page-new.php', 'post-new.php'));
		if(!$is_post_edit_page) {
			return $context;
		}
		$image = "/images/form-button.png";
		$out = ' <a href="#TB_inline?width=480&inlineId=select_ballot" class="thickbox" id="add_ballot_by_social" title="Add Ballot"><img src="' . $image . '" alt="Add Ballot" /></a>';
		return $context . $out;
	}
	//run this function when displaying media buttons
	add_action('media_buttons_context','wp_ballots_by_social_add_ballot_button');
	
	//setting up pop-up for embed ballot button
    function add_ballot_selector_popup() { ?>
	
		<script type="text/javascript">
			function InsertBallot() {
				var ballot_id = jQuery("#add_ballot_id").val();
				if(form_id == ""){
					alert("Please select a ballot.");
					return;
				}
				window.send_to_editor("[ballot id=\"" + ballot_id + "\"]");
			}
		</script>

		<div id="select_ballot" style="display:none;">
			<div class="wrap">
				<div>
					<div style="padding:15px 15px 0 15px;">
						<h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;">Insert A Ballot</h3>
						<span>Select a ballot below to add it to your post or page.</span>
					</div>
					<div style="padding:15px 15px 0 15px;">
						<select id="add_ballot_id">
							<option value="">Select a Ballot</option>
							
							<?php //output ballots
							global $wpdb;
							$ballots = $wpdb->get_results("SELECT * FROM `wp_ballots_by_social_ballots` WHERE ballot_active = 1");
							foreach($ballots as $ballot) { ?>
									
								<option value="<?php echo $ballot->$ballot_id; ?>"><?php echo $ballot->$ballot_question; ?></option>
								
							<?php } ?>
						
						</select>
						<br/>
						<div style="padding:8px 0 0 0; font-size:11px; font-style:italic; color:#5A5A5A">Can't find your ballot? Make sure it is active.</div>
					</div>
					<div style="padding:15px;">
						<input type="button" class="button-primary" value="Insert Ballot" onclick="InsertBallot();"/>&nbsp;&nbsp;&nbsp;
						<a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;">Cancel</a>
					</div>
				</div>
			</div>
		</div>

	<?php }
	
	//run this function when on the editor pages
	if(in_array(CURRENT_PAGE, array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
		//run this function when setting up the admin footer
		add_action('admin_footer', 'add_ballot_selector_popup');
	}
*/	
	//add plug-in options
	function ballots_by_social_set_options() {
		add_option('ballots_by_social_default_answers_number',5);
		add_option('ballots_by_social_default_question','What are we voting for?');
		add_option('ballots_by_social_default_answer','Leave this text to remove this answer.');
		add_option('ballots_by_social_years_in_timeframe',5);
		$ballot_table_sql = "CREATE TABLE `wp_ballots_by_social_ballots` (
		  `ballot_id` int(12) NOT NULL auto_increment,
		  `ballot_question` mediumtext NOT NULL,
		  `ballot_select` varchar(255) NOT NULL default 'single',
		  `ballot_write_ins` int(1) NOT NULL default '0',
		  `ballot_abstain` int(1) NOT NULL default '0',
		  `ballot_num_choices` int(5) NOT NULL default '0',
		  `ballot_start` date NOT NULL,
		  `ballot_end` date NOT NULL,
		  `ballot_active` int(1) NOT NULL default '0',
		  PRIMARY KEY  (`ballot_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		create_table('wp_ballots_by_social_ballots',$ballot_table_sql);
		$answer_table_sql = "CREATE TABLE `wp_ballots_by_social_answers` (
		  `answer_id` int(12) NOT NULL auto_increment,
		  `ballot_id` int(12) NOT NULL,
		  `answer_body` mediumtext NOT NULL,
		  PRIMARY KEY  (`answer_id`),
		  KEY `ballot_id` (`ballot_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		create_table('wp_ballots_by_social_answers',$answer_table_sql);
		$vote_table_sql = "CREATE TABLE `wp_ballots_by_social_votes` (
		  `vote_id` int(12) NOT NULL auto_increment,
		  `ballot_id` int(12) NOT NULL,
		  `vote_body` mediumtext NOT NULL,
		  `vote_voter_id` varchar(255) NOT NULL,
		  PRIMARY KEY  (`vote_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		create_table('wp_ballots_by_social_votes',$vote_table_sql);
		//register the shortcode handler
	}
	//run when plug-in is activated
	register_activation_hook(__FILE__,'ballots_by_social_set_options');
	
	function create_table($the_table, $sql) {
		global $wpdb;
		if($wpdb->get_var("show tables like '". $the_table . "'") != $the_table) {
			$wpdb->query($sql);
		}
	}
	
	//remove plug-in options
	function ballots_by_social_unset_options() {
		delete_option('ballots_by_social_default_answers_number');
		delete_option('ballots_by_social_default_question');
		delete_option('ballots_by_social_default_answer');
		delete_option('ballots_by_social_years_in_timeframe');
		remove_shortcode('ballot');
	}
	//run when plug-in is deactivated
	register_deactivation_hook(__FILE__,'ballots_by_social_unset_options');
	
//ADMIN MENU & OPTIONS
	//include files for each page
	require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/ballots.php");
	require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/options.php");
	require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/new-ballot.php");
	//add the item to the admin menu
	function ballots_by_social_menu() {
		add_menu_page('Ballots','Ballots','check_ballots','social-ballots','','','');
		add_submenu_page('social-ballots','Ballots','Ballots','view_ballots','social-ballots','ballots_by_social_ballots_page');
		add_submenu_page('social-ballots','New Ballot','New Ballot','add_ballots','social-ballots-new','ballots_by_social_new_ballot_page');
		add_submenu_page('social-ballots','Settings','Settings','ballot_options','social-ballots-options','ballots_by_social_options_page');
	}
	//run when admin menu is being created
	add_action('admin_menu','ballots_by_social_menu');

//AJAX VOTE SUBMISSION
	//process submitted votes
	function ballot_ajax_submit() {
		$vote_id = $_POST['vote'];
		$ballot_id = $_POST['bid'];
		$voter_id = $_POST['vid'];
		$this_ballot = new Ballot();
		$this_ballot->submit_ballot($vote_id,$ballot_id,$voter_id);
		exit();
	}
	//run this function when submitting form through ajax
	add_action('wp_ajax_ballot_ajax_submit','ballot_ajax_submit');
	//process ballot deactivation
	function ballot_deactivate() {
		$id = $_POST['ballot'];
		$this_ballot = new Ballot();
		$this_ballot->switch_active_state($id);
		exit();
	}
	//run this function when clicking the deactivate button
	add_action('wp_ajax_ballot_deactivate','ballot_deactivate');
	//process ballot_activation
	function ballot_activate() {
		$id = $_POST['ballot'];
		$this_ballot = new Ballot();
		$this_ballot->switch_active_state($id);
		exit();
	}
	//run this function when clicking the activate button
	add_action('wp_ajax_ballot_activate','ballot_activate');
	//process ballot deletion
	function ballot_delete() {
		$id = $_POST['ballot'];
		$this_ballot = new Ballot();
		$this_ballot->delete_ballot($id);
		exit();
	}
	//run this function when clicking the activate button
	add_action('wp_ajax_ballot_delete','ballot_delete');	
?>