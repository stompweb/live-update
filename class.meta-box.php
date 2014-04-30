<?php 

class LU_Field {

	public function __construct( $id, $title, $args = array() ) {

		$this->id 		= $id;
		$this->title	= $title;

		$this->args		= wp_parse_args( $args, array(
				'selector' 				=> '',
				'rows'        			=> '',
				'taxonomy'   			=> '',
			)
		);

	}

	/* Add any custom JS scripts for this field */
	public function enqueue_scripts() {
    }

	/* Add any custom CSS scripts for this field */
	public function enqueue_styles() {
    }

	/* Output the field */
	public function render() {
    }

	/* Save the values */
    public function save() {
    }

}

class LU_Text_Field extends LU_Field {

	public function render() { ?>

		<input class="lu-field" type="text" name="lu_<?php echo $this->id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), $field['id'], true)); ?>">

	<?php }
}