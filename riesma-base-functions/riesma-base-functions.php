<?php
/*
Plugin Name: Riesma base functions
	Version: 1.0.0
	Plugin URI:
	Description: Adding Riesma's base functions.

Author: Richard van Aalst
	Author URI: http://riesma.nl/
	License: GPL v3

Based on Bones Custom Post Type Example
	Developed by: Eddie Machado
	URL: http://themble.com/bones/

Copyright (c) 2012 i-Aspect: info@riesma.nl

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Usage
	Edit this file for setting the post types, admin menu etc.

	This plugin is using the Bones theme (see above) translations,
	therefore it's recommended to use that as your base theme.
	Even without this plugin it is!

TODO
	1a.	Create xml to edit settings instead of editing php files
	1b.	Create admin pages to edit settings instead of editing php/xml files
	2.	Write own translation? Falling back to Bones for now.
	3.	More...? Of course there is!

More information
	register_post_type	http://codex.wordpress.org/Function_Reference/register_post_type
	register_taxonomy	http://codex.wordpress.org/Function_Reference/register_taxonomy
	custom meta boxes	https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
*/



class Riesma_Functions {

	/**
	 * Set domain for translations.
	*/
	//private $translation_domain = 'default';		// WordPress default translations
	//private $translation_domain = 'riesma';		// Riesma translations
	private $translation_domain = 'bonestheme';		// Bones translations

	/**
	 * Class constructor
	*/
	function __construct() {

		// Add post type 'Products'
		add_action('init', array($this, 'riesma_add_posttype_products'));

		// Order admin menu items
		add_filter('custom_menu_order', create_function('', 'return true;'));
		add_filter('menu_order', 'riesma_order_admin_menu_items');

		// Remove admin menu items
		add_action('admin_menu', 'riesma_remove_admin_menu_items');
	}



	/**
	 * Add post type 'Products'
	*/
	public function riesma_add_posttype_products() {

		$domain = $translation_domain;

		// Add custom post type
		register_post_type('products',
			array(
				'labels' => array(
					// Name of the Custom Type group
					'name'               => __('Producten', $domain),
					// Name of individual Custom Type item
					'singular_name'      => __('Product', $domain),
					// All Items menu item
					'all_items'          => __('Alle producten', $domain),
					// Add New menu item
					'add_new'            => __('Nieuw product', $domain),
					// Add New display title
					'add_new_item'       => __('Nieuw product toevoegen', $domain),
					// Edit dialog
					'edit'               => __('Bewerken', $domain),
					// Edit display title
					'edit_item'          => __('Product bewerken', $domain),
					// New display title
					'new_item'           => __('Nieuw product', $domain),
					// View display title
					'view_item'          => __('Product bekijken', $domain),
					// Search Custom Type title
					'search_items'       => __('Producten zoeken', $domain),
					// No Entries Yet dialog
					'not_found'          => __('Geen producten gevonden.', $domain),
					// Nothing in the Trash dialog
					'not_found_in_trash' => __('Geen producten gevonden in de prullenbak.', $domain),
					// ?
					'parent_item_colon'  => ''
				),
				// Custom Type Description
				'description'         => __('Products post type.', $domain),
				// Show in the admin panel
				'public'              => true,
				// ?
				'publicly_queryable'  => true,
				// ?
				'exclude_from_search' => false,
				// Show in the admin panel
				'show_ui'             => true,
				// Allow vars to be used for querying post-type
				'query_var'           => true,
				// Icon of menu item
				'menu_icon'           => get_stylesheet_directory_uri().'/library/img/products-icon.png',
				// Rename the URL slug
				'rewrite'             => array('slug' => 'products', 'with_front' => false),
				// Rename the archive URL slug
				'has_archive'         => 'products',
				// ?
				'capability_type'     => 'post',
				// ?
				'hierarchical'        => false,
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
											'sticky'
										)
			)
		);


