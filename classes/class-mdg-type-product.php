<?php
/**
 * MDG Type Product Class.
 */


/**
 * @package      WordPress
 * @subpackage   MDG_Base
 *
 * @author       Matchbox Design Group <info@matchboxdesigngroup.com>
 */
class MDG_Type_Product extends MDG_Type_Base
{
	/**
	 * Class constructor, handles instantiation functionality for the class
	 */
	function __construct() {
		/** @var string  REQUIRED slug for post type */
		$this->post_type = 'product';

		/** @var string  REQUIRED title of post type */
		$this->post_type_title = 'Products';

		/** @var string  REQUIRED singular title */
		$this->post_type_single = 'Product';

		// MDG_Type_Base Properties.
		$this->_set_mdg_type_base_options();

		parent::__construct();

		$this->_add_type_actions_filters();
	} // __construct()



	/**
	 * Handles setting of the optional properties of MDG_Type_Base
	 *
	 * return Void
	 */
	private function _set_mdg_type_base_options() {
		/** @var boolean  Disable/Enable Categories per post type */
		$this->disable_post_type_categories = false;

		/** @var array   Custom post type supports array used in register_post_type() */
		$this->custom_post_type_supports = array(
			'title',
			'editor',
			'thumbnail',
			'revisions',
		);

		/** @var array   Custom post type arguments used in register_post_type() */
		$this->custom_post_type_args = array(
			'menu_icon' => 'dashicons-products', // http://melchoyce.github.io/dashicons/
		);
	} // _set_mdg_type_base_options()



	/**
	 * Creates custom meta fields array.
	 *
	 * @return ArrayObject Custom meta fields
	 */
	public function get_custom_meta_fields() {
		$meta_fields = array();

		// Specification Brochure Button Label Override
		$meta_fields[] = array(
			'label' => 'Specification Brochure Button Label Override',
			'desc'  => 'The button label will default to "Page Name Specifications" use this field to add a custom button label.',
			'id'    => "{$this->post_type}_brochure_label",
			'type'  => 'text',
		);
		// Specification Brochure URL
		$meta_fields[] = array(
			'label' => 'Specification Brochure URL',
			'desc'  => '',
			'id'    => "{$this->post_type}_brochure_url",
			'type'  => 'file',
		);
		// Disable Quick Contact Form
		$meta_fields[] = array(
			'label' => 'Disable Quick Contact Form',
			'desc'  => '',
			'id'    => "{$this->post_type}_disable_quick_contact",
			'type'  => 'checkbox',
		);

		// Media Gallery
		$meta_fields[] = array(
			'label'  => 'Media Gallery',
			'desc'   => '',
			'id'     => "{$this->post_type}_media_gallery",
			'type'   => 'multi_input',
			'attrs'  => array(),
			'fields' => $this->_multi_input_fields( "{$this->post_type}_multi_input" ),
		);

		$meta_fields[] = array(
			'label' => 'Advantages',
			'desc'  => '',
			'id'    => "{$this->post_type}_advantage_list",
			'style'  => 'list',
			'type'  => 'textarea',
		);

		return $meta_fields;
	} // get_custom_meta_fields()




// /**
//  * Creates custom meta fields array.
//  *
//  * @return ArrayObject Custom meta fields
//  */

// 	public function _advantages() {
// 		$meta_fields = array();

// 		// Adavantages list
// 		$meta_fields[] = array(
// 			'label' => 'Advantages',
// 			'desc'  => '',
// 			'id'    => "{$this->post_type}_advantage_list",
// 			'type'  => 'textarea',
// 		);

// 		return $meta_fields;
// 	}



	/**
	 * Sets the multi input fields for REPLACE.
	 *
	 * @param   string  $id  The field id.
	 *
	 * @return  array        The multi input fields.
	 */
	private function _multi_input_fields( $id ) {
		$fields = array();

		// Thumbnail/Slide Image
		$fields[] = array(
			'label' => 'Thumbnail/Slide Image',
			'desc'  => '',
			'id'    => "{$this->post_type}_media_center_slide_image",
			'type'  => 'file',
		);

		// Thumbnail Short Description
		$fields[] = array(
			'label' => 'Thumbnail Short Description',
			'desc'  => '',
			'id'    => "{$this->post_type}_media_center_thumbnail_short_description",
			'type'  => 'textarea',
		);

		// Video Embed
		$fields[] = array(
			'label' => 'Video Embed',
			'desc'  => '',
			'id'    => "{$this->post_type}_media_center_video_link",
			'type'  => 'textarea',
		);

		// // Is Video Embed?
		// $fields[] = array(
		// 	'label' => 'Is Video Embed',
		// 	'desc'  => '',
		// 	'id'    => "{$this->post_type}_media_center_is_video_embed",
		// 	'type'  => 'checkbox',
		// );


		return $fields;
	} // _multi_input_fields()



	/**
	 * Add post type actions & filters
	 */
	private function _add_type_actions_filters() {
	} // _add_type_actions_filters()
} // END Class MDG_Type_Product()

global $mdg_product;
$mdg_product = new MDG_Type_Product();
