<?php
/**
 * Riesma Post Type
 *
 * Add custom post type, including (custom) taxonomies,
 * by adding to the cpts array located in posttypes.php.
 */

if( ! defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'RiesmaPostType' ) ) :


class RiesmaPostType {


	var $the_post_type;
	var $labels;
	var $name;
	var $plural;
	var $singular;
	var $hierarchical;
	var $taxonomies;
	var $supports;
	var $slug;
	var $icon;

	var $supports_default = array(
	    'title',
	    'editor',
	    'author',
	    'thumbnail',
	    'excerpt',
	    // 'trackbacks',
	    'custom-fields',
	    // 'comments',
	    'revisions',
	    'page-attributes',
	    // 'post-formats'
	);


	/**
	 * Custom post type
	 *
	 * @param   array   $cpt
	 */
	function __construct( $cpt ) {

		$this->the_post_type = Riesma::slugify( $cpt['post_type'] );
		$this->labels        = $cpt['labels'];

		// Setup all labels
		/*
		$lang = get_language_function_still_to_write();
		// Dutch
		if ( $lang == 'nl' ) {

			if ( is_array( $this->labels ) ) {
				$this->name     = Riesma::titleify( $this->labels['name'] );
				$this->plural   = Riesma::titleify( $this->labels['plural'] );
				$this->singular = Riesma::titleify( $this->labels['singular'] );
			}
			else {
				$this->name     = Riesma::titleify( $this->labels . 's' );
				$this->plural   = Riesma::titleify( $this->labels . 's' );
				$this->singular = Riesma::titleify( $this->labels );
			}

			$this->hierarchical = !empty( $cpt['hierarchical'] ) ? $cpt['hierarchical'] : false;
			$this->taxonomies   = !empty( $cpt['taxonomies'] ) ? $cpt['taxonomies'] : false;
			$this->supports     = !empty( $cpt['supports'] ) ? $cpt['supports'] : $this->supports_default;
			$this->slug         = Riesma::slugify( $this->name );
			$this->icon         = Riesma::iconify( $this->the_post_type );
		}

		// English
		else {

			if ( is_array( $this->labels ) ) {
				$this->name          = Riesma::titleify( $this->labels['name'] );
				$this->singular_name = Riesma::titleify( $this->labels['singular'] );
				$this->plural        = Riesma::textify( $this->labels['plural'] );
				$this->singular      = Riesma::textify( $this->labels['singular'] );
			}
			else {
				$this->name          = Riesma::titleify( Riesma::pluralify( $this->labels ) );
				$this->singular_name = Riesma::titleify( $this->labels );
				$this->plural        = Riesma::textify( Riesma::pluralify( $this->labels ) );
				$this->singular      = Riesma::textify( $this->labels );
			}
		}
		*/

		$this->name          = Riesma::titleify( $this->labels['name'] );
		$this->singular_name = Riesma::titleify( $this->labels['singular'] );
		$this->plural        = Riesma::textify( $this->labels['plural'] );
		$this->singular      = Riesma::textify( $this->labels['singular'] );

		$this->hierarchical = !empty( $cpt['hierarchical'] ) ? $cpt['hierarchical'] : false;
		$this->taxonomies   = !empty( $cpt['taxonomies'] ) ? $cpt['taxonomies'] : false;
		$this->supports     = !empty( $cpt['supports'] ) ? $cpt['supports'] : $this->supports_default;
		$this->slug         = Riesma::slugify( $this->name );
		$this->icon         = Riesma::iconify( $this->the_post_type );


		// Add the post type, if it does not exist yet
		if ( ! post_type_exists( $this->the_post_type ) ) {
			add_action( 'init', array( $this, 'register_post_type' ) );
			add_action( 'init', array( $this, 'register_taxonomy' ) );
		}
	}


