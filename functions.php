<?php
/**
 * Paul Garcia Co.
 *
 * This file adds functions to the Paul Garcia Co Theme.
 * Based on the Genesis Sample Theme.
 *
 * @package Paul Garcia Co
 * @author  Paul Garcia
 * @license GPL-2.0-or-later
 * @link    https://github.com/gp22/genesis-sample
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

add_action( 'wp_enqueue_scripts', 'pg_global_enqueues' );
/**
 * Global enqueues
 *
 * @since 1.0.0
 */
function pg_global_enqueues() {

	// CSS
	wp_dequeue_style( 'child-theme' );
	wp_enqueue_style( 'pg-style', get_stylesheet_directory_uri() . '/public/css/style.css', array(), filemtime( get_stylesheet_directory() . '/public/css/style.css' ) );

}

add_action( 'after_setup_theme', 'pg_child_theme_setup' );
/**
 * Theme setup.
 *
 * Attach all of the site-wide functions to the correct hooks and filters.
 *
 * @since 1.0.0
 */
function pg_child_theme_setup() {

	// Repositions primary navigation menu.
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_header', 'genesis_do_nav', 12 );

	// Repositions the secondary navigation menu.
	remove_action( 'genesis_after_header', 'genesis_do_subnav' );
	add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

	// Theme
	include_once( get_stylesheet_directory() . '/inc/site-footer.php' );
	include_once( get_stylesheet_directory() . '/inc/hero.php' );

	// Removes site layouts.
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'content-sidebar' );
	genesis_unregister_layout( 'sidebar-content' );

	// Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	/* Gutenberg
	----------------------------------------------------------------------------*/

	// Add support for editor styles
	add_theme_support( 'editor-styles' );
	// add_editor_style( get_stylesheet_directory_uri() . '/public/css/editor.css' );

	// Adds support for block alignments
	add_theme_support( 'align-wide' );

	// Make media embeds responsive.
	add_theme_support( 'responsive-embeds' );

	// Disable custom font sizes
	add_theme_support( 'disable-custom-font-sizes' );

	// -- Editor Font Styles
	add_theme_support( 'editor-font-sizes', [
		[
			'name' => __( 'Small', 'genesis-sample' ),
			'size' => 12,
			'slug' => 'small',
		],
		[
			'name' => __( 'Normal', 'genesis-sample' ),
			'size' => 18,
			'slug' => 'normal',
		],
		[
			'name' => __( 'Large', 'genesis-sample' ),
			'size' => 20,
			'slug' => 'large',
		],
		[
			'name' => __( 'Larger', 'genesis-sample' ),
			'size' => 24,
			'slug' => 'larger',
		],
	]);

	// Disable Custom Colors
	add_theme_support( 'disable-custom-colors' );

	// Editor Color Palette
	add_theme_support( 'editor-color-palette', [
		[
			'name'  => __( 'Custom color', 'genesis-sample' ), // Called “Link Color” in the Customizer options. Renamed because “Link Color” implies it can only be used for links.
			'slug'  => 'theme-primary',
			'color' => '#0073e5',
		],
		[
			'name'  => __( 'Accent color', 'genesis-sample' ),
			'slug'  => 'theme-secondary',
			'color' => '#0073e5',
		],
	]);
}

/* Everything below this line was included in the default Genesis sample theme
----------------------------------------------------------------------------- */

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
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

// Removes site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

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
