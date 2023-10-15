<?php
/**
 * saralite Theme Customizer.
 *
 * @package saralite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function saralite_customize_register( $wp_customize ) {

	require_once get_template_directory().'/inc/customizer-controls.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_panel( 'theme_options' ,
        array(
            'title'       => esc_html__( 'Theme Options', 'saralite' ),
            'description' => ''
        )
    );

    // Sidebar settings
    $wp_customize->add_section( 'saralite_home_sidebar' ,
        array(
            'title'       => esc_html__( 'Sidebar', 'saralite' ),
            'description' => '',
            'panel'       => 'theme_options',
            'piority'     => 2
        )
    );

    $wp_customize->add_setting( 'saralite_home_sidebar', array(
        'sanitize_callback' => 'saralite_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'saralite_home_sidebar',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Home Page, Archive Page', 'saralite' ),
                'section'    => 'saralite_home_sidebar',
            )
    );

    $wp_customize->add_setting( 'saralite_sidebar_post', array(
        'sanitize_callback' => 'saralite_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'saralite_sidebar_post',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Single Post', 'saralite' ),
                'section'    => 'saralite_home_sidebar',
            )
    );

    $wp_customize->add_setting( 'saralite_sidebar_page', array(
        'sanitize_callback' => 'saralite_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'saralite_sidebar_page',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Single Page', 'saralite' ),
                'section'    => 'saralite_home_sidebar',
            )
    );

    // Social Media Settings
    $wp_customize->add_section( 'saralite_social' ,
        array(
            'title'      => esc_html__('Social Media Settings', 'saralite'),
            'description'=> esc_html__('Enter your social media(URL). Icons will not show if left blank.', 'saralite'),
            'priority'   => 4,
            'panel'       => 'theme_options',
        ) 
    );

        $wp_customize->add_setting(
            'saralite_facebook',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_twitter',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_instagram',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_pinterest',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_tumblr',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_bloglovin',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_google',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_youtube',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_soundcloud',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_vimeo',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_linkedin',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'saralite_rss',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );


    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'saralite_facebook',
            array(
                'label'      => esc_html__('Facebook', 'saralite'),
                'section'    => 'saralite_social',
                'settings'   => 'saralite_facebook',
                'type'       => 'text',
                'priority'   => 1
            )
        )
    );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_twitter',
                array(
                    'label'      => esc_html__('Twitter', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_twitter',
                    'type'       => 'text',
                    'priority'   => 2
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_instagram',
                array(
                    'label'      => esc_html__('Instagram', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_instagram',
                    'type'       => 'text',
                    'priority'   => 3
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_pinterest',
                array(
                    'label'      => esc_html__('Pinterest', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_pinterest',
                    'type'       => 'text',
                    'priority'   => 4
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_bloglovin',
                array(
                    'label'      => esc_html__('Bloglovin', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_bloglovin',
                    'type'       => 'text',
                    'priority'   => 5
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_google',
                array(
                    'label'      => esc_html__('Google Plus', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_google',
                    'type'       => 'text',
                    'priority'   => 6
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_tumblr',
                array(
                    'label'      => esc_html__('Tumblr', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_tumblr',
                    'type'       => 'text',
                    'priority'   => 7
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_youtube',
                array(
                    'label'      => esc_html__('Youtube', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_youtube',
                    'type'       => 'text',
                    'priority'   => 8
                )
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_soundcloud',
                array(
                    'label'      => esc_html__('Soundcloud', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_soundcloud',
                    'type'       => 'text',
                    'priority'   => 9
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_vimeo',
                array(
                    'label'      => esc_html__('Vimeo', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_vimeo',
                    'type'       => 'text',
                    'priority'   => 10
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_linkedin',
                array(
                    'label'      => esc_html__('Linkedin', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_linkedin',
                    'type'       => 'text',
                    'priority'   => 11
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'saralite_rss',
                array(
                    'label'      => esc_html__('Rss', 'saralite'),
                    'section'    => 'saralite_social',
                    'settings'   => 'saralite_rss',
                    'type'       => 'text',
                    'priority'   => 12
                )
            )
        );

// View Sara Pro

    $wp_customize->add_section( 'sara_pro', array(
        'title' => esc_html__( 'View PRO Version', 'saralite' ),
        'priority'     => 300,
    ) );

    $wp_customize->add_setting( 'sara_pro' , array(
        'sanitize_callback' => 'sanitize_text_field',
        'default'     => '',
    ) );

    $wp_customize->add_control( new SaraLite_Message_Control( $wp_customize, 'sara_pro', array(
        'label'        => '',
        'description'  => '',
        'section'      => 'sara_pro',
        'priority'     => 190,
        'type'         => 'list',
        'list'         => array(
            esc_html__( 'Clean & bold design', 'saralite' ),
            esc_html__( '4 Different Blog Layouts', 'saralite' ),
            esc_html__( 'WooCommerce Compatible', 'saralite' ),
            esc_html__( 'Featured Posts- Beautiful slider', 'saralite' ),
            esc_html__( 'Share Blog Posts', 'saralite' ),
            esc_html__( '3 Custom Widgets', 'saralite' ),
            esc_html__( 'Footer Copyright Text', 'saralite' ),
            esc_html__( 'Logo Upload', 'saralite' ),
            esc_html__( 'Well Documented', 'saralite' ),
            esc_html__( 'Child Theme included', 'saralite' ),
            esc_html__( 'And More...', 'saralite' ),
        ),
        'button' => array(
            'link' => saralite_get_premium_url(),
            'label' => esc_html__( 'Upgrade to Sara Pro', 'saralite' ),
        )
    ) ) );

}

add_action( 'customize_register', 'saralite_customize_register' );

function saralite_sanitize_checkbox( $input ){
    if ( $input == 1 || $input == 'true' || $input === true ) {
        return 1;
    } else {
        return 0;
    }
}

function saralite_sanitize_number( $number, $setting ) {
    $number = absint( $number );
    return ( $number ? $number : $setting->default );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function saralite_customize_preview_js() {
	wp_enqueue_script( 'saralite_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'saralite_customize_preview_js' );

/**
 * Load customizer style
 */
function saralite_customizer_load_css(){
    wp_enqueue_style( 'saralite-customizer', get_template_directory_uri() . '/css/customizer.css' );
}
add_action('customize_controls_print_styles', 'saralite_customizer_load_css');