		// Add custom taxonomy as categories
		register_taxonomy('products_category',
			// Change to the name of register_post_type
			array('products'),
			array(
				/* When using name 'Categories' (en_US) or 'Categorieën' (nl_NL),
				 * WordPress provides all translated labels: only use 'label'.
				 * When using another name, (e.g. 'Locations'): use 'labels'.
				*/
				// Name of the Custom Taxonomy
				'label' => __('Categorieën', $domain),
				/*
				'labels' => array(
					// Name of the Custom Taxonomy group
					'name'              => __('Categorieën', $domain),
					// Name of individual Custom Taxonomy item
					'singular_name'     => __('Categorie', $domain),
					// Add New Custom Taxonomy title and button
					'add_new_item'      => __('Nieuwe categorie toevoegen', $domain),
					// Edit Custom Taxonomy page title
					'edit_item'         => __('Categorie bewerken', $domain),
					// Update Custom Taxonomy button in Quick Edit
					'update_item'       => __('Categorie bijwerken', $domain),
					// Search Custom Taxonomy button
					'search_items'      => __('Categorieën zoeken', $domain),
					// All Custom Taxonomy title in taxonomy's panel tab
					'all_items'         => __('Alle categorieën', $domain),
					// New Custom Taxonomy title in taxonomy's panel tab
					'new_item_name'     => __('Nieuwe categorie naam', $domain)
					// Custom Taxonomy Parent in taxonomy's panel select box
					'parent_item'       => __('Categorie hoofd', $domain),
					// Custom Taxonomy Parent title with colon
					'parent_item_colon' => __('Categorie hoofd:', $domain),
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
				'rewrite'           => array('slug' => 'product-categories', 'with_front' => true)
			)
		);


		// Add custom taxonomy as tags
		register_taxonomy('products_tag',
			// Change to the name of register_post_type
			array('products'),
			array(
				/* When using name 'Tags' (en_US and nl_NL),
				 * WordPress provides all translated labels: only use 'label'.
				 * When using another name, (e.g. 'Locations'): use 'labels'.
				*/
				// Name of the Custom Taxonomy
				'label' => __('Tags', $domain),
				/*
				'labels' => array(
					// Name of the Custom Taxonomy group
					'name'              => __('Tags', $domain),
					// Name of individual Custom Taxonomy item
					'singular_name'     => __('Tag', $domain),
					// Add New Custom Taxonomy title and button
					'add_new_item'      => __('Nieuwe tag toevoegen', $domain),
					// Edit Custom Taxonomy page title
					'edit_item'         => __('Tag bewerken', $domain),
					// Update Custom Taxonomy button in Quick Edit
					'update_item'       => __('Tag bijwerken', $domain),
					// Search Custom Taxonomy button
					'search_items'      => __('Tags zoeken', $domain),
					// All Custom Taxonomy title in taxonomy's panel tab
					'all_items'         => __('Alle tags', $domain),
					// New Custom Taxonomy title in taxonomy's panel tab
					'new_item_name'     => __('Nieuwe tag naam', $domain)
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
				'rewrite'           => array('slug' => 'product-tags', 'with_front' => true)
			)
		);


		// Add WordPress' default post categories to custom post type
		//register_taxonomy_for_object_type('category', 'products');
		// Add WordPress' default post tags to custom post type
		//register_taxonomy_for_object_type('post_tag', 'products');

	} // riesma_add_posttype_products



	/**
	 * Order admin menu items
	*/
	public function riesma_order_admin_menu_items($menu) {
		$ordered_menu = array(
			'index.php',
				'separator1',
			'edit.php?post_type=page',
			'edit.php',
		//	'edit.php?post_type=custom',
				'separator2',
			'edit-comments.php',
			'upload.php',
			'link-manager.php',
				'separator-last',
			'themes.php',
			'plugins.php',
			'users.php',
			'tools.php',
			'options-general.php'
		);
		array_splice($menu, 1, 0, $ordered_menu);
		return array_unique($menu);
	} // riesma_order_admin_menu_items



	/**
	 * Remove admin menu items
	*/
	public function riesma_remove_admin_menu_items() {

		/* When logged in as admin. */
		if (is_admin()) {
			//remove_menu_page('edit.php?post_type=page');
			//remove_menu_page('edit.php');
		//	//remove_menu_page('edit.php?post_type=custom');
			//remove_menu_page('edit-comments.php');
			//remove_menu_page('upload.php');
			//remove_menu_page('link-manager.php');
			//remove_menu_page('themes.php');
			//remove_menu_page('plugins.php');
			//remove_menu_page('users.php');
			//remove_menu_page('tools.php');
			//remove_menu_page('options-general.php');
		}

		/* When not logged in as admin. */
		else {
			//remove_menu_page('edit.php?post_type=page');
			//remove_menu_page('edit.php');
			//remove_menu_page('edit-comments.php');
		//	//remove_menu_page('edit.php?post_type=custom');
			//remove_menu_page('upload.php');
			//remove_menu_page('link-manager.php');
			//remove_menu_page('profile.php');
			//remove_menu_page('tools.php');
		}
	} // riesma_remove_admin_menu_items

} // riesma_Functions



//global $riesma_fucntions;
$riesma_functions = new Riesma_Functions();



?>