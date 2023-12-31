<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Atento
 */

$header_layout  = get_theme_mod( 'atento_header_layout', 4 );
$header_class   = array( 'site-header' );
$header_class[] = 'header-layout-'. $header_layout; ?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page" class="site">

    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'atento' ); ?></a>

    <header id="masthead" class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">

        <?php
        /**
         * Hook - atento_action_header
         *
         * @hooked: atento_add_header    - 20
         */
        do_action( 'atento_action_header' ); ?>

    </header><!-- #masthead -->

    <div class="site-header-separator"></div>

<div id="content" class="site-content">

<?php
/**
 * Hook - atento_action_after_header
 *
 * @hooked: atento_add_after_header_hero - 10
 * @hooked: atento_add_after_header_custom_header - 20
 */
do_action( 'atento_action_after_header' );
