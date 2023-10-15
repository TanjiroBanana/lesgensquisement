<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package saralite
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function saralite_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'saralite_body_classes' );

/**
 * Custom excerpt more
 */
function saralite_custom_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}
	return ' &hellip; ';
}
add_filter( 'excerpt_more', 'saralite_custom_excerpt_more' );

/**
 * Get Premium url
 */
function saralite_get_premium_url(){
    return 'https://zthemes.net/themes/sara';
}