	/**
	 * Register post type
	 *
	 */
	public function register_post_type() {

		// Post type arguments
		$args =	array(

			'labels' => array(
				// Name of the post type group
				'name'               => sprintf( _x( '%s', 'post type general name' ), $this->name ),
				// Name of individual post type item (default: name)
				'singular_name'      => sprintf( _x( '%s', 'post type singular name' ), $this->singular_name ),
				// Name of menu item (default: name)
				// 'menu_name'          => sprintf( _x( '%s', 'admin menu' ), $this->name ),
				// Name in admin bar dropdown (default: singular_name | name)
				// 'name_admin_bar'     => sprintf( _x( '%s', 'add new on admin bar' ), $this->name ),
				// All Items menu item (default: name)
				'all_items'          => sprintf( __( 'All %s' ), $this->plural ),
				// Add New menu item
				'add_new'            => sprintf( _x( 'Add New',' %s' ), $this->singular ),
				// Add New page title
				'add_new_item'       => sprintf( __( 'Add New %s' ), $this->singular ),
				// Edit text
				'edit_item'          => sprintf( __( 'Edit %s' ), $this->singular ),
				// New display title
// where?				'new_item'           => sprintf( __( 'New %s' ), $this->singular ),
				// View button besides permalink
				'view_item'          => sprintf( __( 'View %s' ), $this->singular ),
				// Search button
				'search_items'       => sprintf( __( 'Search %s' ), $this->plural ),
				// No entries/search results dialog
				'not_found'          => sprintf( __( 'No %s found.' ), $this->plural ),
				// Nothing in the Trash dialog
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash.' ), $this->plural ),
				// Parent text, hierarchical types (pages) only
// where?				'parent_item_colon'  => sprintf( __( '%s Parent' ), $this->singular )
			),

			/* Dutch
			'labels' => array(
				// Name of the post type group
				'name'               => sprintf( _x( '%s', 'post type general name' ), $this->name ),
				// Name of individual post type item (default: name)
				'singular_name'      => sprintf( _x( '%s', 'post type singular name' ), $this->singular_name ),
				// Name of menu item (default: name)
				// 'menu_name'          => sprintf( _x( '%s', 'admin menu' ), $this->name ),
				// Name in admin bar dropdown (default: singular_name | name)
				// 'name_admin_bar'     => sprintf( _x( '%s', 'add new on admin bar' ), $this->name ),
				// All Items menu item (default: name)
				'all_items'          => sprintf( __( 'Alle ', Riesma::textify( $this->plural ) ),
				// Add New menu item
				'add_new'            => sprintf( __( $this->singular ), ' toevoegen' ),
				// Add New display title
				'add_new_item'       => sprintf( __( $this->singular ), '  toevoegen' ),
				// Edit display title
				'edit_item'          => sprintf( __( $this->singular ), ' bewerken' ),
				// New display title
				'new_item'           => sprintf( __( $this->singular ), ' toevoegen' ),
				// View display title
				'view_item'          => sprintf( __( $this->singular ), ' bekijken' ),
				// Search post type title
				'search_items'       => sprintf( __( $this->plural ), ' zoeken' ),
				// No Entries Yet dialog
				'not_found'          => sprintf( __( 'Geen ', Riesma::textify( $this->plural ) ), ' gevonden' ),
				// Nothing in the Trash dialog
				'not_found_in_trash' => __( 'Geen ' . Riesma::textify( $this->plural ) . ' gevonden in de prullenbak' ),
				// Parent text, hierarchical types (pages) only
				'parent_item_colon'  => ''
			),*/

			// Custom post type description
			'description'         => sprintf( __( 'This is the %s post type.' ), $this->name ),

			// Show in the admin panel
			'public'              => true,
			// Position in admin menu (integer, default: null, below Comments)
			// Remember that custom_menu_order can override this
			'menu_position'       => 5,
			// Icon of menu item
			'menu_icon'           => $this->icon,

			// String used for creating 'read', 'edit' and 'delete' links
			'capability_type'     => 'post',

			// Allow parent to be set (false = post, true = page)
			'hierarchical'        => $this->hierarchical,
			// Enable options in the post editor
			'supports'            => $this->supports,

			// Rename the archive URL slug
			'has_archive'         => $this->slug,
			// Rename the URL slug
			'rewrite'             => array(
				'slug'            => $this->slug,
				'with_front'      => true
			)
		);

		// Register the post type
		register_post_type( $this->the_post_type, $args );
	}


