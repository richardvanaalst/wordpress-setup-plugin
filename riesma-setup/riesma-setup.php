<?php
/*
Plugin Name:   Riesma Setup
Plugin URI:    http://riesma.nl/
Description:   Adding custom post types, sorting and hiding admin menu items.
Version:       1.0.4
Author:        Richard van Aalst
Author URI:    http://riesma.nl/

Copyright (C) 2012-2014 Richard van Aalst
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/*
Usage
Edit this php file for adding custom post types, sorting the admin menu etc.
This plugin uses default translations.

Todo
1.   Create easier way to edit the settings instead of editing this php file?
  a. Via XML, or
  b. Admin pages
2.   Set default screen options.
3.   Add Custom Post Type archive pages to menu (still needed?)
     (http://wordpress.org/plugins/add-custom-post-types-archive-to-nav-menus/)
4.   Set menu order dynamically for custom post types
5.   Rename the URL slug: find better way to swap characters and character encoding!
6.   Add custom taxonomy
7.   Auto-refresh permalinks after adding custom post type
8.   Add translation: _x( 'text', 'context' ) => 'Nieuw' vs 'Nieuwe'?

More information
register_post_type   http://codex.wordpress.org/Function_Reference/register_post_type
register_taxonomy    http://codex.wordpress.org/Function_Reference/register_taxonomy
custom meta boxes    https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
custom post type     http://www.smashingmagazine.com/2012/11/08/complete-guide-custom-post-types/
*/



class RiesmaSetup {

