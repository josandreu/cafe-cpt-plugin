<?php
/**
 * File for registering custom taxonomies.
 */

/* Register taxonomies on the 'init' hook. */
add_action( 'init', 'cafe_cpt_register_taxonomies' );

/**
 * Register taxonomies for the plugin.
 *
 * @return void.
 * @since  1.0.0
 * @access public
 */
function cafe_cpt_register_taxonomies(): void {

	$tag_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'query_var'         => 'cafe_cpt_tax',

		/* Only 2 caps are needed: 'manage_cafe' and 'edit_cafe_items'. */
		'capabilities'      => array(
			'manage_terms' => 'manage_cafe_cpt',
			'edit_terms'   => 'manage_cafe_cpt',
			'delete_terms' => 'manage_cafe_cpt',
			'assign_terms' => 'edit_cafe_cpt_items',
		),

		/* The rewrite handles the URL structure. https://jaandreu-local.com/cafe/tags/nombre-tag/ */
		'rewrite'           => array(
			'slug'         => cafe_cpt_url_base() . '/regions',
			'with_front'   => false,
			'hierarchical' => true,
			'ep_mask'      => EP_NONE
		),

		/* Labels used when displaying taxonomy and terms. */
		'labels'            => array(
			'name'                       => __( 'Regions', 'cafe_cpt' ),
			'singular_name'              => __( 'Region', 'cafe_cpt' ),
			'menu_name'                  => __( 'Café regions', 'cafe_cpt' ),
			'name_admin_bar'             => __( 'Regions', 'cafe_cpt' ),
			'search_items'               => __( 'Search regions', 'cafe_cpt' ),
			'popular_items'              => __( 'Popular regions', 'cafe_cpt' ),
			'all_items'                  => __( 'All regions', 'cafe_cpt' ),
			'edit_item'                  => __( 'Edit regions', 'cafe_cpt' ),
			'view_item'                  => __( 'View regions', 'cafe_cpt' ),
			'update_item'                => __( 'Update regions', 'cafe_cpt' ),
			'add_new_item'               => __( 'Add new region', 'cafe_cpt' ),
			'new_item_name'              => __( 'New region Name', 'cafe_cpt' ),
			'separate_items_with_commas' => __( 'Separate regions with commas', 'cafe_cpt' ),
			'add_or_remove_items'        => __( 'Add or remove regions', 'cafe_cpt' ),
			'choose_from_most_used'      => __( 'Choose from the most used regions', 'cafe_cpt' ),
		)
	);

	//create_terms( 'Ejemplo', 'ejemplo' );

	register_taxonomy( 'cafe_cpt_tax', array( 'cafe_cpt_item' ), $tag_args );
}

/**
 * Crea los valores iniciales (si no existen)
 *
 * @param string $term
 * @param string $slug
 *
 * @return void
 */
/*function create_terms( string $term, string $slug ): void {
	$taxos = get_object_taxonomies( 'cafe_cpt_item' );
	if ( ! term_exists( $term, 'cafe_cpt_tag' ) ) {
		wp_insert_term( $term, 'cafe_cpt_tag', array( 'slug' => $slug ) );
	}
}*/
