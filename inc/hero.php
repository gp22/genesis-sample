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

	if ( is_front_page() ) {

		$hero = get_field( 'hero' );

		?>

		<section class="hero">
			<div class="wrap hero-inner">
				<div class="hero-heading-container">
					<h1 class="hero-heading">

						<?php if ( $hero['hero-heading'] ) echo esc_html( $hero['hero-heading'] ); ?>

					</h1>
					<p class="hero-subhead">

						<?php if ( $hero['hero-subhead'] ) echo esc_html( $hero['hero-subhead'] ); ?>

					</p>
				</div>
				<img
					alt="Paul Garcia"
					src="<?php echo get_stylesheet_directory_uri(); ?>/public/img/paul_garcia.jpg"
					class="hero-img"
				/>
			</div>

			<svg width="100%" height="50px" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1920 75"><defs><style>.a{fill:none;}.b{clip-path:url(#a);}.c,.d{fill:#ffffff;}.d{opacity:0.5;isolation:isolate;}</style><clipPath id="a"><rect class="a" width="1920" height="75"></rect></clipPath></defs><title>wave</title><g class="b"><path class="c" d="M1963,327H-105V65A2647.49,2647.49,0,0,1,431,19c217.7,3.5,239.6,30.8,470,36,297.3,6.7,367.5-36.2,642-28a2511.41,2511.41,0,0,1,420,48"></path></g><g class="b"><path class="d" d="M-127,404H1963V44c-140.1-28-343.3-46.7-566,22-75.5,23.3-118.5,45.9-162,64-48.6,20.2-404.7,128-784,0C355.2,97.7,341.6,78.3,235,50,86.6,10.6-41.8,6.9-127,10"></path></g><g class="b"><path class="d" d="M1979,462-155,446V106C251.8,20.2,576.6,15.9,805,30c167.4,10.3,322.3,32.9,680,56,207,13.4,378,20.3,494,24"></path></g><g class="b"><path class="d" d="M1998,484H-243V100c445.8,26.8,794.2-4.1,1035-39,141-20.4,231.1-40.1,378-45,349.6-11.6,636.7,73.8,828,150"></path></g></svg>
		</section>

		<?php

	}

}
