<?php
/**
 * Registers block patterns and categories.
 *
 * @return void
 */
function cafe_cpt_register_block_patterns(): void {
	$block_pattern_categories = array(
		'cafe-query-loop' => array( 'label' => __( 'Café Query Loop', 'cafe_cpt' ) ),
	);

	/**
	 * Filters the theme block pattern categories.
	 *
	 * @param array[] $block_pattern_categories {
	 *     An associative array of block pattern categories, keyed by category name.
	 *
	 * @type array[] $properties {
	 *         An array of block category properties.
	 *
	 * @type string $label A human-readable label for the pattern category.
	 *     }
	 * }
	 *
	 */
	$block_pattern_categories = apply_filters( 'cafe_cpt_register_block_patterns', $block_pattern_categories );

	foreach ( $block_pattern_categories as $name => $properties ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
			register_block_pattern_category( $name, $properties );
		}
	}

	$block_patterns = array(
		'query-loop-image-text-time',
	);

	/**
	 * Filters the theme block patterns.
	 *
	 * @param array $block_patterns List of block patterns by name.
	 */
	$block_patterns = apply_filters( 'cafe_cpt_block_patterns', $block_patterns );

	foreach ( $block_patterns as $block_pattern ) {
		$pattern_file = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'patterns/' . $block_pattern . '.php';

		register_block_pattern(
			'cafe_cpt/' . $block_pattern,
			require $pattern_file
		);
	}
}

add_action( 'init', 'cafe_cpt_register_block_patterns', 9 );
