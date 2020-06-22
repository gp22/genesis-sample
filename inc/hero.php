<?php
/**
 * Home Page Hero Section
 *
 * @package      PGGenesisChild
 * @author       Paul Garcia
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Home Page Hero Section
 *
 */
add_action( 'genesis_after_header', 'genesis_hero_section_output' );
/**
 * Echos the hero section.
 *
 */
function genesis_hero_section_output( ) {

echo <<<HTML

	<section>

	</section>

HTML;

}
