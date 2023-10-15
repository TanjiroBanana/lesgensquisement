<?php
/**
 * saralite functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package saralite
 */

if ( ! function_exists( 'saralite_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function saralite_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on saralite, use a find and replace
	 * to change 'saralite' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'saralite', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'saralite-full-thumb', 768, 0, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'saralite' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'saralite_custom_background_args', array(
		'default-color' => 'f3f3f3',
		'default-image' => '',
	) ) );
	// Add Logo
	add_theme_support( 'custom-logo', array(
		'height'      => 50,
		'width'       => 180,
		'flex-height' => true,
		'flex-width'  => true,
	) );
}
endif;
add_action( 'after_setup_theme', 'saralite_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function saralite_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'saralite_content_width', 640 );
}
add_action( 'after_setup_theme', 'saralite_content_width', 0 );

/**
 *
 * Add Custom editor styles
 *
 */
function saralite_add_editor_styles() {
    add_editor_style( 'css/custom-editor-style.css' );
}
add_action( 'admin_init', 'saralite_add_editor_styles' );

/**
 *
 * Load Google Fonts
 *
 */
function saralite_google_fonts_url(){
	
    $fonts_url  = '';
    $Oswald = _x( 'on', 'Oswald font: on or off', 'saralite' );
    $Inconsolata      = _x( 'on', 'Inconsolata font: on or off', 'saralite' );
 
    if ( 'off' !== $Oswald || 'off' !== $Inconsolata ){

        $font_families = array();
 
        if ( 'off' !== $Oswald ) {

            $font_families[] = 'Oswald:300,400,500,700';

        }
 
        if ( 'off' !== $Inconsolata ) {

            $font_families[] = 'Inconsolata:400,700';

        }
        
 
        $query_args = array(

            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

    }
 
    return esc_url_raw( $fonts_url );
}

function saralite_enqueue_googlefonts() {

    wp_enqueue_style( 'saralite-googlefonts', saralite_google_fonts_url(), array(), null );
}

add_action( 'wp_enqueue_scripts', 'saralite_enqueue_googlefonts' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function saralite_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'saralite' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'saralite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name' => esc_html__( 'Instagram Footer','saralite' ),
		'id' => 'sidebar-2',
		'before_widget' => '<div id="%1$s" class="instagram-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="instagram-title">',
		'after_title' => '</h4>',
		'description' => esc_html__('Use the "Instagram" widget here. IMPORTANT: For best result set number of photos to 6.', 'saralite' ),
	));
}
add_action( 'widgets_init', 'saralite_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function saralite_scripts() {
	wp_enqueue_style( 'saralite-style', get_stylesheet_uri() );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');

	wp_enqueue_script( 'saralite-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'saralite-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script( 'saralite-script', get_template_directory_uri() . '/js/saralite.js', array('jquery'), false, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	if ( function_exists( 'saralite_get_custom_style' ) ) {
        wp_add_inline_style( 'saralite-style', saralite_get_custom_style() );
    }
}
add_action( 'wp_enqueue_scripts', 'saralite_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Add theme info page
 */
require get_template_directory() . '/inc/dashboard.php';