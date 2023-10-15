<?php
/**
 * kokoro Theme Customizer.
 *
 * @package kokoro
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function kokoro_customize_register( $wp_customize ) {

	require_once get_template_directory().'/inc/customizer-controls.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_panel( 'theme_options' ,
        array(
            'title'       => esc_html__( 'Theme Options', 'kokoro' ),
            'description' => ''
        )
    );

    // Sidebar settings
    $wp_customize->add_section( 'kokoro_home_sidebar' ,
        array(
            'title'       => esc_html__( 'Sidebar', 'kokoro' ),
            'description' => '',
            'panel'       => 'theme_options',
            'piority'     => 2
        )
    );

    $wp_customize->add_setting( 'kokoro_home_sidebar', array(
        'sanitize_callback' => 'kokoro_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'kokoro_home_sidebar',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Home Page, Archive Page', 'kokoro' ),
                'section'    => 'kokoro_home_sidebar',
            )
    );

    $wp_customize->add_setting( 'kokoro_sidebar_post', array(
        'sanitize_callback' => 'kokoro_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'kokoro_sidebar_post',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Single Post', 'kokoro' ),
                'section'    => 'kokoro_home_sidebar',
            )
    );

    $wp_customize->add_setting( 'kokoro_sidebar_page', array(
        'sanitize_callback' => 'kokoro_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'kokoro_sidebar_page',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Single Page', 'kokoro' ),
                'section'    => 'kokoro_home_sidebar',
            )
    );

    // Social Media Settings
    $wp_customize->add_section( 'kokoro_social' ,
        array(
            'title'      => esc_html__('Social Media Settings', 'kokoro'),
            'description'=> esc_html__('Enter your social media(URL). Icons will not show if left blank.', 'kokoro'),
            'priority'   => 4,
            'panel'       => 'theme_options',
        ) 
    );

        $wp_customize->add_setting(
            'kokoro_facebook',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_twitter',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_instagram',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_pinterest',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_tumblr',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_bloglovin',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_google',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_youtube',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_soundcloud',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_vimeo',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_linkedin',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_setting(
            'kokoro_rss',
            array(
                'default'     => '',
                'sanitize_callback' => 'esc_url_raw'
            )
        );


    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'kokoro_facebook',
            array(
                'label'      => esc_html__('Facebook', 'kokoro'),
                'section'    => 'kokoro_social',
                'settings'   => 'kokoro_facebook',
                'type'       => 'text',
                'priority'   => 1
            )
        )
    );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_twitter',
                array(
                    'label'      => esc_html__('Twitter', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_twitter',
                    'type'       => 'text',
                    'priority'   => 2
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_instagram',
                array(
                    'label'      => esc_html__('Instagram', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_instagram',
                    'type'       => 'text',
                    'priority'   => 3
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_pinterest',
                array(
                    'label'      => esc_html__('Pinterest', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_pinterest',
                    'type'       => 'text',
                    'priority'   => 4
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_bloglovin',
                array(
                    'label'      => esc_html__('Bloglovin', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_bloglovin',
                    'type'       => 'text',
                    'priority'   => 5
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_google',
                array(
                    'label'      => esc_html__('Google Plus', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_google',
                    'type'       => 'text',
                    'priority'   => 6
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_tumblr',
                array(
                    'label'      => esc_html__('Tumblr', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_tumblr',
                    'type'       => 'text',
                    'priority'   => 7
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_youtube',
                array(
                    'label'      => esc_html__('Youtube', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_youtube',
                    'type'       => 'text',
                    'priority'   => 8
                )
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_soundcloud',
                array(
                    'label'      => esc_html__('Soundcloud', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_soundcloud',
                    'type'       => 'text',
                    'priority'   => 9
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_vimeo',
                array(
                    'label'      => esc_html__('Vimeo', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_vimeo',
                    'type'       => 'text',
                    'priority'   => 10
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_linkedin',
                array(
                    'label'      => esc_html__('Linkedin', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_linkedin',
                    'type'       => 'text',
                    'priority'   => 11
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'kokoro_rss',
                array(
                    'label'      => esc_html__('Rss', 'kokoro'),
                    'section'    => 'kokoro_social',
                    'settings'   => 'kokoro_rss',
                    'type'       => 'text',
                    'priority'   => 12
                )
            )
        );

    // Color settings
    $wp_customize->add_section( 'kokoro_color_general' ,
        array(
            'title'       => esc_html__( 'Color Accent', 'kokoro' ),
            'description' => '',
            'panel'       => 'theme_options',
            'piority'     => 2
        )
    );

    $wp_customize->add_setting( 'kokoro_color_accent', array(
        'default'              => '#be9c62',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'kokoro_color_accent',
                array(
                    'label'      => esc_html__( 'Primary Color', 'kokoro' ),
                    'section'    => 'kokoro_color_general',
                )
        )
    );

    // Kokoro Pro
	$wp_customize->add_section( 'kokoro_pro' ,
	    array(
	        'title'       => esc_html__( 'Upgrade to Kokoro Pro', 'kokoro' ),
	        'description' => '',
	        //'panel'       => 'theme_options',
	        'piority'     => 5
	    )
	);

	$wp_customize->add_setting( 'kokoro_features', array(
            'sanitize_callback' => 'sanitize_text_field',
        ) );
	$wp_customize->add_control(
            new kokoro_Customize_Pro_Control(
                $wp_customize,
                'kokoro_features',
                array(
                    'label'      => esc_html__( 'Kokoro Features', 'kokoro' ),
                    'description'   => sprintf( __('<span>1-CLICK DEMO IMPORT</span><span>900+ GOOGLE FONTS</span><span>WooCommerce Compatible</span><span>Featured slider</span><span>6 Different Blog Layouts</span><span>Fully Responsive</span><span>100+ Customizable coloring options</span><span>4 Custom Widgets</span><span>Posts/Page Settings</span><span>Footer Copyright Text</span><span>Footer Menu</span><span>Lifetime Upgrades</span><span>LifeTime Support</span><span>Mailchimp Support</span><span>Well Documented</span><span>Child Theme included</span><span>And More...</span>','kokoro')),
                    'section'    => 'kokoro_pro',
                )
            )
	);
	$wp_customize->add_setting( 'kokoro_pro_links', array(
            'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control(
		new kokoro_Customize_Pro_Control(
			$wp_customize,
			'kokoro_pro_links',
			array(
				'description'   => sprintf( __('<a target="_blank" class="kokoro-buy-button" href="https://zthemes.net/themes/kokoro">Buy Now</a>', 'kokoro')),
				'section'    => 'kokoro_pro',
			)
        )
	);

}
add_action( 'customize_register', 'kokoro_customize_register' );

function kokoro_sanitize_checkbox( $input ){
    if ( $input == 1 || $input == 'true' || $input === true ) {
        return 1;
    } else {
        return 0;
    }
}

function kokoro_sanitize_number( $number, $setting ) {
    $number = absint( $number );
    return ( $number ? $number : $setting->default );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function kokoro_customize_preview_js() {
	wp_enqueue_script( 'kokoro_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'kokoro_customize_preview_js' );

/**
 * Load customizer style
 */
function kokoro_customizer_load_css(){
    wp_enqueue_style( 'kokoro-customizer', get_template_directory_uri() . '/css/customizer.css' );
}
add_action('customize_controls_print_styles', 'kokoro_customizer_load_css');
