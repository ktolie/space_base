<?php
/**
 * Scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.css
 *
 * Enqueue scripts in the following order:
 * 2. /theme/assets/js/vendor/modernizr-2.7.0.min.js
 * 3. /theme/assets/js/main.min.js (in footer)
 */
function roots_scripts() {
  wp_enqueue_style('roots_main', get_template_directory_uri() . '/assets/css/main.min.css', false);

  if (!is_admin() && current_theme_supports('jquery-cdn')) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', array(), null, true);
    add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {wp_enqueue_script('comment-reply'); }

  wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.min.js', array(), false, false);
  wp_enqueue_script('modernizr');

  wp_enqueue_script('jquery');

  wp_register_script('roots_scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', array(), '0fc6af96786d8f267c8686338a34cd38', true);
  wp_enqueue_script('roots_scripts');
}
add_action('wp_enqueue_scripts', 'roots_scripts');

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;
  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/jquery.min.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }
  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }
  return $src;
}
add_action('wp_head', 'roots_jquery_local_fallback');








/**
 * Handles adding JavaScript and CSS.
 *
 * @package      WordPress
 * @subpackage   SPACE_BASE__Base
 */


/**
 * Enqueue front-end scripts and style-sheets
 *
 * @return Void
 */
function space_base_enqueue_site_scripts() {
  global $is_IE;

  $theme_uri = get_template_directory_uri();
  $ltie9     = preg_match( '/(?i)msie [6-8]/', $_SERVER['HTTP_USER_AGENT'] );

  // CSS
  if ( $ltie9 and $is_IE ) {
    // CSS for IE.
    wp_enqueue_style( 'main_css', "{$theme_uri}/assets/css/dist/main-ltie9.min.css", array(), null, 'all' );
  } else {
    // CSS for good browsers.
    wp_enqueue_style( 'main_css', "{$theme_uri}/assets/css/dist/main.min.css", array(), null, 'all' );
  } // if/else()

  // jQuery is loaded using the same method from HTML5 Boilerplate:
  // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
  // It's kept in the header instead of footer to avoid conflicts with plugins.
  if ( ! is_admin() && current_theme_supports( 'jquery-cdn' ) ) {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), null, false );
    add_filter( 'script_loader_src', 'space_base_jquery_local_fallback', 10, 2 );
  } // if()

  // Required for comments
  // if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
  //  wp_enqueue_script( 'comment-reply' );
  // } // if()

  // Reigster/Enqueue scripts for IE
  if ( $ltie9 and $is_IE ) {
    wp_register_script( 'css3pie_js', "{$theme_uri}/assets/js/vendor/css3pie-1.0.0.js", array( 'space_base_env_tests_js' ), null, false );
    wp_enqueue_script( 'css3pie_js' );
  } // if()


  // Reigster/Enqueue scripts for all browsers
  wp_register_script( 'space_base_env_tests_js', "{$theme_uri}/assets/js/dist/env-tests.min.js", array(), null, false );
  wp_enqueue_script( 'space_base_env_tests_js' );
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'jquery-effects-core' );
  wp_register_script( 'main_js', "{$theme_uri}/assets/js/dist/scripts.min.js", array(), null, true );
  wp_enqueue_script( 'main_js' );
} // space_base_enqueue_site_scripts()
add_action( 'wp_enqueue_scripts', 'space_base_enqueue_site_scripts', 100 );



/**
 * Adds a global JS object.
 *
 * @return Void
 */
function space_base_add_global_js() {
  $ltie9 = preg_match( '/(?i)msie [6-8]/', $_SERVER['HTTP_USER_AGENT'] );
  // Add Global PHP -> JS
  $space_base_globals = array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'isIE'    => ( $ltie9 and $is_IE ),
  );
  $space_base_globals = json_encode( $space_base_globals ); ?>
  <script>var SPACE_BASE_GLOBALS = <?php echo wp_kses( $space_base_globals, 'data' ); ?>;</script>
<?php } // space_base_add_global_js()
add_action( 'wp_head', 'space_base_add_global_js' );



