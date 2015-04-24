<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'centric', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'centric' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Zalo Solutions', 'centric' ) );
define( 'CHILD_THEME_URL', 'http://zalosolutions.se' );
define( 'CHILD_THEME_VERSION', '0.6.5' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Scripts
add_action( 'wp_enqueue_scripts', 'centric_load_scripts' );
function centric_load_scripts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,200italic,300italic,400italic|Raleway:600,900', array(), CHILD_THEME_VERSION );

	//wp_enqueue_style( 'dashicons' );

	$siteurl = site_url();
	$homeurl = home_url();
	$fontawesomestyledir = get_bloginfo( 'stylesheet_directory' );
	$fontawesomeurl = str_replace($siteurl, $homeurl, $fontawesomestyledir);

	/*echo 'site:' . $siteurl;
	echo '<br>home:' . $homeurl;
	echo '<br>url: ' . $fontawesomeurl;*/

	//wp_enqueue_style( 'zalo-fontawesome', get_bloginfo( 'stylesheet_directory' ) . '/font-awesome.min.css', array(), '1.0.0.', false );
	wp_enqueue_style( 'zalo-fontawesome', $fontawesomeurl . '/font-awesome.min.css', array(), CHILD_THEME_VERSION, false );

	wp_enqueue_script( 'zalo-imagesloaded', get_bloginfo( 'stylesheet_directory' ) . '/js/imagesloaded.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'zalo-global-js', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

}

//* Add new image sizes
add_image_size( 'featured-page', 960, 700, TRUE );
add_image_size( 'featured-post', 400, 300, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 80,
	'width'           => 360,
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'site-inner',
	'footer-widgets',
	'footer',
) );

/*add_filter( 'dynamic_sidebar_params', 'b3m_wrap_widget_titles', 20 );
function b3m_wrap_widget_titles( array $params ) {
  // $params will ordinarily be an array of 2 elements, we're only interested in the first element
  $widget =& $params[0];
  $widget['before_title'] = '<h4 id="' . $params[0]['widget_name'] . '" class="widgettitle"><span class="sidebar-title">';
  $widget['after_title'] = '</span></h4>';

  return $params;
}*/

/*add_filter('widget_title', 'widget_title_as_id');
function widget_title_as_id($title) {
    return '<span id="' . sanitize_title($title) . '">' . $title . '</span>';
}*/

/** Get rid of empty p-tags by running wpautop much later */
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99 );

function targetdiv_func($atts) {
		extract(shortcode_atts(array(
		"id" => ''
	), $atts));
		return '<div id="' . $id . '" class="targetdiv"></div>';
}
add_shortcode("targetdiv", "targetdiv_func");

function wrapperdiv_func($atts, $content = null) {
	extract(shortcode_atts(array(
		"class" => ''
	), $atts));
	return '<div class="' . $class . '">' . do_shortcode( $content ) . '</div>';
}
add_shortcode("wrapperdiv", "wrapperdiv_func");

function contactcard_func($atts) {
	extract(shortcode_atts(array(
		"title" => '',
		"phone" => '',
		"mail" => '',
		"address" => '',
		"postal" => '',
	), $atts));

	$replacechars = array(" ", "-", "–");
	$phonecleaned = str_replace($replacechars, "", $phone);

	return '<div class="contact-card">
						<h4 class="contact-title">' . $title . '</h4>
						<div class="price">
							<a href="tel:' . $phonecleaned . '"><span class="phone fa fa-phone-square">' . $phone . '</span></a>
							<a href="mailto:' . $mail . '"><span class="mail fa fa-envelope-square">' . $mail . '</span></a>
							<span class="address1">' . $address . '</span>
							<span class="address2">' . $postal . '</span>
						</div>
					</div>';
}
add_shortcode("contactcard", "contactcard_func");

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary navigation menu
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'centric' ) ) );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

add_action( 'genesis_before_header', 'zalo_responsive_check', 1 );
function zalo_responsive_check() {
	echo '<div class="responsive-check"></div>';
}

//* Reposition Page Title
add_action( 'genesis_before', 'centric_post_title' );
function centric_post_title() {

	if ( is_page() and !is_page_template() ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		add_action( 'genesis_after_header', 'centric_open_post_title', 1 );
		add_action( 'genesis_after_header', 'genesis_do_post_title', 2 );
		add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
	} elseif ( is_category() ) {
		remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
		add_action( 'genesis_after_header', 'centric_open_post_title', 1 ) ;
		add_action( 'genesis_after_header', 'genesis_do_taxonomy_title_description', 2 );
		add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
	} elseif ( is_search() ) {
        remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
        add_action( 'genesis_after_header', 'centric_open_post_title', 1 ) ;
        add_action( 'genesis_after_header', 'genesis_do_search_title', 2 );
        add_action( 'genesis_after_header', 'centric_close_post_title', 3 );
    }

}

function centric_open_post_title() {
	echo '<div class="page-title"><div class="wrap">';
}

function centric_close_post_title() {
	echo '</div></div>';
}

//* Prevent Page Scroll When Clicking the More Link
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );
function remove_more_link_scroll( $link ) {

	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;

}

//* Modify the size of the Gravatar in author box
add_filter( 'genesis_author_box_gravatar_size', 'centric_author_box_gravatar_size' );
function centric_author_box_gravatar_size( $size ) {

	return 96;

}

//* Modify the size of the Gravatar in comments
add_filter( 'genesis_comment_list_args', 'centric_comment_list_args' );
function centric_comment_list_args( $args ) {

    $args['avatar_size'] = 60;
	return $args;

}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'centric_remove_comment_form_allowed_tags' );
function centric_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Add support for 4-column footer widgets
add_theme_support( 'genesis-footer-widgets', 4 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-widgets-1',
	'name'        => __( 'Home 1', 'centric' ),
	'description' => __( 'This is the first section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-2',
	'name'        => __( 'Home 2', 'centric' ),
	'description' => __( 'This is the second section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-3',
	'name'        => __( 'Home 3', 'centric' ),
	'description' => __( 'This is the third section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-widgets-4',
	'name'        => __( 'Home 4', 'centric' ),
	'description' => __( 'This is the fourth section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'about-us-widgets',
	'name'        => __( 'About Us', 'centric' ),
	'description' => __( 'This is the about us section of the home page.', 'centric' ),
) );
genesis_register_sidebar( array(
	'id'          => 'contact-us-widgets',
	'name'        => __( 'Contact Us', 'centric' ),
	'description' => __( 'This is the contact us section of the home page.', 'centric' ),
) );

//* Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$site_title = get_bloginfo();
	$homeurl = get_site_url();
	$creds = '[footer_copyright] &middot; <a href="' . $homeurl . '">' . $site_title . '</a>';
	return $creds;
}
