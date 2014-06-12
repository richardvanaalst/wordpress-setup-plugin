<?php
/**
 * Riesma Post Type
 *
 * Add custom post type, including (custom) taxonomies,
 * by adding to the cpts array located in list.posttype.php.
 */



if ( !class_exists( 'RiesmaPostType' ) ) {



class RiesmaPostType {



	var $posttype;
	var $name;
	var $plural;
	var $singular;
	var $hierarchical;
	var $taxonomies;
	var $slug;
	var $icon;



	/**
	 * Custom post type
	 *
	 * @param   array    $cpt
	 */
	// function __construct( $posttype, $name, $args = array(), $labels = array() ) {
	function __construct( $cpt ) {

		$this->posttype     = $cpt['posttype'];
		$this->name         = $cpt['name'];
		$this->plural       = $cpt['plural'];
		$this->singular     = $cpt['singular'];
		$this->hierarchical = !empty( $cpt['hierarchical'] ) ? $cpt['hierarchical'] : false;
		$this->taxonomies   = !empty( $cpt['taxonomies'] ) ? $cpt['taxonomies'] : false;
		$this->slug         = RiesmaSetup::slug( $this->name );
		$this->icon         = RiesmaSetup::icon( $this->posttype );


		// Add the post type, if it does not exist yet
		if( !post_type_exists( $this->posttype ) ) {
			// Priority 11, so it will be executed after RiesmaSetup
			add_action( 'init', array( $this, 'register_post_type' ), 11 );
		}
	}



	function register_post_type() {

		/**
		 * Add the custom post type
		 */
		register_post_type( $this->posttype,

			array(
				'labels' => array(
					// Name of the post type group
					'name'               => _x( $this->name, 'post type general name' ),
					// Name of individual post type item (default: name)
					'singular_name'      => _x( $this->singular, 'post type singular name' ),
					// Name of menu item (default: name)
					// 'menu_name'          => _x( $this->name, 'admin menu' ),
					// Name in admin bar dropdown (default: singular_name | name)
					// 'name_admin_bar'     => _x( $this->name, 'add new on admin bar' ),
					// All Items menu item (default: name)
					'all_items'          => __( 'Alle ' . strtolower($this->plural) ),
					// Add New menu item
					'add_new'            => __( $this->singular . ' toevoegen' ),
					// Add New display title
					'add_new_item'       => __( $this->singular . '  toevoegen' ),
					// Edit display title
					'edit_item'          => __( $this->singular . ' bewerken' ),
					// New display title
					'new_item'           => __( $this->singular . ' toevoegen' ),
					// View display title
					'view_item'          => __( $this->singular . ' bekijken' ),
					// Search post type title
					'search_items'       => __( $this->plural . ' zoeken' ),
					// No Entries Yet dialog
					'not_found'          => __( 'Geen ' . strtolower($this->plural) . ' gevonden' ),
					// Nothing in the Trash dialog
					'not_found_in_trash' => __( 'Geen ' . strtolower($this->plural) . ' gevonden in de prullenbak' ),
					// Parent text, hierarchical types (pages) only
					'parent_item_colon'  => ''
				),

				// Custom post type description
				'description'         => __( $this->name . ' post type.' ),

				// Show in the admin panel
				'public'              => true,
				// Position in admin menu (integer, default: null, below Comments)
				// Remember that custom_menu_order will override this
				'menu_position'       => 5,
				// Icon of menu item
				'menu_icon'           => $this->icon,

				// String used for creating 'read', 'edit' and 'delete' links
				'capability_type'     => 'post',

				// Allow parent to be set (post vs page type)
				'hierarchical'        => $this->hierarchical,
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
				'has_archive'         => $this->slug,
				// Rename the URL slug
				'rewrite'             => array(
				    'slug'            => $this->slug,
				    'with_front'      => true
				)
			)
		);



		/**
		 * Add custom taxonomy
		 */

		if ( !empty( $this->taxonomies ) && is_array( $this->taxonomies ) ) {

			foreach ( $this->taxonomies as $this->taxonomy ) {

				// Categories (predefined): WordPress provides translation
				if ( $this->taxonomy == 'cat' ) {

					register_taxonomy( $this->posttype . '_category',
						array( $this->posttype ),
						array(
							'hierarchical'   => true,
							'rewrite'        => array(
							    'slug'       => $this->slug . '-' . RiesmaSetup::slug( __( 'Categories' ) ),
							    'with_front' => true
							)
						)
					);
				}


				// Tags (predefined): WordPress provides translation
				else if ($this->taxonomy == 'tag' ) {

					register_taxonomy( $this->posttype . '_tag',
						array( $this->posttype ),
						array(
							'hierarchical'   => false,
							'rewrite'        => array(
							    'slug'       => $this->slug . '-' . RiesmaSetup::slug( __( 'Tags' ) ),
							    'with_front' => true
							)
						)
					);
				}


				// WordPress default post categories
				else if ($this->taxonomy == 'WP_cat' ) {
					register_taxonomy_for_object_type( 'category', $this->posttype );
				}


				// WordPress default post tags
				else if ($this->taxonomy == 'WP_tag' ) {
					register_taxonomy_for_object_type( 'post_tag', $this->posttype );
				}


				// Custom taxonomy
				else if ( is_array($this->taxonomy) ) {

					$the_tax          = $this->posttype . '_' . $this->taxonomy['taxonomy'];
					$tax_name         = $this->posttype . '_' . $this->taxonomy['name'];
					$tax_plural       = $this->taxonomy['plural'];
					$tax_singular     = $this->taxonomy['singular'];
					$tax_hierarchical = !empty( $this->taxonomy['hierarchical'] ) ? $this->taxonomy['hierarchical'] : true;
					$tax_slug         = $this->slug . '-' . RiesmaSetup::slug( $tax_name );


					register_taxonomy( $the_tax,

						// Name of register_post_type
						array( $this->posttype ),

						array(

							'labels' => array(
								// Name of the taxonomy group
								'name'              => __( $tax_plural ),
								// Name of individual taxonomy item
								'singular_name'     => __( $tax_singular ),
								// Add New taxonomy title and button
								'add_new_item'      => __( 'Nieuwe ' . strtolower($tax_singular) . ' toevoegen' ),
								// Edit taxonomy page title
								'edit_item'         => __( $tax_singular . ' bewerken' ),
								// Update taxonomy button in Quick Edit
								'update_item'       => __( $tax_singular . ' bijwerken' ),
								// Search taxonomy button
								'search_items'      => __( $tax_plural . ' zoeken' ),
								// All taxonomy title in taxonomy's panel tab
								'all_items'         => __( 'Alle ' . strtolower($tax_plural) ),
								// New taxonomy title in taxonomy's panel tab
								'new_item_name'     => __( 'Nieuwe ' . strtolower($tax_singular) . ' naam' ),
								// taxonomy Parent in taxonomy's panel select box
								'parent_item'       => __( $tax_singular . ' hoofd' ),
								// taxonomy Parent title with colon
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
	}



} // class



} // if (!class_exists)



?>