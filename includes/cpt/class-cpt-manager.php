<?php
namespace WPTS\CPT;

defined( 'ABSPATH' ) || exit;

/**
 * Custom Post Type manager.
 *
 * By default this plugin indexes existing post types.
 * This class also ships a sample "wpts_resource" CPT as a demo,
 * which developers can disable via the filter below.
 */
class Manager {

	public function register(): void {
		/**
		 * Filter: wpts_register_sample_cpt
		 * Return false to disable the built-in "Resource" custom post type.
		 *
		 * @param bool $register  Default true.
		 */
		if ( apply_filters( 'wpts_register_sample_cpt', true ) ) {
			add_action( 'init', [ $this, 'register_resource_cpt' ] );
		}

		// Allow external code to register additional CPTs via action
		add_action( 'init', function () {
			/**
			 * Action: wpts_register_post_types
			 * Register additional custom post types to be included in search.
			 *
			 * Example:
			 *   add_action( 'wpts_register_post_types', function() {
			 *       register_post_type( 'my_cpt', [...] );
			 *   });
			 */
			do_action( 'wpts_register_post_types' );
		}, 11 );
	}

	public function register_resource_cpt(): void {
		$labels = [
			'name'               => _x( 'Resources', 'post type general name', 'wp-turbo-search' ),
			'singular_name'      => _x( 'Resource',  'post type singular name', 'wp-turbo-search' ),
			'add_new'            => __( 'Add New Resource', 'wp-turbo-search' ),
			'add_new_item'       => __( 'Add New Resource', 'wp-turbo-search' ),
			'edit_item'          => __( 'Edit Resource', 'wp-turbo-search' ),
			'view_item'          => __( 'View Resource', 'wp-turbo-search' ),
			'search_items'       => __( 'Search Resources', 'wp-turbo-search' ),
			'not_found'          => __( 'No resources found.', 'wp-turbo-search' ),
			'not_found_in_trash' => __( 'No resources found in Trash.', 'wp-turbo-search' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => [ 'slug' => 'resources' ],
			'supports'           => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-book',
		];

		/**
		 * Filter: wpts_resource_cpt_args
		 * Modify the built-in Resource CPT arguments before registration.
		 *
		 * @param array $args  The post type arguments.
		 */
		$args = apply_filters( 'wpts_resource_cpt_args', $args );

		register_post_type( 'wpts_resource', $args );
	}
}
