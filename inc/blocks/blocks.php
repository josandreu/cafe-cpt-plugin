<?php
add_action( 'acf/init', 'hfm_acf_init_blocks' );
function hfm_acf_init_blocks(): void {

	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type(
			array(
				'name'            => 'opening-hours',
				'title'           => 'Café Opening Hours',
				'description'     => 'Display opening hours for a café',
				'render_template' => 'inc/block-templates/opening-hours.php',
				'category'        => 'text',
				'icon'            => 'admin-comments',
				'api_version'     => 2,
				'keywords'        => array( 'opening hours', 'hours' ),
				'mode'            => 'preview',
				'supports'        => array(
					'jsx'             => true,
					'color'           => array(
						'text'       => true,
						'background' => false,
					),
					'align_text'      => true,
					'align'           => true,
					'anchor'          => true,
					'customClassName' => true,
				),
			)
		);
		acf_register_block_type(
			array(
				'name'            => 'cafe-description',
				'title'           => 'Café Description',
				'description'     => 'Display the café description',
				'render_template' => 'inc/block-templates/cafe-description.php',
				'category'        => 'text',
				'icon'            => 'admin-comments',
				'api_version'     => 2,
				'keywords'        => array( 'cafe description', 'description' ),
				'mode'            => 'preview',
				'supports'        => array(
					'jsx'        => true,
					'color'      => array(
						'text'       => true,
						'background' => true,
					),
					'align_text' => true,
					'align'      => true,
				),
			)
		);
	}
}
