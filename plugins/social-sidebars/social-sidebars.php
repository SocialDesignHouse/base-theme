<?php
	/*
	Plugin Name: Add Sidebars to Page by Social
	Plugin URI: http://apps.socialdesignhouse.com/plugins/sidebars/
	Description: Add custom post type for sidebars and add custom meta box to page editor for attaching sidebars to pages.
	Version: 1.0b
	Author: Eric Allen of Social Design House
	Author URI: http://socialdesignhouse.com/
	License: GPL2
	*/
	
	/*--------------------------------------------------- Change Log -----------------------------------------------------
		
	 +	2012-04-25		v1.0b		Converted previous functions.php code into plug-in
	
	--------------------------------------------------------------------------------------------------------------------*/
	
	//create the sidebar post type
	function social_create_post_type() {
		register_post_type('social_sidebar',
			array(
				'labels' => array(
					'name' => 'Sidebars',
					'singular_name' => 'Sidebar',
					'add_new' => 'Add Sidebar',
					'edit_item' => 'Edit Sidebar',
					'new_item' => 'New Sidebar',
					'add_new_item' => 'Add New Sidebar',
					'view_item' => 'View Sidebar',
					'search_items' => 'Search Sidebars',
					'not_found' => 'No Sidebars Found'
				),
				'public' => true,
				'has_archive' => false,
				'hierarchical' => false,
				'menu_position' => 6,
				'capability_type' => 'page',
				'supports' => array('title','editor','thumbnail','page-attributes')
			)
		);
	}
	//run this function when initializing wordpress
	add_action('init', 'social_create_post_type');
	
	//create sidebar meta box
	function social_sidebars_add_meta_box() {
		add_meta_box("page_sidebars-meta","Sidebars","social_page_sidebars","page","side","high");
		add_meta_box("page_sidebars-meta","Sidebars","social_page_sidebars","social_conference","side","high");
		add_meta_box("page_sidebars-meta","Sidebars","social_page_sidebars","social_exhibition","side","high");
	}
	//run this section when setting up the page editor
	add_action("admin_init","social_sidebars_add_meta_box");
	
	//populate sidebar meta box
	function social_page_sidebars() {
		global $post;
		$custom = get_post_custom($post->ID);
		$attached_sidebars = $custom['attached_sidebars'][0];
		//create array from attached sidebars
		$attached_sidebar_array = explode(',',$attached_sidebars);
		//initialize new array for storing sidebar data
		$sidebar_array = array();
		//retrieve sidebars in alphabetical order
		$args = array(
			'post_type' => 'social_sidebar',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'numberposts' => -1
		);
		$sidebars = get_posts($args);
		//iterate through sidebars and add them to the array 
		foreach($sidebars as $sidebar) {
			$id = $sidebar->ID;
			$title = $sidebar->post_title;
			$sidebar_array[$id]['id'] = $id;
			$sidebar_array[$id]['title'] = $title;
		}
		//if testimonials were returned
		if(count($sidebar_array) > 0) { ?>

			<div class="inside">
				<div class="categorydiv" id="taxonomy-sidebar_category">
					<ul class="category-tabs" id="sidebar_category-tabs">
						<li class="tabs">Sidebars</li>
					</ul>
					<div class="tabs-panel" id="sidebar_category-all">
						<input type="hidden" value="0" name="sidebars[]">
						<ul class="list:sidebar_category categorychecklist form-no-clear" id="sidebar_categorychecklist">

							<?php //iterate through sorted pages and display output
							foreach($sidebar_array as $sidebar) { 
								if($sidebar['id'] != '') { 
									if(in_array($sidebar['id'],$attached_sidebar_array)) {
										$checked = ' checked = "checked" ';
									} else {
										$checked = '';
									} ?>

									<li id="sidebar_category-<?=$sidebar['id']?>">
										<label class="selectit"><input type="checkbox" id="in-sidebar_category-<?=$sidebar['id']?>" name="sidebars[]" value="<?=$sidebar['id']?>" <?=$checked?>/> <?=$sidebar['title']?></label>
									</li>

							<?php }
							} ?>

						</ul>
					</div>
				</div>
			</div>

		<?php } else { ?>
			
			<div class="inside">
				<p>You haven't created any Sidebars yet.</p>
			</div>
			
		<?php }
	}

	//save the attached sidebars
	function social_save_sidebars() {
		global $post;
		$post_type = $post->post_type;
		if($post_type == 'page' || $post_type == 'social_conference') {
			//iterate through sidebars and add them to a comma-seperated list
			foreach($_POST['sidebars'] as $sidebar) {
				$sidebar_string .= $sidebar . ',';
			}
			//remove trailing comma
			$sidebar_string = substr($sidebar_string,0,-1);
			//save sidebar string to custom post data
			update_post_meta($post->ID,'attached_sidebars',$sidebar_string);
		}
	}
	//run this function when saving a post
	add_action('save_post','social_save_sidebars');
	
?>