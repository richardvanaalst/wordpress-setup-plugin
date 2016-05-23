<?php
/**
 * Riesma Custom Post Types List
 *
 * Add custom post type, including (custom) taxonomies,
 * by adding to the cpts array.
 * See (dutch) usage examples below.
 *
 * @param   array          $cpts
 * @param   string         post_type
 * @param   string|array   labels
 * @param   string         - name
 * @param   string         - plural
 * @param   string         - singular
 * @param   bool           hierarchical   false = post, true = page
 * @param   array          taxonomies
 * @param   string|array   - taxonomy
 * @param   string         - name
 * @param   string         - plural
 * @param   string         - singular
 *
 * $taxonomies has some optional predefined options:
 * cat      categories
 * tag      tags
 * WP_cat   WordPress default categories
 * WP_tag   WordPress default tags
 */

if ( ! defined( 'ABSPATH' ) ) exit;


$cpts = array();


// Products
$cpts[] = array(
	'post_type'    => 'products',
	'labels'       => array(
		'name'         => 'Products',
		'plural'       => 'Products',
		'singular'     => 'Product'
	),
	'taxonomies'   => array(
		array(
			'taxonomy' => 'collections',
			'name'     => 'Collections',
			'plural'   => 'Collections',
			'singular' => 'Collection'
		)
	),
	'supports'     => array(
		'title',
		// 'editor',
		'author',
		'thumbnail',
		'custom-fields',
		'revisions'
	)

);


// Stores
$cpts[] = array(
	'post_type'       => 'stores',
	'labels'          => array(
		'name'            => 'Stores',
		'plural'          => 'Stores',
		'singular'        => 'Store'
	),
	'taxonomies'      => array(
		array(
			'taxonomy'    => 'types',
			'name'        => 'Store types',
			'plural'      => 'Store types',
			'singular'    => 'Store type'
		)
	),
	'supports'        => array(
		'title',
		'editor',
		'author',
		'custom-fields',
		'revisions'
	)
);


?>