	function RiesmaSetup() {

		// Add custom post type(s) and taxonomy
		add_action( 'init', array( $this, 'add_cpt' ) );

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

		// Load the custom post types
		require_once( 'riesma-setup-cpt.php' );

		if ( !empty( $cpts ) && is_array( $cpts ) ) {

			/**
			 * The Custom Post Type Loop
			*/

			foreach ( $cpts as $cpt ) {

				$the_posttype     = $cpt['posttype'];
				$cpt_name         = $cpt['name'];
				$cpt_plural       = $cpt['plural'];
				$cpt_singular     = $cpt['singular'];
				$cpt_hierarchical = !empty( $cpt['hierarchical'] ) ? $cpt['hierarchical'] : false;
				$cpt_taxonomies   = !empty( $cpt['taxonomies'] ) ? $cpt['taxonomies'] : false;
				$cpt_slug         = RiesmaHelper::slug( $cpt_name );
				$cpt_icon         = RiesmaHelper::icon( $the_posttype );



				/**
				 * Add the custom post type
				 */

				register_post_type( $the_posttype,

					array(
						'labels' => array(
							// Name of the custom post type group
							'name'               => _x( $cpt_name, 'post type general name' ),
							// Name of individual custom post type item (default: name)
							'singular_name'      => _x( $cpt_singular, 'post type singular name' ),
							// Name of menu item (default: name)
							// 'menu_name'          => _x( $cpt_name, 'admin menu' ),
							// Name in admin bar dropdown (default: singular_name | name)
							// 'name_admin_bar'     => _x( $cpt_name, 'add new on admin bar' ),
							// All Items menu item (default: name)
							'all_items'          => __( 'Alle ' . strtolower($cpt_plural) ),
							// Add New menu item
							'add_new'            => __( $cpt_singular . ' toevoegen' ),
							// Add New display title
							'add_new_item'       => __( $cpt_singular . '  toevoegen' ),
							// Edit display title
							'edit_item'          => __( $cpt_singular . ' bewerken' ),
							// New display title
							'new_item'           => __( $cpt_singular . ' toevoegen' ),
							// View display title
							'view_item'          => __( $cpt_singular . ' bekijken' ),
							// Search custom post type title
							'search_items'       => __( $cpt_plural . ' zoeken' ),
							// No Entries Yet dialog
							'not_found'          => __( 'Geen ' . strtolower($cpt_plural) . ' gevonden' ),
							// Nothing in the Trash dialog
							'not_found_in_trash' => __( 'Geen ' . strtolower($cpt_plural) . ' gevonden in de prullenbak' ),
							// Parent text, hierarchical types (pages) only
							'parent_item_colon'  => ''
						),

						// Custom post type description
						'description'         => __( $cpt_name . ' post type.' ),

						// Show in the admin panel
						'public'              => true,
						// Position in admin menu (integer, default: null, below Comments)
						// Remember that custom_menu_order will override this
						'menu_position'       => 5,
						// Icon of menu item
						'menu_icon'           => $cpt_icon,

						// String used for creating 'read', 'edit' and 'delete' links
						'capability_type'     => 'post',

						// Allow parent to be set (post vs page type)
						'hierarchical'        => $cpt_hierarchical,
						// Enable options in the post editor
						'supports'            => array(
						    'title',
						    'editor',
						    'author',
						    'thumbnail',
						    'excerpt',
						    'trackbacks',
						    'custom-fields',
						    'comments',
						    'revisions',
						    'page-attributes',
						    'post-formats'
						),

						// Rename the archive URL slug
						'has_archive'         => $cpt_slug,
						// Rename the URL slug
						'rewrite'             => array(
						    'slug'            => $cpt_slug,
						    'with_front'      => true
						)
					)
				);



				/**
				 * Add custom taxonomy
				 */

				if ( !empty( $cpt_taxonomies ) && is_array( $cpt_taxonomies ) ) {

					foreach ( $cpt_taxonomies as $cpt_taxonomy ) {

						// Categories (predefined): WordPress provides translation
						if ( $cpt_taxonomy == 'cat' ) {

							register_taxonomy( $the_posttype . '_category',
								array( $the_posttype ),
								array(
									'hierarchical'   => true,
									'rewrite'        => array(
									    'slug'       => $cpt_slug . '-' . RiesmaHelper::slug( __( 'Categories' ) ),
									    'with_front' => true
									)
								)
							);
						}


						// Tags (predefined): WordPress provides translation
						else if ($cpt_taxonomy == 'tag' ) {

							register_taxonomy( $the_posttype . '_tag',
								array( $the_posttype ),
								array(
									'hierarchical'   => false,
									'rewrite'        => array(
									    'slug'       => $cpt_slug . '-' . RiesmaHelper::slug( __( 'Tags' ) ),
									    'with_front' => true
									)
								)
							);
						}


						// WordPress default post categories
						else if ($cpt_taxonomy == 'WP_cat' ) {
							register_taxonomy_for_object_type( 'category', $the_posttype );
						}


						// WordPress default post tags
						else if ($cpt_taxonomy == 'WP_tag' ) {
							register_taxonomy_for_object_type( 'post_tag', $the_posttype );
						}


						// Custom taxonomy
						else if ( is_array($cpt_taxonomy) ) {

							$the_tax          = $the_posttype . '_' . $cpt_taxonomy['taxonomy'];
							$tax_name         = $the_posttype . '_' . $cpt_taxonomy['name'];
							$tax_plural       = $cpt_taxonomy['plural'];
							$tax_singular     = $cpt_taxonomy['singular'];
							$tax_hierarchical = !empty( $cpt_taxonomy['hierarchical'] ) ? $cpt_taxonomy['hierarchical'] : true;
							$tax_slug         = $cpt_slug . '-' . RiesmaHelper::slug( $tax_name );


							register_taxonomy( $the_tax,

								// Name of register_post_type
								array( $the_posttype ),

								array(

									'labels' => array(
										// Name of the Custom Taxonomy group
										'name'              => __( $tax_plural ),
										// Name of individual Custom Taxonomy item
										'singular_name'     => __( $tax_singular ),
										// Add New Custom Taxonomy title and button
										'add_new_item'      => __( 'Nieuwe ' . strtolower($tax_singular) . ' toevoegen' ),
										// Edit Custom Taxonomy page title
										'edit_item'         => __( $tax_singular . ' bewerken' ),
										// Update Custom Taxonomy button in Quick Edit
										'update_item'       => __( $tax_singular . ' bijwerken' ),
										// Search Custom Taxonomy button
										'search_items'      => __( $tax_plural . ' zoeken' ),
										// All Custom Taxonomy title in taxonomy's panel tab
										'all_items'         => __( 'Alle ' . strtolower($tax_plural) ),
										// New Custom Taxonomy title in taxonomy's panel tab
										'new_item_name'     => __( 'Nieuwe ' . strtolower($tax_singular) . ' naam' ),
										// Custom Taxonomy Parent in taxonomy's panel select box
										'parent_item'       => __( $tax_singular . ' hoofd' ),
										// Custom Taxonomy Parent title with colon
										'parent_item_colon' => __( $tax_singular . ' hoofd:' ),
									),

									// Hierachy: true = categories, false = tags
									'hierarchical'      => $tax_hierarchical,
									// Available in admin panel
									'public'            => true,
									// Show in the admin panel
									'show_ui'           => true,
									// Show in the menus admin panel
									'show_in_nav_menus' => true,
									// Allow vars to be used for querying taxonomy
									'query_var'         => true,
									// Rename the URL slug
									'rewrite'           => array(
									    'slug'          => $tax_slug,
									    'with_front'    => true
									)
								)
							);
						}
					} // end foreach taxonomy
				}
			} // end foreach cpts
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

}



class RiesmaHelper {

	/**
	 * Create clean slug
	 * !! Improve this: __() returns &235; instead of ë
	 */
	function slug( $slug ) {
		return str_replace( array(' ', '"'), array('-', ''), iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', strtolower($slug) ) );
	}


	/**
	 * Check if icon file exists, else return default icon (Posts)
	 * Path based on Bones theme
	 */
	function icon( $cpt ) {
		$file = get_stylesheet_directory_uri() . '/library/img/' . $cpt . '-icon.png';
		$icon = file_exists($file) ? $file : false;
		return $icon;
	}

}



// Instantiate the setup
$RiesmaSetup = new RiesmaSetup();



?>