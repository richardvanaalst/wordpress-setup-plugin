<?php

/*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ***\

Plugin Name:   Riesma Base Setup
Plugin URI:    http://riesma.nl/
Description:   Adding custom post types, sorting and hiding admin menu items.
Version:       1.0.3
Author:        Richard van Aalst
Author URI:    http://riesma.nl/
License:       GPL v3

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

*** *** *** *** *** *** *** *** ***

Usage
Edit this php file for adding custom post types, sorting the admin menu etc.
This plugin uses default translations.

Todo
1.   Create easier way to edit the settings instead of editing this php file?
  a. Via XML, or
  b. Admin pages
2.   Set default screen options.
3.   Add Custom Post Type archive pages to menu (still needed?) (http://wordpress.org/plugins/add-custom-post-types-archive-to-nav-menus/).
4.   Set menu order dynamically for custom post types
5.   Rename the URL slug: find better way to swap characters and character encoding!
6.   Add custom taxonomy
7.   Add translation: _x( 'text', 'context' ) => 'Nieuw' vs 'Nieuwe'?

*** *** *** *** *** *** *** *** ***

More information
register_post_type   http://codex.wordpress.org/Function_Reference/register_post_type
register_taxonomy    http://codex.wordpress.org/Function_Reference/register_taxonomy
custom meta boxes    https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress

\*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ***/



class RiesmaBaseSetup {

	/**
	 * Class constructor
	*/

	function RiesmaBaseSetup() {

		// Add custom post type(s)
		add_action( 'init', array( &$this, 'do_add_cpt' ) );

		// Order admin menu items
		add_filter( 'custom_menu_order', array( &$this, 'custom_menu_order' ) );
		add_filter( 'menu_order', array( &$this, 'custom_menu_order' ) );

		// Remove admin menu items
		add_action( 'admin_menu', array( &$this, 'remove_admin_menu_items' ) );
	}



	/**
	 * Add custom post type, including (custom) taxonomies
	 * http://codex.wordpress.org/Function_Reference/register_post_type
	*/

	// Add custom post types here by executing more add_cpt()
	// RiesmaBaseSetup::add_cpt( 'type', 'Name', 'Plural items name', 'Singular item name', hierarchical, taxonomy );
	// taxonomy has predefined options which can optionally be passed through an array: array('cat', 'tag', 'WP_cat', 'WP_tag')
	//   cat       categories
	//   tag       tags
	//   WP_cat    WordPress default categories
	//   WP_tag    WordPress default tags
	function do_add_cpt() {
		// RiesmaBaseSetup::add_cpt( 'items', 'Items', 'Items', 'Item' );
		// RiesmaBaseSetup::add_cpt( 'clients', 'Cliënten', 'Cliënten', 'Cliënt', false, array('cat', 'tag') );
		// RiesmaBaseSetup::add_cpt( 'products', 'Producten', 'Producten', 'Product' );
		// RiesmaBaseSetup::add_cpt( 'portfolio', 'Portfolio', 'Portfolio cases', 'Portfolio case' );
	}

	function add_cpt( $cpt, $cpt_name, $cpt_plural, $cpt_singular, $cpt_hierarchical = false, $cpt_tax = false ) {

		/**
		 * Add the custom post type
		*/

		register_post_type( $cpt,

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
				'description'         => __( $cpt . ' post type' ),

				// Show in the admin panel
				'public'              => true,
				// Position in admin menu (integer, default: null, below Comments)
				// Remember that menu position will be set via custom_menu_order as well
				'menu_position'       => null,
				// Icon of menu item (path based on Bones theme)
				'menu_icon'           => get_stylesheet_directory_uri() . '/library/img/' . $cpt . '-icon.png',

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
				    //'page-attributes', // enable when hierarchical is set to true
				    'post-formats'
				),

				// Rename the archive URL slug (default: false | post_type ($cpt) when true)
				'has_archive'         => true,
				// Rename the URL slug
				'rewrite'             => array(
				    'slug' => str_replace( array(' ', '"'), array('-', ''), iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', strtolower($cpt_name) ) )
				)

			)
		);



		/**
		 * Add custom taxonomy as categories
		 *
		 * When using the name 'Categories' (en_US) or 'Categorieën' (nl_NL), only use 'label' below.
		 * WordPress automatically provides translated labels)
		 * for en_US and nl_NL (with Dutch language installed).
		 *
		 * When using a custom name, (e.g. 'Locations'), use 'labels'.
		*/

		if ( $cpt_tax == 'cat' || in_array('cat', $cpt_tax) ) {

			$cat          = $cpt . '_category';
			$cat_plural   = 'Categorieën';
			$cat_singular = 'Categorie';

			register_taxonomy( $cat,

				// Name of register_post_type
				array( $cpt ),

				array(

					// Name of the Custom Taxonomy
					'label' => __( $cat_plural ),

					/*
					// Extended options
					'labels' => array(
						// Name of the Custom Taxonomy group
						'name'              => __( $cat_plural ),
						// Name of individual Custom Taxonomy item
						'singular_name'     => __( $cat_singular ),
						// Add New Custom Taxonomy title and button
						'add_new_item'      => __( 'Nieuwe ' . strtolower($cat_singular) . ' toevoegen' ),
						// Edit Custom Taxonomy page title
						'edit_item'         => __( $cat_singular . ' bewerken' ),
						// Update Custom Taxonomy button in Quick Edit
						'update_item'       => __( $cat_singular . ' bijwerken' ),
						// Search Custom Taxonomy button
						'search_items'      => __( $cat_plural . ' zoeken' ),
						// All Custom Taxonomy title in taxonomy's panel tab
						'all_items'         => __( 'Alle ' . strtolower($cat_plural) ),
						// New Custom Taxonomy title in taxonomy's panel tab
						'new_item_name'     => __( 'Nieuwe ' . strtolower($cat_singular) . ' naam' ),
						// Custom Taxonomy Parent in taxonomy's panel select box
						'parent_item'       => __( $cat_singular . ' hoofd' ),
						// Custom Taxonomy Parent title with colon
						'parent_item_colon' => __( $cat_singular . ' hoofd:' ),
					),
					*/

					// Hierachy: true = categories, false = tags
					'hierarchical'      => true,
					// Available in admin panel
					'public'            => true,
					// Show in the admin panel
					'show_ui'           => true,
					// Show in the menus admin panel
					'show_in_nav_menus' => true,
					// Allow vars to be used for querying taxonomy
					'query_var'         => true,
					// Rename the URL slug
					'rewrite'           => array( 'slug' => str_replace('_', '-', $cat), 'with_front' => true )
				)
			);
		}



