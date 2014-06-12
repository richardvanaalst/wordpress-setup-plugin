<?php
/**
 * Riesma Setup
 *
 * Todo
 * 1.   Create easier way to edit the settings instead of editing this php file?
 *   a. Via XML, or
 *   b. Admin pages
 * 2.   Set default screen options.
 * 3.   Add Custom Post Type archive pages to menu (still needed?)
 *      (http://wordpress.org/plugins/add-custom-post-types-archive-to-nav-menus/)
 * 4.   Set menu order dynamically for custom post types
 * 5.   Rename the URL slug: find better way to swap characters and character encoding!
 * 6.   Add custom taxonomy class
 * 7.   Add translation: _x( 'text', 'context' ) => 'Nieuw' vs 'Nieuwe'?
 *
 * More information
 * register_post_type   http://codex.wordpress.org/Function_Reference/register_post_type
 * register_taxonomy    http://codex.wordpress.org/Function_Reference/register_taxonomy
 * custom meta boxes    https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 * custom post type     http://www.smashingmagazine.com/2012/11/08/complete-guide-custom-post-types/
*/



if ( !class_exists( 'RiesmaSetup' ) ) {



class RiesmaSetup {



	/**
	 * Construct all settings
	*/
	function __construct() {

		// Add custom post type(s) and taxonomy
		$this->add_cpt();
		// add_action( 'init', array( $this, 'add_cpt' ) );
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
	function add_cpt() {

		// Load the list with custom post types
		require_once( 'posttypes.php' );

		if ( !empty( $cpts ) && is_array( $cpts ) ) {

			foreach ( $cpts as $cpt ) {
				// $$cpt['posttype'] = new RiesmaCustomPostType( $cpt );
				$rcpt = new RiesmaPostType( $cpt );
			}

			// Flush permalink rewrite rules when developing
			if ( WP_DEBUG ) {
				// flush_rewrite_rules(false);
			}
		}
	}



	/**
	 * Flush the permalink rewrite rules
	 */
	function flush_rewrite_rules() {
		if ( WP_DEBUG ) {
			flush_rewrite_rules(false);
		}
	}



	/**
	 * Order admin menu items
	 */
	function custom_menu_order( $menu_order ) {

		if ( !$menu_order ) return true;

		// Default order:
		// 'index.php',                 // Dashboard
		// 'separator1',
		// 'edit.php',                  // Posts
		// 'upload.php',                // Media
		// 'edit.php?post_type=page',   // Pages
		// 'edit-comments.php',         // Comments
		// 'edit.php?post_type=custom', // A custom post type, when menu_position isn't null
		// 'separator-last',            // It's possible to add another 'separator2'
		// 'themes.php',                // Appearance, admins only
		// 'plugins.php',               // Plugins, admins only
		// 'users.php',                 // Users, admins only
		// 'profile.php',               // Profile, non-admins
		// 'tools.php',                 // Tools
		// 'options-general.php'        // Settings, admins only


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
	 */
	function hide_admin_menu_items() {

		// All items for reference:
		// remove_menu_page( 'index.php' );                 // Dashboard
		// remove_menu_page( 'edit.php?post_type=page' );   // Pages
		// remove_menu_page( 'edit.php' );                  // Posts
		// remove_menu_page( 'edit.php?post_type=custom' ); // A custom post type, which can be added above
		// remove_menu_page( 'upload.php' );                // Media
		// remove_menu_page( 'edit-comments.php' );         // Comments
		// remove_menu_page( 'themes.php' );                // Appearance, admins only
		// remove_menu_page( 'plugins.php' );               // Plugins, admins only
		// remove_menu_page( 'users.php' );                 // Users, admins only
		// remove_menu_page( 'profile.php' );               // Profile, non-admins
		// remove_menu_page( 'tools.php' );                 // Tools
		// remove_menu_page( 'options-general.php' );       // Settings, admins only


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



} // if (!class_exists)



?>