/**
 * Enqueue administrator scripts/styles.
 *
 * @return Void
 */
function space_base_enqueue_admin_scripts() {
  global $is_IE;
  $theme_uri = get_template_directory_uri();
  $ltie9     = preg_match( '/(?i)msie [6-8]/', $_SERVER['HTTP_USER_AGENT'] );

  wp_enqueue_style( 'space_base-admin-css', "{$theme_uri}/assets/css/dist/admin.min.css", array( 'wp-color-picker' ), null, 'all' );

  wp_register_script( 'admin_scripts', "{$theme_uri}/assets/js/dist/admin.min.js", array( 'jquery', 'jquery-ui-datepicker', 'wp-color-picker' ), null, true );

  // Add Global PHP -> JS
  $space_base_globals = array(
    'isIE' => ( $ltie9 and $is_IE ),
  );
  wp_localize_script( 'admin_scripts', 'SPACE_BASE_GLOBALS', $space_base_globals );
  wp_enqueue_script( 'admin_scripts' );
} // space_base_enqueue_admin_scripts()
add_action( 'admin_enqueue_scripts', 'space_base_enqueue_admin_scripts', 100 );



/**
 * Adds a global JS object for wp-admin.
 *
 * @return Void
 */
function space_base_add_admin_global_js() {
  $ltie9 = preg_match( '/(?i)msie [6-8]/', $_SERVER['HTTP_USER_AGENT'] );
  // Add Global PHP -> JS
  $space_base_globals = array(
    'isIE' => ( $ltie9 and $is_IE ),
  );
  $space_base_globals = json_encode( $space_base_globals ); ?>
  <script>var SPACE_BASE_GLOBALS = <?php echo wp_kses( $space_base_globals, 'data' ); ?>;</script>
<?php } // space_base_add_admin_global_js()
add_action( 'admin_head', 'space_base_add_admin_global_js' );



/**
 * jQuery local fallback
 *
 * @see http://wordpress.stackexchange.com/a/12450
 *
 * @param  string  $src    Script src.
 * @param  string  $handle Script handle.
 *
 * @return string          Script src.
 */
function space_base_jquery_local_fallback( $src, $handle ) {
  static $add_jquery_fallback = false;

  if ( $add_jquery_fallback ) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery-1.11.0.min.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  } // if()

  if ( $handle === 'jquery' ) {
    $add_jquery_fallback = true;
  } // if()

  return $src;
} // space_base_jquery_local_fallback()



/**
 * Adds the favicon for the site, login, and administrator section.
 * Add favicon.png to /assets/img/.
 *
 * @return Void
 */
function space_base_add_favicon() {
  echo '<link rel="icon" href="'.get_template_directory_uri().'/assets/img/favicon.png" type="image/png">';
} // space_base__add_favicon()
add_action( 'wp_head', 'space_base_add_favicon' );
add_action( 'admin_head', 'space_base_add_favicon' );






/**
 * Handles out putting the scripts for the share this buttons.
 *
 * @return  void
 */
function space_base_sharethis_scripts() {
  $is_post_single = ( get_post_type() == 'post' and is_single() );
  if ( ! $is_post_single ) {
    return;
  } // if() ?>

  <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
  <script type="text/javascript">window.stLight.options({publisher: "820f18a5-6cfd-4845-81a1-f5593ee21e90", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
  <?php
} // space_base_sharethis_scripts()
add_action( 'wp_head', 'space_base_sharethis_scripts' );









/**
 * Google Analytics snippet from HTML5 Boilerplate
 */
function roots_google_analytics() { ?>
<script>
  (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
  function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
  e=o.createElement(i);r=o.getElementsByTagName(i)[0];
  e.src='//www.google-analytics.com/analytics.js';
  r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
  ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>');ga('send','pageview');
</script>

<?php }
if (GOOGLE_ANALYTICS_ID && !current_user_can('manage_options')) {
  add_action('wp_footer', 'roots_google_analytics', 20);
}
