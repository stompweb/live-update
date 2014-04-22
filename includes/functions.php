<?php 

// Only get the meta boxes for that specific post type for displaying
function lu_get_post_type_meta_boxes() {

	$all_meta_boxes = apply_filters('lu_meta_box_array', $meta_boxes); 
	$meta_boxes = array();
	$post_type = get_post_type(get_the_ID());

	foreach ($all_meta_boxes as $meta_box) {

		if (in_array($post_type, $meta_box['post_types'])) {

			$meta_boxes[] = $meta_box;
		}

	}

	return $meta_boxes;

}

function lu_get_users_for_authors() {

	global $wpdb;

	$user_args = new WP_User_Query( array(
    	'meta_query' => array(
        	'relation' => 'OR',
        	array(
            	'key' => $wpdb->prefix . 'capabilities',
            	'value' => 'administrator',
            	'compare' => 'like'
        	),
        	array(
            	'key' => $wpdb->prefix . 'capabilities',
            	'value' => 'editor',
            	'compare' => 'like'
        	),
        	array(
            	'key' => $wpdb->prefix . 'capabilities',
            	'value' => 'author',
            	'compare' => 'like'
        	),

    	)
	));

	$users = get_users( $user_args );

	$authors = array();

	foreach ($users as $user) {

		$authors[$user->ID] = $user->display_name;

	}

	return $authors;

}

function lu_validate_field($field) {

	// Some fields do not need IDs so automatically add them
	if ('title' == $field['type']) {
		$field['id'] = 'post_title';
	} 

	if ('featured-image' == $field['type']) {
		$field['id'] = 'post_thumbnail';
	} 

	if ('featured' == $field['type']) {
		$field['id'] = '_featured';
	} 	

	if ('content' == $field['type']) {
		$field['id'] = 'the_content';
	} 	

	// If title is blank, try and set it to something reasonable
	if (empty($field['title'])) {
		$field['title'] = lu_unslug($field['id']);
	}

	if (empty($field['featured'])) {
		$field['title'] = lu_unslug($field['id']);
	}

	return $field;
}

// Turn a post name into a post title
function lu_unslug($string) {

	$string = str_replace('-', ' ', $string);
	$string = str_replace('_', ' ', $string);
	$string = ucwords($string);

    return $string;
}

// Get the title of a post type
function lu_post_type_title() {

	global $post;

	$post_type = get_post_type($post->ID);
	$post_type_obj = get_post_type_object( $post_type );

	return $post_type_obj->labels->singular_name;

}

// Output icons on front end.
function lu_status_icons($field) { ?>

	<span class="dashicons dashicons-update lu-update" data-field="<?php echo $field['id']; ?>" data-type="<?php echo $field['type']; ?>" data-selector="<?php echo $field['selector']; ?>"></span>
	<span class="dashicons dashicons-yes" id="success-<?php echo $field['id']; ?>"></span>
	<span class="loading" id="loading-<?php echo $field['id']; ?>"><img src="<?php echo LU_PLUGIN_URL . '/assets/images/ajax-loader.gif'; ?>"></span>

<?php } ?>