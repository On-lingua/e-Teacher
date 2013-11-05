<?php
################################################################################
// CUSTOM POST TYPES
// Register post types and taxonomies
// @Staff
// @Courses
// @Misc. Custom Post Type Magic
################################################################################


// Register Staff Posts
if ( ! function_exists( 'fs_add_staff' ) ) {
	function fs_add_staff() {
	
		global $fs_options;
	
		// "Staff" Custom Post Type
		$labels = array(
			'name' => __( 'Staff', 'post type general name' ),
			'singular_name' => _x( 'Staff', 'post type singular name' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Staff Member' ),
			'edit_item' => __( 'Edit Staff Member' ),
			'new_item' => __( 'New Staff Member' ),
			'view_item' => __( 'View Staff Member' ),
			'search_items' => __( 'Search Staff Members' ),
			'not_found' =>  __( 'No staff members found' ),
			'not_found_in_trash' => __( 'No staff members found in Trash' ), 
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => 'staff', 'with_front' => false ),
			'capability_type' => 'post',
			'hierarchical' => true,
			'menu_position' => null, 
			'has_archive' => true, 
			'taxonomies' => array( 'church-staff' ), 
			'supports' => array( 'title','editor','thumbnail', 'page-attributes' )
		);
		
		register_post_type( 'staff', $args );
		
		// "Staff Title" Custom Taxonomy. Designates a staff member's title
		$labels = array(
			'name' => __( 'Staff Titles', 'taxonomy general name' ),
			'singular_name' => __( 'Staff Title', 'taxonomy singular name' ),
			'search_items' =>  __( 'Staff Title' ),
			'all_items' => __( 'All Staff Titles' ),
			'parent_item' => __( 'Parent Staff' ),
			'parent_item_colon' => __( 'Parent Staff Title:' ),
			'edit_item' => __( 'Edit Staff Title' ), 
			'update_item' => __( 'Update Staff Title' ),
			'add_new_item' => __( 'Add New Staff Title' ),
			'new_item_name' => __( 'New Staff Title' ),
			'menu_name' => __( 'Staff Titles' )
		); 	
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'staff-title' ) 
		);
		
		register_taxonomy( 'staff-title', array( 'staff' ), $args ); 
		
		// Add Meta Box for Contact Information
		add_action( 'add_meta_boxes', 'add_staff_metaboxes' );
		// Add the Contact Meta Boxes
		function add_staff_metaboxes() {
    	add_meta_box('staff_contact', 'Contact Information', 'staff_contact', 'staff', 'normal', 'high');
		}
		
		// The Contact Information Metabox
		function staff_contact() {
   		global $post;
    
    	// Noncename needed to verify where the data originated
    	echo '<input type="hidden" name="staffmeta_noncename" id="staffmeta_noncename" value="' .
    	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    	
    	// Get the contact data if its already been entered
    	$email = get_post_meta($post->ID, '_email', true);
        $phone = get_post_meta($post->ID, '_phone', true);
        $facebook = get_post_meta($post->ID, '_facebook', true);
        $twitter = get_post_meta($post->ID, '_twitter', true);
    
    	// Echo out the field
        echo '<p>Enter email address:</p>';
    	echo '<input type="text" name="_email" value="' . $email  . '" class="widefat" />';
        echo '<p>Phone number:</p>';
        echo '<input type="text" name="_phone" value="' . $phone  . '" class="widefat" />';
        echo '<p>Facebook URL: (e.g. facebook.com/WeAreE-Teacher)</p>';
        echo '<input type="text" name="_facebook" value="' . $facebook  . '" class="widefat" />';
        echo '<p>Twitter Username: (e.g. WeAreE-Teacher)</p>';
        echo '<input type="text" name="_twitter" value="' . $twitter  . '" class="widefat" />';
		}	
		
		// Save the Metabox Data
		function wpt_save_staff_meta($post_id, $post) {
    	// verify this came from the our screen and with proper authorization,
    	// because save_post can be triggered at other times
    	if ( !wp_verify_nonce( $_POST['staffmeta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    	}
    
    	// Is the user allowed to edit the post or page?
    	if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    	// OK, we're authenticated: we need to find and save the data
    	// We'll put it into an array to make it easier to loop though.
    	$staff_meta['_email'] = $_POST['_email'];
    	$staff_meta['_phone'] = $_POST['_phone'];
    	$staff_meta['_facebook'] = $_POST['_facebook'];
    	$staff_meta['_twitter'] = $_POST['_twitter'];
    	// Add values of $events_meta as custom fields
    	foreach ($staff_meta as $key => $value) { // Cycle through the $staff_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    	}
		}
		add_action('save_post', 'wpt_save_staff_meta', 1, 2); // save the custom fields
		$staff_meta['_email'] = $_POST['_email'];
		$staff_meta['_phone'] = $_POST['_phone'];
		$staff_meta['_facebook'] = $_POST['_facebook'];
    	$staff_meta['_twitter'] = $_POST['_twitter'];
	}
	
	add_action( 'init', 'fs_add_staff' );
}


// Register Sermon Posts
if ( ! function_exists( 'fs_add_sermons' ) ) {
	function fs_add_sermons() {
	
		global $fs_options;
	
		// "Courses" Custom Post Type
		$labels = array(
			'name' => __( 'Courses', 'post type general name' ),
			'singular_name' => _x( 'Course', 'post type singular name' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Course' ),
			'edit_item' => __( 'Edit Course' ),
			'new_item' => __( 'New Course' ),
			'view_item' => __( 'View Course' ),
			'search_items' => __( 'Search Courses' ),
			'not_found' =>  __( 'No courses found' ),
			'not_found_in_trash' => __( 'No courses found in Trash' ), 
			'parent_item_colon' => ''
		);
		
		$courseitems_rewrite = get_option( 'fs_sermonitems_rewrite' ); // LATER
 		if( empty( $courseitems_rewrite ) ) { $courseitems_rewrite = 'courses'; } 
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => $courseitems_rewrite ),
			'capability_type' => 'post',
			'hierarchical' => true,
			'menu_position' => null, 
			'has_archive' => true, 
			'taxonomies' => array( 'teachers' ), 
			'supports' => array( 'title','editor','thumbnail' )
		);
		
		register_post_type( 'courses', $args ); 
		
		// "Topics" Custom Taxonomy. Allows courses to be grouped together if they are in a topic.
		$labels = array(
			'name' => __( 'Topics', 'taxonomy general name' ),
			'singular_name' => __( 'Topic', 'taxonomy singular name' ),
			'search_items' =>  __( 'Topic' ),
			'all_items' => __( 'All Topics' ),
			'parent_item' => __( 'Parent Topic' ),
			'parent_item_colon' => __( 'Parent Topic:' ),
			'edit_item' => __( 'Edit Topic' ), 
			'update_item' => __( 'Update Topic' ),
			'add_new_item' => __( 'Add New Topic' ),
			'new_item_name' => __( 'New Topic' ),
			'menu_name' => __( 'Topics' )
		); 	
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true, 
			'rewrite' => array( 'slug' => 'topics' )
		);
		
		register_taxonomy( 'topics', array( 'courses' ), $args ); 
		
		// "Teacher" Custom Taxonomy. Designates a speaker for a particular course post.
		$labels = array(
			'name' => __( 'Teachers', 'taxonomy general name' ),
			'singular_name' => __( 'Teacher', 'taxonomy singular name' ),
			'search_items' =>  __( 'Teacher' ),
			'all_items' => __( 'All Teachers' ),
			'parent_item' => __( 'Parent Teacher' ),
			'parent_item_colon' => __( 'Parent Teacher:' ),
			'edit_item' => __( 'Edit Teacher' ), 
			'update_item' => __( 'Update Teacher' ),
			'add_new_item' => __( 'Add New Teacher' ),
			'new_item_name' => __( 'New Teacher' ),
			'menu_name' => __( 'Teachers' )
		); 	
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => '-teachers' ) 
		);
		
		register_taxonomy( 'teachers', array( 'courses' ), $args ); 
		
		// "Levels" Custom Taxonomy. Designates central level for a particular course post.
		$labels = array(
			'name' => __( 'Levels', 'taxonomy general name' ),
			'singular_name' => __( 'Level', 'taxonomy singular name' ),
			'search_items' =>  __( 'Level' ),
			'all_items' => __( 'All Levels' ),
			'parent_item' => __( 'Parent Level' ),
			'parent_item_colon' => __( 'Parent Level:' ),
			'edit_item' => __( 'Edit Level' ), 
			'update_item' => __( 'Update Level' ),
			'add_new_item' => __( 'Add New Level' ),
			'new_item_name' => __( 'New Level' ),
			'menu_name' => __( 'Levels' )
		); 	
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'levels' )
		);
		
		register_taxonomy( 'levels', array( 'courses' ), $args ); 
		
		// "Bible Verses" Custom Taxonomy. Designates Bible verse for a particular sermon post.
		$labels = array(
			'name' => __( 'Departments', 'taxonomy general name' ),
			'singular_name' => __( 'Department', 'taxonomy singular name' ),
			'search_items' =>  __( 'Department' ),
			'all_items' => __( 'All Departments' ),
			'parent_item' => __( 'Parent Department' ),
			'parent_item_colon' => __( 'Parent Department:' ),
			'edit_item' => __( 'Edit Department' ), 
			'update_item' => __( 'Update Department' ),
			'add_new_item' => __( 'Add New Department' ),
			'new_item_name' => __( 'New Departments' ),
			'menu_name' => __( 'Departments' )
		); 	
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'departaments' ) 
		);
		
		register_taxonomy( 'departaments', array( 'courses' ), $args ); 
		
		// Add Meta Box for Course Media
		add_action( 'add_meta_boxes', 'add_course_metaboxes' );
		// Add the Contact Meta Boxes
		function add_course_metaboxes() {
    	add_meta_box('course_media', 'Course Media Files', 'course_media', 'courses', 'normal', 'low');
		}
		
		// The Course Media Metabox
		function course_media() {
   		global $post;
    
    	// Noncename needed to verify where the data originated
    	echo '<input type="hidden" name="sermonmeta_noncename" id="sermonmeta_noncename" value="' . // LATER
    	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    	
    	// Get the contact data if its already been entered
    	$video = get_post_meta($post->ID, '_video', true);
    	$youtube = get_post_meta($post->ID, '_youtube', true);
        $audio = get_post_meta($post->ID, '_audio', true);
        $notes = get_post_meta($post->ID, '_notes', true);
    
    	// Echo out the field
        echo '<p>Enter video file URL from Media Library (must be .mp4 format):</p>';
    	echo '<input type="text" name="_video" value="' . $video  . '" class="widefat" />';
    	echo '<p>Or enter YouTube ID instead:</p>';
    	echo '<input type="text" name="_youtube" value="' . $youtube  . '" class="widefat" />';
        echo '<p>Enter Audio file URL From Media Library:</p>';
        echo '<input type="text" name="_audio" value="' . $audio  . '" class="widefat" />';
        echo '<p>Enter Notes file URL From Media Library:</p>';
        echo '<input type="text" name="_notes" value="' . $notes  . '" class="widefat" />';
		}	
		
		// Save the Metabox Data
		function wpt_save_course_meta($post_id, $post) {
    	// verify this came from the our screen and with proper authorization,
    	// because save_post can be triggered at other times
    	if ( !wp_verify_nonce( $_POST['sermonmeta_noncename'], plugin_basename(__FILE__) )) { // LATER
    	return $post->ID;
    	}
    
    	// Is the user allowed to edit the post or page?
    	if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    	// OK, we're authenticated: we need to find and save the data
    	// We'll put it into an array to make it easier to loop though.
    	$sermon_meta['_video'] = $_POST['_video'];
    	$sermon_meta['_youtube'] = $_POST['_youtube'];
    	$sermon_meta['_audio'] = $_POST['_audio'];
    	$sermon_meta['_notes'] = $_POST['_notes'];
    	// Add values of $events_meta as custom fields
    	foreach ($sermon_meta as $key => $value) { // Cycle through the $sermon_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    	}
		}
		add_action('save_post', 'wpt_save_course_meta', 1, 2); // save the custom fields
		$sermon_meta['_video'] = $_POST['_video'];
		$sermon_meta['_youtube'] = $_POST['_youtube'];
		$sermon_meta['_audio'] = $_POST['_audio'];
		$sermon_meta['_notes'] = $_POST['_notes'];
	}
	
	add_action( 'init', 'fs_add_sermons' );
}

// Misc. Custom Post Type Magic
/* -------------------------------------------------------------------------- */
 
// Get taxonomies terms links
function custom_taxonomies_terms_links() {
	global $post, $post_id;
	// get post by post id
	$post = &get_post($post->ID);
	// get post type by post
	$post_type = $post->post_type;
	// get post type taxonomies
	$taxonomies = get_object_taxonomies($post_type);
	foreach ($taxonomies as $taxonomy) {
		// get the terms related to post
		$terms = get_the_terms( $post->ID, $taxonomy );
		if ( !empty( $terms ) ) {
			$out = array();
			foreach ( $terms as $term )
				$out[] = '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>';
			$return = join( ', ', $out );
		}
		return $return;
	}
}
?>