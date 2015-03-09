<?php
/**
 * MDG Generic Class.
 */

/**
 * Contains methods/properties that can be used by all classes.
 *
 * All classes should at the very minimum extend MDG_Generic so they can
 * have easy access to all helper methods/properties.
 *
 * @package      WordPress
 * @subpackage   MDG_Base
 *
 * @author       Matchbox Design Group <info@matchboxdesigngroup.com>
 */
class MDG_Generic {
	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
	} // __construct()


	/**
	 * Checks if the current host is localhost.
	 *
	 * @return boolean If the current host is localhost.
	 */
	public function is_localhost() {
		$localhost = array(
			'localhost',
			'127.0.0.1',
			'10.0.2.2',
		);
		$host      = $_SERVER['HTTP_HOST'];
		$is_vhost  = strpos( $host, '.dev' );

		if ( in_array( $host, $localhost ) or $is_vhost ) {
			return true;
		} // if()

		return false;
	} // is_localhost()



	/**
	 * Checks if the current host is a staging site.
	 *
	 * @return boolean If the current host is a staging site.
	 */
	public function is_staging() {
		$staging = array( 'staging.', 'dev.' );
		$host    = $_SERVER['HTTP_HOST'];

		foreach ( $staging as $site ) {
			if ( strpos( $host, $site ) !== false ) {
				return true;
			} // if()
		} // foreach()

		return false;
	} // is_staging()



	/**
	 * Retrieves a page/post/custom post type ID when provided a slug.
	 *
	 * @param string  $slug The slug of the page/post/custom post type you want an ID for.
	 *
	 * @return integer      The ID of the page/post/custom post type
	 */
	public function get_id_by_slug( $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ){
			return $page->ID;
		}

		return null;
	} // get_id_by_slug()



	/**
	 * Adds testing post content to the supplied post type.
	 *
	 * @example $mdg_generic->make_dummy_content( 'project', 'Sample Project' 20 );
	 *
	 * @todo Fix all posts having the same exact post date.
	 * @todo Add options such as adding http://placehold.it featured images.
	 *
	 * @param string  $post_type Required, Name of the post type to create content for.
	 * @param string  $title     Required, The title base you want to use without a trailing space, the post count will be appended to the end.
	 * @param integer $count     Required, The amount of posts you want to be added.
	 * @param array   $options   Optional, options to customize post generation.
	 *
	 * @return Void
	 */
	public function make_dummy_content( $post_type, $title, $count, $options = array() ) {
		global $user_ID;

		for ( $i = 1; $i <= $count; $i++ ) {

			$text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";

			// add an extra paragraph here and there
			$text = $i % 3 ? $text . '<br/><br/>' . $text : $text;

			// By adding one to the time the publish date increments
			$current_time = time() + $i;

			$new_post = array(
				'post_title'    => "{$title} {$i}",
				'post_content'  => $text,
				'post_status'   => 'publish',
				'post_date'     => date( 'Y-m-d H:i:s', $current_time ),
				'post_author'   => $user_ID,
				'post_type'     => $post_type,
				'post_category' => array( 0 )
			);

			$post_id = wp_insert_post( $new_post );
		} // for()
	} // make_dummy_content()



	/**
	 * Truncates a string with the supplied information
	 *
	 * Example:
	 * global $mdg_generic;
	 * $mdg_generic->truncate( $string, 30, " " )
	 *
	 * @param string  $string The string to be truncated
	 * @param integer $limit  The length of the truncated string
	 * @param string  $break  The break point
	 * @param string  $pad    The string padding to use
	 *
	 * @return string          The truncated string if $string <= $limit or the input $string
	 */
	public function truncate_string( $string, $limit, $break = '.', $pad = '...' ) {
		// return with no change if string is shorter than $limit
		if ( strlen( $string ) <= $limit ){
			return $string;
		}

		// our first test
		$test1 = strpos( $string, $break, $limit );

		// second test to make sure we didn't land on a break (won't truncate)
		$test2 = strpos( $string, $break, $limit -1 );

		// is $break present between $limit and the end of the string?
		if ( false !== ( $breakpoint = $test1 ) || false !== ( $breakpoint = $test2 ) ) {
			if ( $breakpoint < strlen( $string ) - 1 ) {
				$string = substr( $string, 0, $breakpoint ) . $pad;
			} // if()
		} // if()

		return $string;
	} // truncate_string()



	/**
	 * Creates and optionally outputs pagination
	 *
	 * @param string  $max_num_pages Optional. The amount of pages to be paginated through, defaults to the global $wp_query->max_num_pages.
	 * @param integer $range         Optional. The minimum amount of items to show
	 * @param boolean $output        Optional. Output the content
	 *
	 * @return string                    The pagination HTML
	 */
	public function pagination( $max_num_pages = null, $range = 2, $output = true ) {
		$showitems  = ( $range * 2 ) + 1;
		$pagination = '';

		global $paged;
		if ( empty( $paged ) ){
			$paged = 1;
		}

		if ( is_null( $max_num_pages ) ) {
			global $wp_query;
			$max_num_pages = $wp_query->max_num_pages;
			if ( ! $max_num_pages )
				$max_num_pages = 1;
		} // if()

		if ( 1 != $max_num_pages ) {
			$pagination .= "<div class='pagination'>";
			if ( $paged > 2 && $paged > $range + 1 && $showitems < $max_num_pages )
				$pagination .= "<a href='".get_pagenum_link( 1 )."'>&laquo;</a>";
			if ( $paged > 1 && $showitems < $max_num_pages )
				$pagination .= "<a href='".get_pagenum_link( $paged - 1 )."'>&lsaquo;</a>";

			for ( $i = 1; $i <= $max_num_pages; $i++ ) {
				if ( 1 != $max_num_pages &&( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $max_num_pages <= $showitems ) ) {
					$pagination .= ( $paged == $i )? "<span class='current'>".$i.'</span>':'<a href="'.get_pagenum_link( $i ).'" class="inactive" >'.$i.'</a>';
				} // if()
			} // for()

			if ( $paged < $max_num_pages && $showitems < $max_num_pages ) {
				$pagination .= "<a href='".get_pagenum_link( $paged + 1 )."'>&rsaquo;</a>";
			}

			if ( $paged < $max_num_pages - 1 &&  $paged + $range - 1 < $max_num_pages && $showitems < $max_num_pages ) {
				$pagination .= "<a href='".get_pagenum_link( $max_num_pages )."'>&raquo;</a>";
			}

			$pagination .= "</div>\n";
		} // if()

		if ( $output ) {
			$allowed_html = array(
				'div' => array(
					'class' => array(),
					'id'    => array(),
				),
				'a' => array(
					'href'  => array(),
					'class' => array(),
					'id'    => array(),
				),
				'span'    => array(
					'class' => array(),
					'id'    => array(),
				),
			);
			echo wp_kses( $pagination, $allowed_html );
		} // if()

		return $pagination;
	} // pagination()



	/**
	 * Retrieves the YouTube video ID from the supplied embed code
	 *
	 * @param string  $embed YouTube embed code
	 *
	 * @return [type]        [description]
	 */
	public function get_youtube_id( $embed ) {

		// pass me a link or an embed code and I'll return the youtube id for the video
		preg_match( '#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $embed, $matches );
		if ( isset( $matches[2] ) && $matches[2] != '' ) {
			$youtube_id = $matches[2];
		}

		return $youtube_id;
	} // get_youtube_id()



	/**
	 * Cleans the multi input meta field.
	 *
	 * @todo Audit and document this method better.
	 * @todo Move into MDG_Meta_Helper.
	 *
	 * @param  string  $multi_input  The multi input meta value.
	 *
	 * @return array                 The cleaned multi input values.
	 */
	public function clean_multi_input( $multi_input = '' ) {
		// this converts the multi_input from it's saved state (fake sorta json object thingy)
		// to a php array

		// make it a valid json object
		$multi_input = str_replace( '|', '"', $multi_input );

		// decode to get make it php friendly array
		$multi_input = json_decode( $multi_input );

		return $multi_input;
	} // clean_multi_input()



	/**
	 * Groups the multi input meta field.
	 *
	 * @todo Audit and document this method better.
	 * @todo Move into MDG_Meta_Helper.
	 *
	 * @param  string  $multi_input  The multi input meta value.
	 *
	 * @return array                 The grouped multi input values.
	 */
	public function group_multi_input( $multi_input = '' ) {

		// this method will get the multi_input, clean them via this->clean_multi_input, and return them in a grouped array

		// clean/format multi_input
		$multi_input = $this->clean_multi_input( $multi_input );

		$i = 1;
		$multi_input_fields_count = 3; // this is the number of fields for each group of rewards
		$tracker = 1;
		$grouped_array  = array();

		foreach ( $multi_input as $award ) {

			// iterate through multi_input, building an award (item) with each field
			if ( $tracker == 1 ) {
				//first in group
				$item = array();
			}

			array_push( $item, $award );

			if ( $tracker == $multi_input_fields_count ) {
				// last in group

				array_push( $grouped_array, $item );

				$tracker = 1; // reset tracker

			} else {
				$tracker++;
			}

			$i++;
		}

		return $grouped_array;
	} // group_multi_input()



	/**
	 * Get attachment ID from src url
	 *
	 * @param string  $attachment_url Absolute URI to an attachment
	 *
	 * @return integer Post ID
	 */
	public function get_attachment_id_from_src( $attachment_url ) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$attachment_url'";
		$id    = $wpdb->get_var( $query );
		return $id;
	} // get_attachment_id_from_src()



	/**
	 * Handles getting an excerpt by more tag and falls back to get_the_excerpt.
	 *
	 * @param   object   $post  Optional, WordPress post object to retrieve the content/excerpt from.
	 * @param   boolean  $echo  If the excerpt content should be echoed out.
	 *
	 * @return  string          The "excerpt" content.
	 */
	public function get_more_content_excerpt_fallback( $post = null, $echo = true ) {
		$excerpt = '';

		if ( is_null( $post ) ) {
			global $post;
		} // if()

		$has_more_tag = ( strpos( $post->post_content, '<!--more-->' ) !== false );
		if ( $has_more_tag ) {
			global $more;
			$more     = 0;
			$excerpt .= get_the_content( '' );
		} else {
			$excerpt .= get_the_excerpt();
		} // if()

		$excerpt = strip_shortcodes( $excerpt );
		$exceprt = strip_tags( $excerpt );

		if ( $echo ) {
			echo wp_kses( $exceprt, 'post' );
		} // if()

		return $excerpt;
	} // get_more_content_excerpt_fallback()



	/**
	 * Handles setting the transient.
	 *
	 * @param  string   $transient    Transient name. Expected to not be SQL-escaped. Should be 45 characters or less in length as WordPress will prefix your name with "_transient_" or "_transient_timeout_" in the options table (depending on whether it expires or not). Longer key names will silently fail. See Trac #15058.
	 * @param  mixed    $value        Transient value. Expected to not be SQL-escaped.
	 * @param  integer  $expiration   Required, Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24). Default: HOUR_IN_SECONDS
	 *
	 * @return boolean                False if value was not set and true if value was set.
	 */
	public function set_transient(  $transient, $value, $expiration = null ) {
		$expiration = ( is_null( $expiration ) ) ? HOUR_IN_SECONDS : $expiration;
		$transient  = md5( $transient );

		if ( is_multisite() ) {
			return set_site_transient( $transient, $value, $expiration );
		} else {
			return set_transient( $transient, $value, $expiration );
		} // if/else()
	} // set_transient()



	/**
	 * Handles getting the transient.
	 *
	 * @param  string   $transient  Transient name. Expected to not be SQL-escaped. Should be 45 characters or less in length as WordPress will prefix your name with "_transient_" or "_transient_timeout_" in the options table (depending on whether it expires or not). Longer key names will silently fail. See Trac #15058.
	 *
	 * @return  mixed               Value of transient. If the transient does not exist, does not have a value, or has expired, then get_transient will return false. This should be checked using the identity operator ( === ) instead of the normal equality operator, because an integer value of zero (or other "empty" data) could be the data you're wanting to store. Because of this "false" value, transients should not be used to hold plain boolean values. Put them into an array or convert them to integers instead.
	 */
	public function get_transient(  $transient ) {
		$transient = md5( $transient );

		if ( is_multisite() ) {
			return get_site_transient( $transient );
		} else {
			return get_transient( $transient );
		} // if/else()
	} // get_transient()



	/**
	 * Appends $_GET parameters to an URL.
	 *
	 * @param   string  $base_url    The URL to append the $_GET parameters.
	 * @param   array   $get_params  Associative ARRAY or URL encode values.
	 *
	 * @return  string               The base URL with the appended $_GET parameters.
	 */
	public function append_get_params( $base_url, $get_params = array() ) {
		if ( empty( $get_params ) ) {
			return $base_url;
		} // if()
		$base_url = ( strpos( $base_url, '?' ) === false ) ? "{$base_url}?" : "{$base_url}&";

		$params = '';
		foreach ( $get_params as $key => $value ) {
			$params .= "{$key}=$value&";
		} // foreach

		$params = trim( $params, '&' );
		$url    = "{$base_url}{$params}";

		return $url;
	} // append_get_params()
} // END Class MDG_Generic()

global $mdg_generic;
$mdg_generic = new MDG_Generic();
