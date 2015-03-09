<?php
/**
 * MDG Type Slide Class.
 */

/**
 * You basically need to change [slide/Slide] to be your post
 * type name and then add your custom meta if needed, if no
 * custom meta is needed then delete the get_custom_meta_fields.
 * Please do take a look at MDG_Type_Base to see what parameters
 * and methods are already available to use.
 *
 * The properties of MDG_Type_Base that you should/can alter are
 * all in __construct(). Anything thay isn't REQUIRED that you
 * do not use please remove before deploying to production. Also
 * any property that is optional has the defaults as an example.
 */


/**
 * This class can be used as a starting point to add new post types.
 *
 * @package      WordPress
 * @subpackage   MDG_Base
 *
 * @author       Matchbox Design Group <info@matchboxdesigngroup.com>
 */
class MDG_Type_Slide extends MDG_Type_Base
{
	/**
	 * Class constructor, handles instantiation functionality for the class
	 */
	function __construct() {
		/** @var string  REQUIRED slug for post type */
		$this->post_type = 'slide';

		/** @var string  REQUIRED title of post type */
		$this->post_type_title = 'Home Page Slides';

		/** @var string  REQUIRED singular title */
		$this->post_type_single = 'Home Page Slide';

		// MDG_Type_Base Properties.
		$this->_set_mdg_type_base_options();

		// MDG_Meta_Helper Properties
		$this->_set_mdg_meta_helper_options();

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
		$this->disable_post_type_categories = true;

		/** @var array   Custom post type supports array used in register_post_type() */
		$this->custom_post_type_supports = array(
			'title',
			'editor',
			'thumbnail',
			'revisions',
		);

		/** @var array   Custom post type arguments used in register_post_type() */
		$this->custom_post_type_args = array(
			'hierarchical' => false,
			'menu_icon'    => 'dashicons-images-alt2',
		);
	} // _set_mdg_type_base_options()



	/**
	 * Handles setting of the optional properties of MDG_Meta_Helper
	 *
	 * return Void
	 */
	private function _set_mdg_meta_helper_options() {
		/** @var string Renames the featured image meta box */
		$this->featured_image_title = "{$this->post_type_single} Image (444x376)"; // set to '' or false to keep default title.
	} // _set_mdg_meta_helper_options()


	/**
	 * Creates custom meta fields array.
	 *
	 * @return ArrayObject Custom meta fields
	 */
	public function get_custom_meta_fields() {
		return array(
			// Text
			array(
				'label' => 'Slide Sub-title',
				'desc'  => '',
				'id'    => "{$this->post_type}_sub_title",
				'type'  => 'text',
			),
			// Text
			array(
				'label' => 'Slide Link',
				'desc'  => '',
				'id'    => "{$this->post_type}_link",
				'type'  => 'text',
			),
		);
	} // get_custom_meta_fields()



	/**
	 * Add post type actions & filters
	 */
	private function _add_type_actions_filters() {
		// Uncomment to redirect the single page to the landing page.
		add_action( 'template_redirect', array( &$this, 'single_redirect' ) );
	} // _add_type_actions_filters()



	/**
	 * Handles redirecting the single templates to the main team page.
	 *
	 * @return  void
	 */
	public function single_redirect() {
		if ( is_single() and get_post_type() == $this->post_type ) {
			wp_redirect( home_url() );
			exit();
		} // if()
	} // single_redirect()
} // END Class MDG_Type_Slide()

global $mdg_slide;
$mdg_slide = new MDG_Type_Slide();
