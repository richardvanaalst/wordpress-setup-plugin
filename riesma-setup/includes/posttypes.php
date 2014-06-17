<?php
/**
 * Riesma Custom Post Types List
 *
 * Add custom post type, including (custom) taxonomies,
 * by adding to the cpts array.
 * See (dutch) usage examples below.
 *
 * @param   array    $cpts
 *
 * @param   string   $post_type
 * @param   string   $name
 * @param   string   $plural
 * @param   string   $singular
 * @param   bool     $hierarchical   false = post, true = page
 * @param   array    $taxonomies
 *
 * $taxonomies has some optional predefined options:
 * cat      categories
 * tag      tags
 * WP_cat   WordPress default categories
 * WP_tag   WordPress default tags
 */

if( ! defined( 'ABSPATH' ) ) exit;


$cpts = array();


// Items
/*$cpts[] = array(
	'post_type'    => 'items',
	'name'         => 'Items',
	'plural'       => 'Items',
	'singular'     => 'Item',
	'hierarchical' => false,
	'taxonomies'   => array('cat', 'tag')
);*/


// Portfolio
/*$cpts[] = array(
	'post_type'    => 'portfolio',
	'name'         => 'Portfolio',
	'plural'       => 'Portfolio cases',
	'singular'     => 'Portfolio case',
	'hierarchical' => false,
	'taxonomies'   => array(
		'cat',
		'tag',
		array(
			'taxonomy' => 'collections',
			'name'     => 'Collecties',
			'plural'   => 'Collecties',
			'singular' => 'Collectie'
		)
	)
);*/


// Clients
/*$cpts[] = array(
	'post_type'    => 'clients',
	'name'         => 'Cliënten',
	'plural'       => 'Cliënten',
	'singular'     => 'Cliënt',
	'hierarchical' => false,
	'taxonomies'   => array('cat', 'tag')
);*/


// Products
/*$cpts[] = array(
	'post_type'    => 'products',
	'name'         => 'Producten',
	'plural'       => 'Producten',
	'singular'     => 'Product',
	'hierarchical' => false,
	'taxonomies'   => array('cat', 'tag')
);*/


?>