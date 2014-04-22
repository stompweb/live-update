<?php 

function meta_box_array($meta_boxes) {

	$meta_boxes = array();

	$authors = lu_get_users_for_authors();

	$fields = array(
		array( 'type' => 'title', 'title' => 'Circuit Title', 'selector' => 'h1.entry-title'),
        array( 'type' => 'featured' ),
        array( 'type' => 'author', 'id' => 'author', 'users' => $authors),
    );

    $meta_boxes[] = array(
        'post_types' => array('post'),
        'fields' => $fields
    );

    return $meta_boxes;

}

add_filter('lu_meta_box_array', 'meta_box_array');