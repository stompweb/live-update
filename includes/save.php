<?php 

function lu_update_value() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'lu-nonce' ) ) {
		die();
	}

	if ( !current_user_can( 'edit_posts' ) ) {
		return;
	}

	$post_id = $_POST['post_id'];
	$field_type = $_POST['field_type'];
	$new_value = $_POST['new_value'];

	if (isset($_POST['taxonomy'])) {
		$taxonomy = $_POST['taxonomy'];
	}

	do_action('lu_before_save_values', $post_id, $new_value);

	switch ($field_type) {
						    
		case "title":

			$post_data = array(
      			'ID'			=> $post_id,
      			'post_title' 	=> sanitize_text_field($new_value),
      			'post_name' 	=> sanitize_title_with_dashes($new_value)
  			);

			wp_update_post( $post_data );

			break;

		case "content":

			$post_data = array(
      			'ID'			=> $post_id,
      			'post_content' 	=> $new_value
  			);

			wp_update_post( $post_data );

			break;

		case "author":

			$post_data = array(
      			'ID'			=> $post_id,
      			'post_author' 	=> $new_value
  			);

			wp_update_post( $post_data );

			break;	

		case "featured-image":

			set_post_thumbnail( $post_id, absint($new_value) ); 

			break;

		case "taxonomy":

			$post_terms = wp_get_post_terms( $post_id, $taxonomy, array("fields" => "ids"));
			wp_remove_object_terms( $post_id, $post_terms, $taxonomy );
			if (!empty($new_value)) {
				wp_set_post_terms( $post_id, $new_value, $taxonomy );
			}

			break;	

		case "email":

			update_post_meta($post_id, $_POST['field_id'], sanitize_email($new_value));

			break;

		case "number":

			update_post_meta($post_id, $_POST['field_id'], absint($new_value));

			break;

		case "checkbox":

			update_post_meta($post_id, $_POST['field_id'], absint($new_value));

			break;		

		case "wysiwyg":

			update_post_meta($post_id, $_POST['field_id'], apply_filters('the_content', $new_value));

			break;		

		default:

			update_post_meta($post_id, $_POST['field_id'], sanitize_text_field($new_value));

	}

	do_action('lu_after_save_values', $post_id, $new_value);

	die();
}
add_action( 'wp_ajax_lu_update_value', 'lu_update_value' );