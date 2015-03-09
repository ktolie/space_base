<?php
/**
 * MDG Type Post Class.
 */
/**
 * Handles anything custom for the default WordPress "post" post type.
 *
 * @package      WordPress
 * @subpackage   MDG_Base
 *
 * @author       Matchbox Design Group <info@matchboxdesigngroup.com>
 */
class MDG_Type_Post extends MDG_Type_Base
{
	/**
	 * Class constructor, handles instantiation functionality for the class
	 */
	function __construct() {
		$this->post_type        = 'post';
		$this->post_type_title  = 'Posts';
		$this->post_type_single = 'Post';

		$this->_add_type_actions_filters();

		parent::__construct();
	} // __construct()



	/**
	 * Creates custom meta fields array.
	 *
	 * @return ArrayObject Custom meta fields
	 */
	public function get_custom_meta_fields() {
		return array(
			// Disable Quick Contact Form
			array(
				'label' => 'Disable Quick Contact Form',
				'desc'  => '',
				'id'    => "{$this->post_type}_disable_quick_contact",
				'type'  => 'checkbox',
			),
		);
	} // get_custom_meta_fields()



	/**
	 * Disables creating post type since post is a default post type
	 *
	 * @return Void
	 */
	public function register_post_type() {}



	/**
	 * Add post type actions & filters
	 */
	private function _add_type_actions_filters() {
		add_action( 'wp_ajax_mdg_ajax_load_posts', array( &$this, 'ajax_load_posts_callback' ) );
		add_action( 'wp_ajax_nopriv_mdg_ajax_load_posts', array( &$this, 'ajax_load_posts_callback' ) );
	} // _add_type_actions_filters()


	/**
	 * Template for a posts on the blog landing page.
	 *
	 * @param   array    $posts    The posts to display.
	 * @param   boolean  $echo     If the posts should be output to the screen.
	 * @param   boolean  $is_ajax  If it is being requested via AJAX.
	 *
	 * @return  void
	 */
	public function posts_template( $posts, $echo = true, $is_ajax = false ) {
		if ( empty( $posts ) or gettype( $posts ) != 'array' ) {
			return '';
		} // if()

		global $post;
		$html = '';

		$html .= ( $is_ajax ) ? '<div class="row load-posts-ajax loading">' : '<div class="row">';
		foreach ( $posts as $post ) {
			setup_postdata( $post );
			ob_start();
			get_template_part( 'templates/partial-news-item' );
			$html .= ob_get_clean();
		} // foreach()
		wp_reset_postdata();
		$html .= '</div> <!-- /.row -->';

		if ( $echo ) {
			echo $html;
		} // if()

		return $html;
	} // posts_template()


	/**
	 * Handles loading more posts via AJAX
	 *
	 * @return  void
	 */
	public function ajax_load_posts_callback() {
		$offset         = ( isset( $_GET['offset'] ) ) ? $_GET['offset'] : false;
		$posts_per_page = ( isset( $_GET['postsperpage'] ) ) ? $_GET['postsperpage'] : false;
		$post_type      = ( isset( $_GET['posttype'] ) ) ? $_GET['posttype'] : false;
		$category       = ( isset( $_GET['category'] ) ) ? $_GET['category'] : false;
		$response       = array(
			'success' => false,
			'html'    => '',
			'count'   => 0,
		);

		// Missing something? FAIL!
		if ( ! $offset or ! $posts_per_page or ! $post_type ) {
			echo json_encode( $response );
			die();
		} // if()

		// Get the posts
		$post_query_args = array(
			'posts_per_page' => $posts_per_page,
			'post_type'      => $post_type,
			'offset'         => $offset,
			'cat'            => $category,
		);
		$posts = $this->get_posts( $post_query_args );

		// No posts? FAIL!
		if ( empty( $posts ) ) {
			echo json_encode( $response );
			die();
		} // if()

		$response['success'] = true;
		$response['html']    = $this->posts_template( $posts, false, true );
		$response['count']   = count( $posts );

		echo json_encode( $response );
		die();
	} // ajax_load_posts_callback()
} // END Class MDG_Type_Post()

global $mdg_post;
$mdg_post = new MDG_Type_Post();
