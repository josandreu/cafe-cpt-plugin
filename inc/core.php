<?php
/**
 * Core functions file for the plugin.  This file sets up default actions/filters and defines other functions
 * needed within the plugin.
 *
 */

/* Filter the post type archive title. */

add_filter( 'post_type_archive_title', 'cafe_cpt_post_type_archive_title' );

/* Add custom image sizes (for menu listing in admin). */
add_action( 'init', 'cafe_cpt_add_image_sizes' );

/**
 * Returns the default plugin settings.
 *
 * @return array
 * @since  1.0.0
 * @access public
 */
function cafe_cpt_get_default_settings(): array {

	return array(
		'cafe_cpt_item_archive_title' => __( 'Cafés', 'cafe_cpt' ),
		'cafe_cpt_item_description'   => __( 'Delicious coffee.', 'cafe_cpt' )
	);
}

/**
 * Defines the base URL slug for the "café" section of the Web site. https://jaandreu-local.com/cafe/tags/nombre-tag/ | https://jaandreu-local.com/cafe/items/cafe-ejemplo-1/
 *
 * @return string
 * @since  1.0.0
 * @access public
 */
function cafe_cpt_url_base(): string {
	return apply_filters( 'cafe_cpt_url_base', 'cafes' );
}

/**
 * Filters 'post_type_archive_title' to use our custom 'archive_title' label.
 *
 * @param string $title
 *
 * @return string
 * @since  1.0.0
 * @access public
 */
function cafe_cpt_archive_title( string $title ): string {

	if ( is_post_type_archive( 'cafe_cpt_item' ) ) {
		$post_type = get_post_type_object( 'cafe_cpt_item' );
		$title     = $post_type->labels->archive_title ?? $title;
	}

	return $title;
}

/**
 * Adds a custom image size for viewing in the admin edit posts screen.
 *
 * @return void
 * @since  1.0.0
 * @access public
 */
function cafe_cpt_add_image_sizes(): void {
	add_image_size( 'cafe-cpt-thumbnail', 100, 75, true );
}
