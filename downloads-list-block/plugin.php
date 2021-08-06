<?php
/**
 * Plugin Name: Download File List Block
 * Description: Gutenburg block which lists out multiple file attachments for users to download.
 * Version: 1.0
 * Author: Kim Le
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}


/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.build.js - Backend.
 * 2. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function attachementslist_block_assets() { // phpcs:ignore

	// Register block editor script for backend.
	wp_register_script(
		'download-list-blocks',
		plugins_url('/dist/blocks.build.js', __FILE__ ),
		array(
			'wp-api',
			'wp-blocks',
			'wp-components',
			'wp-data',
			'wp-editor',
			'wp-element',
			'wp-i18n',
		),
		null,
		true
	);

	wp_register_script(
		'download-list-blocks-fontawesome',
		'https://kit.fontawesome.com/efcece40e2.js',
		array(),
		1.0,
		true
	);


	// Register block styles for both frontend + backend.
	wp_register_style(
		'download-list-blocks-style-css',
		plugins_url( '/dist/blocks.style.build.css', __FILE__ ),
		is_admin() ? array( 'wp-editor' ) : null,
		null
	);

	// Register block editor styles for backend.
	wp_register_style(
		'download-list-blocks-editor-css',
		plugins_url( '/dist/blocks.editor.build.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		null
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'download-list-blocks',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'download-list-blocks-plugin/download-list-blocks', array(
			'editor_script' => 'download-list-blocks',
			'editor_style'  => 'download-list-blocks-editor-css',
			'style'         => 'download-list-blocks-style-css',
			'script'				=> 'download-list-blocks-fontawesome'
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'attachementslist_block_assets' );



// dynamic block template
include( plugin_dir_path( __FILE__ ) . 'template/download-list-template.php');
include( plugin_dir_path( __FILE__ ) . 'template/file-template.php');

?>
