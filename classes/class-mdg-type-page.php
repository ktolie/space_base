<?php
/**
 * MDG Type Page Class.
 */

/**
 * Handles anything custom for the default WordPress "page" post type
 *
 * @package      WordPress
 * @subpackage   MDG_Base
 *
 * @author       Matchbox Design Group <info@matchboxdesigngroup.com>
 */
class MDG_Type_Page extends MDG_Type_Base {
	public $download_form_id = 3;

	/**
	 * Class constructor, handles instantiation functionality for the class
	 */
	function __construct() {
		if ( session_id() == '' ) {
			session_start();
		} // if()

		$this->post_type        = 'page';
		$this->post_type_title  = 'Pages';
		$this->post_type_single = 'Page';

		parent::__construct();

		$this->_add_type_actions_filters();
	} // __construct()



	/**
	 * Creates custom meta fields array.
	 *
	 * @return ArrayObject Custom meta fields
	 */
	public function get_custom_meta_fields() {
		$meta_fields = array();

		// Brochure Button Label Override
		$meta_fields[] = array(
			'label' => 'Brochure Button Label Override',
			'desc'  => 'The button label will default to "Page Name Brochure" use this field to add a custom button label.',
			'id'    => "{$this->post_type}_brochure_label",
			'type'  => 'text',
		);

		// Brochure URL
		$meta_fields[] = array(
			'label' => 'Brochure URL',
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

		$meta_fields = $this->_front_page_meta_fields( $meta_fields );
		$meta_fields = $this->_contact_page_meta_fields( $meta_fields );

		return $meta_fields;
	} // get_custom_meta_fields()



	/**
	 * Disables creating post type since page is a default post type
	 *
	 * @return Void
	 */
	public function register_post_type() {}



	/**
	 * Meta Fields for the front page
	 *
	 * @param   array  $meta_fields  The current meta fields.
	 *
	 * @return  array                The current meta fields with the front page fields.
	 */
	private function _front_page_meta_fields( $meta_fields ) {
		global $post;

		if ( isset( $post->ID ) and $post->ID != get_option( 'page_on_front' ) ) {
			return $meta_fields;
		} // if()

		// These fields will be totally different so lets nuke em.
		$meta_fields = array();

		// Featured Video Title
		$meta_fields[] = array(
			'label' => 'Featured Video Title',
			'desc'  => '',
			'id'    => "{$this->post_type}_featured_video_title",
			'type'  => 'text',
		);

		// Featured Video Embed
		$meta_fields[] = array(
			'label' => 'Featured Video Embed',
			'desc'  => '',
			'id'    => "{$this->post_type}_featured_video_embed",
			'type'  => 'textarea',
		);

		// Featured Video Link
		$meta_fields[] = array(
			'label' => 'Featured Video Link',
			'desc'  => '',
			'id'    => "{$this->post_type}_featured_video_link",
			'type'  => 'text',
		);

		return $meta_fields;
	} // _front_page_meta_fields()



	/**
	 * Meta Fields for the contact page
	 *
	 * @param   array  $meta_fields  The current meta fields.
	 *
	 * @return  array                The current meta fields with the contact page fields.
	 */
	private function _contact_page_meta_fields( $meta_fields ) {
		global $post;

		if ( get_page_template_slug() != 'template-contact.php' ) {
			return $meta_fields;
		} // if()

		// Directions Link
		$meta_fields[] = array(
			'label' => 'Directions Link',
			'desc'  => '',
			'id'    => "{$this->post_type}_directions_link",
			'type'  => 'text',
		);

		return $meta_fields;
	} // _contact_page_meta_fields()



	/**
	 * Add post type actions & filters
	 */
	private function _add_type_actions_filters() {
		// Uncomment to redirect the single page to the landing page.
		add_action( 'template_redirect', array( &$this, 'download_redirect' ) );
	} // _add_type_actions_filters()



	/**
	 * Handles redirecting the single templates to the main team page.
	 *
	 * @return  void
	 */
	public function download_redirect() {

		// Make sure we have the correct form.
		$wrong_form = ( ! isset( $_POST['gform_submit'] ) or $_POST['gform_submit'] != $this->download_form_id );
		if (  $wrong_form and ! isset( $_GET['download'] ) ) {
			return;
		} // if()

		// Make sure we have a brochure
		$brochure = ( isset( $_POST['input_3'] ) and $_POST['input_3'] != '' ) ? urlencode( $_POST['input_3'] ) : '';
		$brochure = ( isset( $_GET['brochure'] ) and $_GET['brochure'] != '' ) ? $_GET['brochure'] : $brochure;
		if ( $brochure == '' ) {
			return;
		} // if()

		// We need to redirect to close the window
		if ( ! isset( $_GET['download'] ) ) {
			$redirect_params = array(
				'download' => 1,
				'brochure' => $brochure,
			);
			$redirect_url = $this->append_get_params( $_SERVER['HTTP_REFERER'], $redirect_params );
			wp_redirect( $redirect_url );
			exit();
		} // if()

		// Set session
		$_SESSION['brochure_request_submited'] = 1;

		// Download the brochure
		$brochure  = urldecode( $brochure );
		$headers   = get_headers( $brochure, 1 );
		$type      = $headers['Content-Type'];
		$pathinfo  = pathinfo( $brochure );
		$file_name = ( isset( $pathinfo['basename'] ) ) ? $pathinfo['basename'] : '';

		if ( ! is_null( $type ) and $file_name != '' ) {
			header( "Content-disposition: attachment; filename={$file_name}" );
			header( "Content-type: {$type}" );
			readfile( $brochure );
		} // if()
	} // download_redirect()
} // END Class MDG_Type_Page()

global $mdg_page;
$mdg_page = new MDG_Type_Page();
