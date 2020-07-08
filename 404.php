<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Templates
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

// Remove default loop.
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'genesis_404' );
/**
 * This function outputs a 404 "Not Found" error message.
 *
 * @since 1.6
 */
function genesis_404() {

	genesis_markup(
		[
			'open'    => '<article class="entry">',
			'context' => 'entry-404',
		]
	);

	genesis_markup(
		[
			'open'    => '<h1 %s>',
			'close'   => '</h1>',
			'content' => apply_filters( 'genesis_404_entry_title', __( 'Oh oh! Page not found, error 404', 'genesis' ) ),
			'context' => 'entry-title',
		]
	);

	$genesis_404_content = sprintf(
		/* translators: %s: URL for current website. */
		__( 'The page you\'re looking for doesn\'t exist. Maybe you can go back to the <a href="%s">homepage</a> and check that out instead. Sorry about that!', 'genesis' ),
		esc_url( trailingslashit( home_url() ) )
	);

	$genesis_404_content = sprintf( '<p>%s</p>', $genesis_404_content );

	/**
	 * The 404 content (wrapped in paragraph tags).
	 *
	 * @since 2.2.0
	 *
	 * @param string $genesis_404_content The content.
	 */
	$genesis_404_content = apply_filters( 'genesis_404_entry_content', $genesis_404_content );

	genesis_markup(
		[
			'open'    => '<div %s>',
			'close'   => '</div>',
			'content' => $genesis_404_content,
			'context' => 'entry-content',
		]
	);

	genesis_markup(
		[
			'close'   => '</article>',
			'context' => 'entry-404',
		]
	);

}

genesis();
