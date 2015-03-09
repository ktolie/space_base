<?php
/**
 * MDG Type Market Class.
 */


/**
 * @package      WordPress
 * @subpackage   MDG_Base
 *
 * @author       Matchbox Design Group <info@matchboxdesigngroup.com>
 */
class MDG_Type_Market extends MDG_Type_Base
{
	/**
	 * Class constructor, handles instantiation functionality for the class
	 */
	function __construct() {
		/** @var string  REQUIRED slug for post type */
		$this->post_type = 'market';

		/** @var string  REQUIRED title of post type */
		$this->post_type_title = 'Markets';

		/** @var string  REQUIRED singular title */
		$this->post_type_single = 'Market';

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
			'revisions',
		);

		/** @var array   Custom post type arguments used in register_post_type() */
		$this->custom_post_type_args = array(
			'menu_icon' => 'dashicons-admin-site', // http://melchoyce.github.io/dashicons/
		);
	} // _set_mdg_type_base_options()



	/**
	 * Creates custom meta fields array.
	 *
	 * @return ArrayObject Custom meta fields
	 */
	public function get_custom_meta_fields() {
		return array(
			// Brochure Button Label Override
			array(
				'label' => 'Brochure Button Label Override',
				'desc'  => 'The button label will default to "Page Name Brochure" use this field to add a custom button label.',
				'id'    => "{$this->post_type}_brochure_label",
				'type'  => 'text',
			),
			// Brochure URL
			array(
				'label' => 'Brochure URL',
				'desc'  => '',
				'id'    => "{$this->post_type}_brochure_url",
				'type'  => 'file',
			),
		);
	} // get_custom_meta_fields()



	/**
	 * Add post type actions & filters
	 */
	private function _add_type_actions_filters() {
		add_action( 'template_redirect', array( &$this, 'single_redirect' ) );
	} // _add_type_actions_filters()



	/**
	 * Handles redirecting the single templates to the main team page.
	 *
	 * @return  void
	 */
	public function single_redirect() {
		if ( is_single() and get_post_type() == $this->post_type ) {
			wp_redirect( home_url( "/{$this->post_type}s/" ) );
			exit();
		} // if()
	} // single_redirect()
} // END Class MDG_Type_Market()

global $mdg_market;
$mdg_market = new MDG_Type_Market();