		/**
		 * Add custom taxonomy as tags
		 *
		 * When using the name 'Tags' (en_US and nl_NL), only use 'label' below.
		 * WordPress automatically provides translated labels
		 * for en_US and nl_NL (with Dutch language installed).
		 *
		 * When using a custom name, (e.g. 'Locations'), use 'labels'.
		*/

		if ( $cpt_tax == 'tag' || in_array('tag', $cpt_tax) ) {

			$tag          = $cpt . '_tag';
			$tag_plural   = 'Tags';
			$tag_singular = 'Tag';

			register_taxonomy( $tag,

				// Name of register_post_type
				array( $cpt ),

				array(

					// Name of the Custom Taxonomy
					'label' => __( $tag_plural ),

					/*
					'labels' => array(
						// Name of the Custom Taxonomy group
						'name'              => __( $tag_plural ),
						// Name of individual Custom Taxonomy item
						'singular_name'     => __( $tag_singular ),
						// Add New Custom Taxonomy title and button
						'add_new_item'      => __( 'Nieuwe ' . strtolower($tag_singular) . ' toevoegen' ),
						// Edit Custom Taxonomy page title
						'edit_item'         => __( $tag_singular . ' bewerken' ),
						// Update Custom Taxonomy button in Quick Edit
						'update_item'       => __( $tag_singular . ' bijwerken' ),
						// Search Custom Taxonomy button
						'search_items'      => __( $tag_plural . ' zoeken' ),
						// All Custom Taxonomy title in taxonomy's panel tab
						'all_items'         => __( 'Alle ' . strtolower($tag_plural) ),
						// New Custom Taxonomy title in taxonomy's panel tab
						'new_item_name'     => __( 'Nieuwe ' . strtolower($tag_singular) . ' naam' )
					),
					*/

					// Hierachy: true = categories, false = tags
					'hierarchical'      => false,
					// Available in admin panel
					'public'            => true,
					// Show in the admin panel
					'show_ui'           => true,
					// Show in the menus admin panel
					'show_in_nav_menus' => true,
					// Allow vars to be used for querying taxonomy
					'query_var'         => true,
					// Rename the URL slug
					'rewrite'           => array( 'slug' => str_replace('_', '-', $tag), 'with_front' => true )
				)
			);
		}



		/**
		 * Add WordPress' default taxonomies to custom post type
		*/

		// Categories
		// if ( $cpt_tag == 'WP_cat' || in_array('WP_cat', $cpt_tax) ) {
		// 	 register_taxonomy_for_object_type( 'category', $cpt );
		// }
		// Tags
		// if ( $cpt_tag == 'WP_tag' || in_array('WP_tag', $cpt_tax) ) {
		// 	 register_taxonomy_for_object_type( 'post_tag', $cpt );
		// }
	}



	/**
	 * Order admin menu items
	*/

	function custom_menu_order( $menu_order ) {
		if ( !$menu_order ) return true;

		$ordered_menu = array(

			// Dashboard
			'index.php',

			'separator1',

			// Content
			'edit.php?post_type=page',
			'edit.php',
			'edit.php?post_type=custom', // Custom Post Type, which can be added above

			'separator2',

			// Media and comments
			'upload.php',
			'edit-comments.php',

			'separator-last',

			// Settings
			'themes.php',
			'plugins.php',
			'users.php',
			'profile.php',
			'tools.php',
			'options-general.php'
		);

		return $ordered_menu;
	}



	/**
	 * Remove admin menu items
	 * Order of items is as they are after running custom_menu_order()
	*/

	function remove_admin_menu_items() {

		// All items:
		// remove_menu_page( 'index.php' );                 // Dashboard
		// remove_menu_page( 'edit.php?post_type=page' );   // Pages
		// remove_menu_page( 'edit.php' );                  // Posts
		// remove_menu_page( 'edit.php?post_type=custom' ); // Custom Post Type, which can be added above
		// remove_menu_page( 'upload.php' );                // Media
		// remove_menu_page( 'edit-comments.php' );         // Comments
		// remove_menu_page( 'themes.php' );                // Appearance, default: admins only
		// remove_menu_page( 'plugins.php' );               // Plugins, default: admins only
		// remove_menu_page( 'users.php' );                 // Users, default: admins only
		// remove_menu_page( 'profile.php' );               // User profile, default: non-admins
		// remove_menu_page( 'tools.php' );                 // Tools
		// remove_menu_page( 'options-general.php' );       // Settings, default: admins only



		// Remove items which aren't used often for all users by default
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'tools.php' );

		// When logged in as admin
		/*if ( current_user_can( 'administrator' ) ) {
		}*/

		// When not logged in as admin
		/*else {
		}*/
	}

}



// Instantiate the class
$RiesmaBaseSetup = new RiesmaBaseSetup();



?>