<?php
/**
 * File for registering custom post types.
 *
 */

/* Register custom post types on the 'init' hook. */
add_action( 'init', 'cafe_cpt_register_post_type' );

/* Filter the "enter title here" text. */
add_filter( 'enter_title_here', 'cafe_cpt_enter_title_here', 10, 2 );

/* Filter post updated messages for custom post types. */
// add_filter( 'post_updated_messages', 'rp_post_updated_messages' );

/**
 * Registers post types needed by the plugin.
 *
 * @return void
 */
function cafe_cpt_register_post_type(): void {

	$settings = get_option( 'cafe_cpt_settings', cafe_cpt_get_default_settings() );

	/* Set up the arguments for the post type. */
	$args = array(
		'description'         => $settings['cafe_cpt_item_description'],
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-food',
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => cafe_cpt_url_base(),
		'query_var'           => 'cafe_cpt_item',
		'capability_type'     => 'cafe_cpt_item',
		'map_meta_cap'        => true,

		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_cafe_cpt_item',
			'read_post'              => 'read_cafe_cpt_item',
			'delete_post'            => 'delete_cafe_cpt_item',

			// primitive/meta caps
			'create_posts'           => 'create_cafe_cpt_items',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_cafe_cpt_items',
			'edit_others_posts'      => 'manage_cafe_cpt',
			'publish_posts'          => 'manage_cafe_cpt',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_cafe_cpt',
			'delete_private_posts'   => 'manage_cafe_cpt',
			'delete_published_posts' => 'manage_cafe_cpt',
			'delete_others_posts'    => 'manage_cafe_cpt',
			'edit_private_posts'     => 'edit_cafe_cpt_items',
			'edit_published_posts'   => 'edit_cafe_cpt_items'
		),

		'rewrite' => array(
			// https://jaandreu-local.com/cafe/items/nombre-item/
			'slug'       => cafe_cpt_url_base() . '/items',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'revisions',
			'page-attributes',
			'editor',
		),

		'labels' => array(
			'name'               => __( 'Café Items', 'cafe_cpt' ),
			'singular_name'      => __( 'Café Item', 'cafe_cpt' ),
			'menu_name'          => __( 'Café', 'cafe_cpt' ),
			'name_admin_bar'     => __( 'Café Item', 'cafe_cpt' ),
			'all_items'          => __( 'Café Items', 'cafe_cpt' ),
			'add_new'            => __( 'Add Café Item', 'cafe_cpt' ),
			'add_new_item'       => __( 'Add New Café Item', 'cafe_cpt' ),
			'edit_item'          => __( 'Edit Café Item', 'cafe_cpt' ),
			'new_item'           => __( 'New Café Item', 'cafe_cpt' ),
			'view_item'          => __( 'View Café Item', 'cafe_cpt' ),
			'search_items'       => __( 'Search Café Items', 'cafe_cpt' ),
			'not_found'          => __( 'No café items found', 'cafe_cpt' ),
			'not_found_in_trash' => __( 'No café items found in trash', 'cafe_cpt' ),

			/* Custom archive label.  Must filter 'post_type_archive_title' to use. */
			'archive_title'      => $settings['cafe_cpt_item_archive_title'],
		)
	);

	register_post_type( 'cafe_cpt_item', $args );
}

function cafe_cpt_enter_title_here( $title, $post ) {
	// if ( 'cafe_cpt_item' === get_post_type() )
	if ( 'cafe_cpt_item' === $post->post_type ) {
		$title = __( 'Enter cafe item name', 'cafe_cpt' );
	}

	return $title;
}


/*function rp_post_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['restaurant_item'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Menu item updated. <a href="%s">View menu item</a>', 'restaurant' ), esc_url( get_permalink( $post_ID ) ) ),
		2 => '',
		3 => '',
		4 => __( 'Menu item updated.', 'restaurant' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Menu item restored to revision from %s', 'restaurant' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Menu item published. <a href="%s">View menu item</a>', 'restaurant' ), esc_url( get_permalink( $post_ID ) ) ),
		7 => __( 'Menu item saved.', 'restaurant' ),
		8 => sprintf( __( 'Menu item submitted. <a target="_blank" href="%s">Preview menu item</a>', 'restaurant' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		9 => sprintf( __( 'Menu item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview menu item</a>', 'restaurant' ), date_i18n( __( 'M j, Y @ G:i', 'restaurant' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Menu item draft updated. <a target="_blank" href="%s">Preview menu item</a>', 'restaurant' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	return $messages;
}*/

