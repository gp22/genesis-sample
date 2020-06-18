<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

add_action( 'wp_enqueue_scripts', 'global_enqueues' );
/**
 * Global enqueues
 *
 * @since 1.0.0
 */
function global_enqueues() {
	// CSS
	wp_dequeue_style( 'child-theme' );
	wp_enqueue_style( 'global-style', get_stylesheet_directory_uri() . '/public/css/style.css', array(), filemtime( get_stylesheet_directory() . '/public/css/style.css' ) );
}

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		genesis_get_theme_version()
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_action( 'after_setup_theme', 'genesis_sample_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'genesis_sample_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-singular-images', 702, 526, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

add_filter( 'genesis_footer_output', 'genesis_child_footer_output' );
/**
 * Modifies the footer output.
 *
 * @param string $output footer output from customizer.
 * @return string modified footer output.
 */
function genesis_child_footer_output( $output ) {

return <<<HTML

	<div class="contact-container">

		<div class="contact">
			<a href="mailto:hello@paulgarcia.co" class="email">
				<svg width="32" height="24" viewBox="0 0 32 24"><path d="M31.394 71.925a.376.376 0 01.606.294V85a3 3 0 01-3 3H3a3 3 0 01-3-3V72.225a.374.374 0 01.606-.294c1.4 1.088 3.256 2.469 9.631 7.1 1.319.963 3.544 2.988 5.762 2.975 2.231.019 4.5-2.05 5.769-2.975 6.376-4.631 8.226-6.018 9.626-7.106zM16 80c1.45.025 3.537-1.825 4.587-2.587 8.294-6.019 8.925-6.544 10.837-8.044A1.5 1.5 0 0032 68.188V67a3 3 0 00-3-3H3a3 3 0 00-3 3v1.188a1.5 1.5 0 00.575 1.181c1.912 1.494 2.544 2.025 10.837 8.044 1.05.762 3.138 2.612 4.588 2.587z" transform="translate(0 -64)" fill="#fff"></path></svg>
			</a>
			<a href="https://github.com/gp22" target="_blank" class="github">
				<svg width="24" height="24" viewBox="0 0 24 24"><path d="M21.429 32H2.571A2.572 2.572 0 000 34.571v18.858A2.572 2.572 0 002.571 56h18.858A2.572 2.572 0 0024 53.429V34.571A2.572 2.572 0 0021.429 32zm-6.574 20.555c-.45.08-.616-.2-.616-.429 0-.289.011-1.768.011-2.963a2.087 2.087 0 00-.605-1.645c1.982-.22 4.071-.493 4.071-3.916a2.75 2.75 0 00-.916-2.089 3.35 3.35 0 00-.091-2.411c-.745-.23-2.448.959-2.448.959a8.427 8.427 0 00-4.457 0S8.1 38.873 7.355 39.1a3.325 3.325 0 00-.091 2.411 2.691 2.691 0 00-.835 2.089c0 3.407 2 3.7 3.98 3.916a1.942 1.942 0 00-.568 1.195 1.88 1.88 0 01-2.588-.745 1.866 1.866 0 00-1.366-.916c-.868-.011-.059.546-.059.546.579.268.986 1.3.986 1.3.52 1.591 3.005 1.055 3.005 1.055 0 .745.011 1.955.011 2.175s-.161.509-.616.429a8.888 8.888 0 01-6.014-8.48 8.5 8.5 0 018.679-8.652 8.691 8.691 0 018.9 8.652 8.806 8.806 0 01-5.924 8.48zM9.6 49.282c-.1.021-.2-.021-.209-.091s.059-.15.161-.171.2.032.209.1-.054.139-.161.161zm-.509-.048c0 .07-.08.129-.187.129s-.2-.048-.2-.129.08-.129.188-.129.199.049.199.129zm-.734-.059c-.021.07-.129.1-.22.07s-.171-.1-.15-.171.129-.1.22-.08c.107.032.177.113.15.182zm-.657-.289c-.048.059-.15.048-.23-.032s-.1-.171-.048-.22.15-.048.23.032.094.177.048.22zm-.489-.486c-.048.032-.139 0-.2-.08s-.059-.171 0-.209.15-.011.2.07a.157.157 0 010 .219zm-.348-.52c-.048.048-.129.021-.187-.032-.059-.07-.07-.15-.021-.188s.129-.021.188.032c.057.069.068.149.019.187zm-.359-.4a.1.1 0 01-.15.021c-.07-.032-.1-.091-.08-.139a.126.126 0 01.15-.021c.069.039.101.098.076.141z" transform="translate(0 -32)" fill="#fff"></path></svg>
			</a>
			<a href="https://www.linkedin.com/in/paulgarcia22" target="_blank" class="linkedin">
				<svg width="24" height="24" viewBox="0 0 24 24"><path d="M22.286 32H1.709A1.722 1.722 0 000 33.73v20.54A1.722 1.722 0 001.709 56h20.577A1.726 1.726 0 0024 54.27V33.73A1.726 1.726 0 0022.286 32zM7.254 52.571H3.7V41.118h3.559v11.453zM5.475 39.554a2.063 2.063 0 112.063-2.062 2.063 2.063 0 01-2.063 2.062zm15.112 13.017H17.03V47c0-1.329-.027-3.037-1.848-3.037-1.854 0-2.137 1.446-2.137 2.941v5.668H9.488V41.118H12.9v1.564h.048a3.747 3.747 0 013.37-1.848c3.6 0 4.27 2.373 4.27 5.459z" transform="translate(0 -32)" fill="#fff"></path></svg>
			</a>
		</div>

		$output

	</div>

HTML;

}
