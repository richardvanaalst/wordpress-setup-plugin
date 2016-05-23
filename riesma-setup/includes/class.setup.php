<?php
/**
 * Riesma Setup
 *
 */

if( ! defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'RiesmaSetup' ) ) :


class RiesmaSetup {


	/**
	 * Construct all settings
	 */
	function __construct() {

		// Add custom post types and taxonomy
		self::add_post_types();

		// Flush rewrite rules, so added post type permalinks work
		add_action( 'init', array( $this, 'flush_rewrite_rules' ) );

		// Order admin menu items
		add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );
		add_filter( 'menu_order', array( $this, 'custom_menu_order' ) );

		// Hide admin menu items
		add_action( 'admin_menu', array( $this, 'hide_admin_menu_items' ) );
	}


	/**
	 * Add custom post type, including (custom) taxonomies
	 */
	public function add_post_types() {

		// Load the list with custom post types
		require_once( 'posttypes.php' );

		if ( !empty( $cpts ) && is_array( $cpts ) ) {

			foreach ( $cpts as $cpt ) {
				// $$cpt['post_type'] = new RiesmaPostType( $cpt );
				$rcpt = new RiesmaPostType( $cpt );
			}
		}
	}


	/**
	 * Flush the permalink rewrite rules
	 */
	public function flush_rewrite_rules() {
		if ( WP_DEBUG ) {
			flush_rewrite_rules(false);
		}
	}


	/**
	 * Order admin menu items
	 *
	 * Default order:
	 * 'index.php',                 // Dashboard
	 * 'separator1',
	 * 'edit.php',                  // Posts
	 * 'upload.php',                // Media
	 * 'edit.php?post_type=page',   // Pages
	 * 'edit-comments.php',         // Comments
	 * 'edit.php?post_type=custom', // A custom post type, when menu_position isn't null
	 * 'separator-last',            // It's possible to add another 'separator2'
	 * 'themes.php',                // Appearance, admins only
	 * 'plugins.php',               // Plugins, admins only
	 * 'users.php',                 // Users, admins only
	 * 'profile.php',               // Profile, non-admins
	 * 'tools.php',                 // Tools
	 * 'options-general.php'        // Settings, admins only
	 */
	public function custom_menu_order( $menu_order ) {

		if ( !$menu_order ) return true;

		$ordered_menu = array(
			'index.php',
			'separator1',
			'edit.php',
			'edit.php?post_type=page'
		);

		return $ordered_menu;
	}


	/**
	 * Hide admin menu items
	 * Order of items is as they are after running custom_menu_order()
	 *
	 * All items for reference:
	 * remove_menu_page( 'index.php' );                 // Dashboard
	 * remove_menu_page( 'edit.php?post_type=page' );   // Pages
	 * remove_menu_page( 'edit.php' );                  // Posts
	 * remove_menu_page( 'edit.php?post_type=custom' ); // A custom post type, which can be added above
	 * remove_menu_page( 'upload.php' );                // Media
	 * remove_menu_page( 'edit-comments.php' );         // Comments
	 * remove_menu_page( 'themes.php' );                // Appearance, admins only
	 * remove_menu_page( 'plugins.php' );               // Plugins, admins only
	 * remove_menu_page( 'users.php' );                 // Users, admins only
	 * remove_menu_page( 'profile.php' );               // Profile, non-admins
	 * remove_menu_page( 'tools.php' );                 // Tools
	 * remove_menu_page( 'options-general.php' );       // Settings, admins only
	 */
	public function hide_admin_menu_items() {

		// When logged in as admin
		if ( current_user_can( 'administrator' ) ) {
			remove_menu_page( 'edit.php' );
			remove_menu_page( 'edit-comments.php' );
		}


		// When not logged in as admin
		if ( ! current_user_can( 'administrator' ) ) {
			remove_menu_page( 'edit.php' );
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'tools.php' );
		}
	}


} // class


endif; // if (!class_exists)


?>