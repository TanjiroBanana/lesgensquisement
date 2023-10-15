<?php
// ----------------------------------------------------------------------------------
//	Register Front-End Styles And Scripts
// ----------------------------------------------------------------------------------

function kokoro_rose_enqueue_child_styles() {
 
    wp_enqueue_style( 'kokoro-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'kokoro-rose-style', get_stylesheet_directory_uri() . '/style.css', array( 'kokoro-style' ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'kokoro_rose_enqueue_child_styles' );

/**
 *
 * Load Google Fonts
 *
 */
function kokoro_rose_google_fonts_url(){
	
    $fonts_url  = '';
    $JosefinSans = _x( 'on', 'Josefin Sans font: on or off', 'kokoro-rose' );
    $DancingScript      = _x( 'on', 'Dancing Script font: on or off', 'kokoro-rose' );
 
    if ( 'off' !== $JosefinSans || 'off' !== $DancingScript ){

        $font_families = array();
 
        if ( 'off' !== $JosefinSans ) {

            $font_families[] = 'Josefin Sans:400,600,700';

        }
 
        if ( 'off' !== $DancingScript ) {

            $font_families[] = 'Dancing Script:400,700';

        }
        
 
        $query_args = array(

            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

    }
 
    return esc_url_raw( $fonts_url );
}

function kokoro_rose_enqueue_googlefonts() {

    wp_enqueue_style( 'kokoro-rose-googlefonts', kokoro_rose_google_fonts_url(), array(), null );
}

add_action( 'wp_enqueue_scripts', 'kokoro_rose_enqueue_googlefonts' );