	/**
	 * Register taxonomy
	 *
	 */
	public function register_taxonomy() {

		if ( !empty( $this->taxonomies ) ) {
			foreach ( $this->taxonomies as $taxonomy ) {

				// Predefined taxonomy
				if ( !is_array( $taxonomy ) ) {

					switch ( $taxonomy ) {

						// Categories (predefined): WordPress provides translation
						case 'cat':

							register_taxonomy( $this->the_post_type . '_category',
								array( $this->the_post_type ),
								array(
									'hierarchical'   => true,
									'rewrite'        => array(
										'slug'       => $this->slug . '-' . Riesma::slugify( __( 'Categories' ) ),
										'with_front' => true
									)
								)
							);
							break;


						// Tags (predefined): WordPress provides translation
						case 'tag':

							register_taxonomy( $this->the_post_type . '_tag',
								array( $this->the_post_type ),
								array(
									'hierarchical'   => false,
									'rewrite'        => array(
										'slug'       => $this->slug . '-' . Riesma::slugify( __( 'Tags' ) ),
										'with_front' => true
									)
								)
							);
							break;


						// WordPress default post categories
						case 'WP_cat':
							register_taxonomy_for_object_type( 'category', $this->the_post_type );
							break;


						// WordPress default post tags
						case 'WP_tag':
							register_taxonomy_for_object_type( 'post_tag', $this->the_post_type );
							break;
					}
				}

				// Custom taxonomy
				else if ( is_array( $taxonomy ) ) {

					$the_taxonomy     = $this->the_post_type . '_' . $taxonomy['taxonomy'];
					$tax_name         = $this->the_post_type . '_' . $taxonomy['name'];
					$tax_plural       = $taxonomy['plural'];
					$tax_singular     = $taxonomy['singular'];
					$tax_hierarchical = !empty( $taxonomy['hierarchical'] ) ? $taxonomy['hierarchical'] : true;
					$tax_slug         = $this->slug . '-' . Riesma::slugify( $tax_name );


					register_taxonomy( $the_taxonomy,

						// Name of register_post_type
						array( $this->the_post_type ),

						array(

							'labels' => array(
								// Name of the taxonomy group
								'name'              => __( $tax_plural ),
								// Name of individual taxonomy item
								'singular_name'     => __( $tax_singular ),
								// Add New taxonomy title and button
//								'add_new_item'      => __( $tax_singular . ' toevoegen' ),
								// Edit taxonomy page title
//								'edit_item'         => __( $tax_singular . ' bewerken' ),
								// Update taxonomy button in Quick Edit
//								'update_item'       => __( $tax_singular . ' bijwerken' ),
								// Search taxonomy button
//								'search_items'      => __( $tax_plural . ' zoeken' ),
								// All taxonomy title in taxonomy's panel tab
//								'all_items'         => __( 'Alle ' . Riesma::textify( $tax_plural ) ),
								// New taxonomy title in taxonomy's panel tab
//								'new_item_name'     => __( 'Nieuwe ' . Riesma::textify( $tax_singular ) . ' naam' ),
								// taxonomy Parent in taxonomy's panel select box
//								'parent_item'       => __( $tax_singular . ' hoofd' ),
								// taxonomy Parent title with colon
//								'parent_item_colon' => __( $tax_singular . ' hoofd:' ),
							),

							// Hierachy: true = categories, false = tags
							'hierarchical'      => $tax_hierarchical,
							// Available in admin panel
							'public'            => true,
							// Show in the admin panel
							'show_ui'           => true,
							// Show in the menus admin panel
							'show_in_nav_menus' => true,
							// Show in the menus admin panel
							'show_admin_column' => true,
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


endif; // if (!class_exists)


?>