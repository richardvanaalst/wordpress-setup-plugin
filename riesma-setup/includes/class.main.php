<?php
/**
 * Riesma Main
 *
 * Main functions
 */

if( ! defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'Riesma' ) ) :


class Riesma {


	public static function init() {
		self::includes();
		$RiesmaSetup = new RiesmaSetup();
	}


	private static function includes() {
		include( 'class.setup.php' );
		include( 'class.posttype.php' );
	}


	/**
	 * Title: first letter capitalised
	 *
	 * @param    string   $string
	 * @return   string
	 */
	public static function titleify( $string ) {
		return apply_filters( 'riesma_titleify', ucfirst( strtolower( $string ) ) );
	}


	/**
	 * Text: all lowercase
	 *
	 * @param    string   $string
	 * @return   string
	 */
	public static function textify( $string ) {
		return apply_filters( 'riesma_textify', strtolower( $string ) );
	}


	/**
	 * Simple make plural
	 *
	 * @param    string   $string
	 * @return   string
	 */
	public static function pluralify( $string ) {
		return apply_filters( 'riesma_pluralify', $string . 's' );
	}


	/**
	 * Simple make singular    --   Todo: pop s
	 *
	 * @param    string   $string
	 * @return   string
	 */
	// public static function singularify( $string ) {
		// return apply_filters( 'riesma_singularify', $string );
	// }


	/**
	 * Create clean slug
	 *
	 * @param    string   $string
	 * @return   string
	 */
	public static function slugify( $string ) {
		return apply_filters( 'riesma_slugify', sanitize_title( $string ) );
	}


	/**
	 * Check if icon file exists, else return default icon (Posts)
	 * Default path based on Bones theme
	 *
	 * @param    string   $string
	 * @param    string   $path
	 * @return   string
	 */
	public static function iconify( $cpt, $path = null ) {
		$path = $path ? $path : get_stylesheet_directory_uri() . '/library/img/';
		$file = $cpt . '-icon.png';
		$icon = file_exists( $path . $file ) ? $file : false;
		return $icon;
	}


} // class


endif; // if (!class_exists)


?>