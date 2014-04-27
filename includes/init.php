<?php 

function lu_add_customiser() {

	if ( !current_user_can( 'edit_posts' ) ) {
		return;
	}

	// Only show on posts
	if (!is_singular()) {
		return;
	}

	$meta_boxes = apply_filters('lu_meta_box_array', $meta_boxes); 

	// No meta boxes have been defined yet so don't show customiser
	if (empty($meta_boxes)) {
		return;
	} ?>

	<a class="customiser-open-button"><div class="dashicons dashicons-arrow-right"></div></a>

	<div class="customiser">

		<div class="customiser-inner">

			<div class="post-type-title">
				<h2>Edit <?php echo lu_post_type_title(); ?></h2>
			</div>

			<div class="meta-boxes">

				<?php 

				$meta_boxes = lu_get_post_type_meta_boxes();

				if (empty($meta_boxes)) { ?>

					<p>There are no meta boxes for this post type. </p>

				<?php } else { ?>

					<form>

						<?
						
						foreach ($meta_boxes as $meta_box) {
						
							foreach ($meta_box['fields'] as $field) { 

								$field = lu_validate_field($field);

								?>

								<div class="meta-box">

									<h3><?php echo $field['title']; ?> <?php lu_status_icons($field); ?></h3>

									<?php 
									// TODO: image, file
									switch ($field['type']) {
							    
							    		case "title": ?>
							        		<input class="lu-field" type="text" id="lu_post_title" name="lu_post_title" value="<?php the_title(); ?>">
							        		<?php
							        		break;

							    		case "text": ?>
							        		<input class="lu-field" type="text" name="lu_<?php echo $field['id']; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), $field['id'], true)); ?>">
							        		<?php 
							        		break;

							    		case "number": ?>
							        		<input class="lu-field" type="number" name="lu_<?php echo $field['id']; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), $field['id'], true)); ?>">
							        		<?php 
							        		break;						        		

										case "email": ?>
							        		<input class="lu-field" type="email" name="lu_<?php echo $field['id']; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), $field['id'], true)); ?>">
							        		<?php 
							        		break;

										case "date": ?>
							        		<input class="lu-field datepicker" type="text" name="lu_<?php echo $field['id']; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), $field['id'], true)); ?>">
							        		<?php 
							        		break;

							    		case "textarea": ?>
							        		<textarea class="lu-field" name="lu_<?php echo $field['id']; ?>" rows="<?php echo $field['rows']; ?>"><?php echo esc_attr(get_post_meta(get_the_ID(), $field['id'], true)); ?></textarea>
							        		<?php break;

										case "featured": 
											$featured = absint(get_post_meta(get_the_ID(), $field['id'], true));
											if ($featured) { ?>
												<div class="dashicons dashicons-star-filled" id="lu_featured"></div>
											<?php } else { ?>
												<div class="dashicons dashicons-star-empty" id="lu_featured"></div>
											<?php 
											} 
											break;	

							    		case "checkbox": ?>
							        		<input type="checkbox" name="lu_<?php echo $field['id']; ?>[]" <?php checked( absint(get_post_meta(get_the_ID(), $field['id'], true), 1, true )); ?>>
							        		<?php break;

										case "taxonomy": 
											global $post;

											$post_terms = wp_get_post_terms( $post->ID, $field['taxonomy'], array("fields" => "ids"));

											$terms = get_terms($field['taxonomy'], 'hide_empty=0'); 

											foreach ($terms as $term) { ?>

												<div class="checkbox-field">

													<input type="checkbox" name="lu_<?php echo $field['id']; ?>[]" value="<?php echo $term->term_id; ?>" 
														<?php if (in_array($term->term_id, $post_terms)) { echo 'checked'; } ?>
													>
													<?php echo $term->name; ?>

												</div>

											<?php }
											break;	
							    
							    		case "select": ?>
											<select class="lu-field" name="lu_<?php echo $field['id']; ?>">
											<?php 

											foreach ($field['fields'] as $value => $option) { 
												?>

												<option value="<?php echo $value; ?>" <?php selected( get_post_meta(get_the_ID(), $field['id'], true), $value ); ?>>
													<?php echo $option; ?>
												</option>

											<?php } ?>
											</select>
											<?php break;

							    		case "content": 
							    			wp_editor( get_the_content() , $field['id'], array('textarea_name' => 'lu_' . $field['id'], 'quicktags' => false, 'textarea_rows' => $field['rows'])  );
							        		break;

							        	case "wysiwyg": 
							    			wp_editor( get_post_meta(get_the_ID(), $field['id'], true) , $field['id'], array('textarea_name' => 'lu_' . $field['id'], 'quicktags' => false, 'textarea_rows' => $field['rows'])  );
							        		break;

							        	case "author": 
							        		global $post; ?>
							    			<select class="lu-field" name="lu_<?php echo $field['id']; ?>">
											<?php 

											foreach ($field['users'] as $value => $option) { 
												?>

												<option value="<?php echo $value; ?>" <?php selected( $post->post_author, $value ); ?>>
													<?php echo $option; ?>
												</option>

											<?php } ?>
											</select>
							        		<?php break;

							    		case "featured-image": ?>


							    			<?php if (has_post_thumbnail()) { 

							    				$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'medium' );
							    				?>
							    				
							    				<div style="position:relative">
							    					<button class="upload_image_button existing-image" /><div class="dashicons dashicons-format-gallery"></div></button>
							    					<input type="hidden" name="featured-image-id" value="">
							    					<img src="<?php echo $featured_image[0]; ?>" class="featured-image">
							    				</div>

							    			<?php } else { ?>

							    				<button class="upload_image_button" />Upload Image</button>

							    				<div>
							    					<input type="hidden" name="featured-image-id" value="">
							    					<img src="" class="featured-image">
							    				</div>

							        		<?php 
							        		}
							        		break;
										}
									?>
								</div>

						<?php }

						}

					} ?>

				</form>
			
				<a class="customiser-close-button"><div class="dashicons dashicons-arrow-left"></div></div></a>

			</div>

		</div>

	</div>

<?php }
add_action('wp_footer', 'lu_add_customiser');