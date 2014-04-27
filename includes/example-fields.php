<?php 

function my_meta_box_array($meta_boxes) {

    $meta_boxes = array();

    $fields = array(
        array( 'type' => 'title', 'title' => 'Post Title', 'selector' => 'h1.entry-title'),
        array( 'type' => 'text', 'id' => 'my_post_meta', 'title' => 'Please update me', 'selector' => '.my-post_meta'),
        array( 'type' => 'taxonomy', 'taxonomy' => 'category', 'id' => 'categories', 'title' => 'Categories'),
    );

    $meta_boxes[] = array(
        'post_types' => array('post'),
        'fields' => $fields
    );

    return $meta_boxes;

}

add_filter('lu_meta_box_array', 'my_meta_box_array');