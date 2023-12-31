<?php
if (!function_exists('cuar_is_active_body_class'))
{
    /**
     * Add a body class if WPCA is active to get better CSS priority on our area
     *
     * @param $classes
     *
     * @return array
     */
    function cuar_is_active_body_class($classes)
    {
        $classes[] = 'customer-area-active';

        return $classes;
    }

    add_action('body_class', 'cuar_is_active_body_class');
}

if (!function_exists('cuar_load_skin_scripts'))
{

    /** Always load our scripts */
    function cuar_load_skin_scripts()
    {

        $cuar_plugin = cuar();

        $cuar_assets = [

            // BOOTSTRAP
            // --
            'bootstrap.affix',
            'bootstrap.alert',
            'bootstrap.button',
            //'bootstrap.carousel',
            'bootstrap.collapse',
            'bootstrap.dropdown',
            'bootstrap.modal',
            //'bootstrap.popover',
            'bootstrap.scrollspy',
            'bootstrap.tab',
            'bootstrap.tooltip',
            'bootstrap.transition',

            // CUAR PAGES AND SINGLE POSTS SCRIPTS
            [
                'on' => cuar_is_customer_area_page(get_queried_object()) || cuar_is_customer_area_private_content(get_the_ID()),
                'scripts' => [
                    'summernote',
                ],
            ],

            // CUAR PAGES SCRIPTS
            // --
            [
                'on' => cuar_is_customer_area_page(get_queried_object()),
                'scripts' => [
                    'jquery.cookie',
                    'jquery.mixitup',
                    'jquery.steps',
                ],
            ],

            // CUAR SINGLE POSTS SCRIPTS
            // --
            [
                'on' => cuar_is_customer_area_private_content(get_the_ID()),
                'scripts' => [
                    'jquery.slick',
                ],
            ],

        ];

        // BUILD DEPENDENCIES ARRAY AND ENQUEUE THEM
        // --
        $dependencies = ['jquery'];
        foreach ($cuar_assets as $cuar_asset)
        {
            if (!is_array($cuar_asset))
            {
                $cuar_plugin->enable_library($cuar_asset);
                $dependencies[] = $cuar_asset;
            }
            else
            {
                if ($cuar_asset['on'])
                {
                    foreach ($cuar_asset['scripts'] as $script)
                    {
                        $cuar_plugin->enable_library($script);
                        $dependencies[] = $script;
                    }
                }
            }
        }
        $dependencies[] = 'cuar.frontend';

        // CUSTOM SCRIPTS
        // --
        wp_register_script('customer-area-master-skin',
            CUAR_PLUGIN_URL . 'skins/frontend/master/assets/js/main.min.js',
            $dependencies,
            $cuar_plugin->get_version(),
            true);
        wp_enqueue_script('customer-area-master-skin');

        // CUSTOM FONTS
        // --
        wp_register_style('customer-area-master-fontawesome',
            CUAR_PLUGIN_URL . 'skins/frontend/master/assets/css/fonts.min.css',
            [],
            $cuar_plugin->get_version());
        wp_enqueue_style('customer-area-master-fontawesome');
    }

    /** Only load our scripts when necessary */
    function cuar_load_skin_scripts_conditional()
    {
        if (cuar_is_customer_area_page(get_queried_object()) || cuar_is_customer_area_private_content(get_the_ID()))
        {
            cuar_load_skin_scripts();
        }
    }

    add_action('wp_enqueue_scripts', 'cuar_load_skin_scripts_conditional');
    add_action('cuar/core/shortcode/before-process', 'cuar_load_skin_scripts');
}

if (!function_exists('cuar_custom_sidebar_attributes'))
{
    /**
     * Example function that you can override in your theme functions to customize
     * the behaviors of the tray sidebars
     * By default, parameters are configured to make the area height fit the window viewport
     * You can customize it to fit your need, or simply remove the data-tray-height-base
     * and data-tray-height-substract that are used to calculate the height of the main content
     * and the sidebar.
     * This function is actually just an example, so the modifications have been commented out
     * to leave the default behaviors
     *
     * @param $args array
     *
     * @return mixed
     * @see customer-area/src/php/core-classes/templates/customer-page.template.php
     */
    function cuar_custom_sidebar_attributes($args)
    {
        //$args['data-tray-height-base'] = '';
        //$args['data-tray-height-substract'] = '';
        //$args['data-tray-height-minimum'] = 350;

        return $args;
    }

    add_action('cuar/core/page/sidebar-attributes', 'cuar_custom_sidebar_attributes');
}

if (!function_exists('cuar_images_sizes'))
{
    /**
     * Generate images sizes for wpca
     */
    function cuar_images_sizes()
    {
        add_theme_support('post-thumbnails');
        add_image_size('wpca-thumb', 220, 150, true);
    }

    add_action('after_setup_theme', 'cuar_images_sizes');
}

if (!function_exists('cuar_enable_bootstrap_nav_walker'))
{

    /**
     * Use the bootstrap navwalker for our navigation menu to output bootstrap-friendly HTML.
     *
     * @param $args
     *
     * @return mixed
     */
    function cuar_enable_bootstrap_nav_walker($args)
    {
        if (!isset($args['theme_location']) || $args['theme_location'] !== 'cuar_main_menu') {
            return $args;
        }

        require_once(CUAR_PLUGIN_DIR . '/src/php/helpers/bootstrap-nav-walker.class.php');
        $new_args = $args;

        $new_args['depth'] = 2;
        $new_args['container'] = 'div';
        $new_args['container_class'] = 'nav-container collapse navbar-collapse';
        $new_args['menu_class'] = 'nav navbar-nav';
        $new_args['fallback_cb'] = 'CUAR_BootstrapNavWalker::fallback';
        $new_args['walker'] = new CUAR_BootstrapNavWalker();

        return $new_args;
    }

    add_filter('cuar/core/page/nav-menu-args', 'cuar_enable_bootstrap_nav_walker');
}

if (!function_exists('cuar_custom_editor_styles'))
{
    /**
     * Load custom styles for TinyMCE
     *
     * @param $mce_css
     *
     * @return string
     */
    function cuar_custom_editor_styles($mce_css)
    {
        if (is_admin())
        {
            return $mce_css;
        }

        if (cuar_is_customer_area_page(get_queried_object()) || cuar_is_customer_area_private_content(get_the_ID()))
        {
            $mce_css = ', ' . plugins_url('assets/css/styles.min.css', __FILE__);
        }

        return $mce_css;
    }

    add_filter('mce_css', 'cuar_custom_editor_styles');
}

if (!function_exists('cuar_custom_excerpt_length'))
{
    /**
     * Custom excerpt length
     *
     * @param $length
     *
     * @return int
     */
    function cuar_custom_excerpt_length($length)
    {
        if (cuar_is_customer_area_page(get_queried_object()) || cuar_is_customer_area_private_content(get_the_ID()))
        {
            return 30;
        }
        else
        {
            return $length;
        }
    }

    add_filter('cuar_excerpt_length', 'cuar_custom_excerpt_length', 999);
}

if (!function_exists('cuar_custom_excerpt_more'))
{
    /**
     * Remove more link to excerpt
     *
     * @param $more
     *
     * @return string
     */
    function cuar_custom_excerpt_more($more)
    {
        if (cuar_is_customer_area_page(get_queried_object()) || cuar_is_customer_area_private_content(get_the_ID()))
        {
            return '';
        }
        else
        {
            return $more;
        }
    }

    add_filter('cuar_excerpt_more', 'cuar_custom_excerpt_more');
}

if (!function_exists('cuar_trim_excerpt'))
{
    /**
     * Generates an excerpt from the content, if needed.
     *
     * Returns a maximum of 55 words with an ellipsis appended if necessary.
     *
     * The 55 word limit can be modified by plugins/themes using the {@see 'excerpt_length'} filter
     * The ' [&hellip;]' string can be modified by plugins/themes using the {@see 'excerpt_more'} filter
     *
     * @param string             $text Optional. The excerpt. If set to empty, an excerpt is generated.
     * @param WP_Post|object|int $post Optional. WP_Post instance or Post ID/object. Default is null.
     * @return string The excerpt.
     * @since 5.2.0 Added the `$post` parameter.
     *
     * @since 1.5.0
     */
    function cuar_trim_excerpt($text = '', $post = null)
    {
        $raw_excerpt = $text;
        if ('' == $text)
        {
            $post = get_post($post);
            $text = get_the_content('', false, $post);

            $text = strip_shortcodes($text);
            $text = excerpt_remove_blocks($text);

            /** This filter is documented in wp-includes/post-template.php */
            if (!(cuar_is_customer_area_page(get_queried_object()) || cuar_is_customer_area_private_content(get_the_ID())))
            {
                $text = apply_filters('the_content', $text);
            }
            $text = str_replace(']]>', ']]&gt;', $text);

            /* translators: Maximum number of words used in a post excerpt. */
            $excerpt_length = intval(_x('55', 'excerpt_length'));

            /**
             * Filters the maximum number of words in a post excerpt.
             *
             * @param int $number The maximum number of words. Default 55.
             * @since 2.7.0
             *
             */
            $excerpt_length = (int)apply_filters('excerpt_length', $excerpt_length);

            /**
             * Filters the string in the "more" link displayed after a trimmed excerpt.
             *
             * @param string $more_string The string shown within the more link.
             * @since 2.9.0
             *
             */
            $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
            $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
        }

        /**
         * Filters the trimmed excerpt string.
         *
         * @param string $text        The trimmed text.
         * @param string $raw_excerpt The text prior to trimming.
         * @since 2.8.0
         *
         */
        return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
    }
}

if (!function_exists('cuar_remove_auto_excerpt'))
{
    /**
     * Prevent the excerpt to be generated from the_content
     */
    function cuar_remove_auto_excerpt()
    {
        if (cuar_is_customer_area_page(get_queried_object_id()) || cuar_is_customer_area_private_content(get_the_ID()))
        {
            remove_filter('get_the_excerpt', 'wp_trim_excerpt');
            add_filter('get_the_excerpt', 'cuar_trim_excerpt', 30, 2);
        }
    }

    add_action('loop_start', 'cuar_remove_auto_excerpt');
}

if (!function_exists('cuar_acf_field_group_class'))
{
    /**
     * Customize field groups on frontend
     */
    function cuar_acf_field_group_class($options, $id)
    {
        if (!is_admin())
        {
            $options["layout"] = 'panel';
        }

        return $options;
    }

    add_filter('acf/field_group/get_options', 'cuar_acf_field_group_class', 10, 2);
}

if (!function_exists('cuar_toolbar_profile_button'))
{
    /**
     * Add a profile menu button to the toolbar
     *
     * @param $groups
     *
     * @return mixed
     */
    function cuar_toolbar_profile_button($groups)
    {
        $out = '';
        $current_user = wp_get_current_user();
        $current_avatar = get_avatar($current_user->user_email, 17);

        if (!$current_avatar) {
            $current_avatar = sprintf('%s&nbsp;<span class="caret ml5"></span>', $current_user->display_name);
        }

        $out .= '<div class="cuar-menu-avatar-icon btn-group">';
        $out .= '<button type="button" class="btn btn-default dropdown-toggle mn" data-toggle="dropdown" aria-expanded="false">';
        $out .= $current_avatar;
        $out .= '</button>';
        $out .= '<ul class="dropdown-menu animated animated-shorter fadeIn" role="menu" style="margin-top: 1px;">';

        if (is_user_logged_in()) {
            $addon_account = cuar_addon('customer-account');
            $addon_account_edit = cuar_addon('customer-account-edit');
            $addon_logout = cuar_addon('customer-logout');

            $out .= '<li class="dropdown-header">'
                    . sprintf(__('Hello, %1$s', 'cuar'), $current_user->display_name)
                    . '</li>';

            if (current_user_can('cuar_view_account')) {
                $out .= '<li><a href="' . $addon_account->get_page_url() . '">' . __('View profile', 'cuar') . '</a></li>';
            }
            if (current_user_can('cuar_edit_account')) {
                $out .= '<li><a href="' . $addon_account_edit->get_page_url() . '">' . __('Manage account', 'cuar') . '</a></li>';
            }
            $out .= '<li><a href="' . $addon_logout->get_page_url() . '">' . __('Logout', 'cuar') . '</a></li>';

        } else {
            $addon_login = cuar_addon('customer-login');
            $addon_register = cuar_addon('customer-register');

            $out .= '<li><a href="' . $addon_register->get_page_url() . '">' . __('Register', 'cuar') . '</a></li>';
            $out .= '<li><a href="' . $addon_login->get_page_url() . '">' . __('Login', 'cuar') . '</a></li>';
        }

        $out .= '</ul>';
        $out .= '</div>';

        $groups['welcome'] = [
            'type' => 'raw',
            'html' => $out,
        ];

        return $groups;
    }

    add_filter('cuar/core/page/toolbar', 'cuar_toolbar_profile_button', 10);
}

if (!function_exists('cuar_dev_nuancier_print_html'))
{
    /**
     * Nuancier colors for development purposes
     */
    function cuar_dev_nuancier_print_html()
    {
        $current_skin_path = cuar()->get_theme_path('frontend');

        $file = $current_skin_path . '/src/less/less-vars.css';

        if ($_SERVER['HTTP_HOST'] == 'local.wordpress.test' && file_exists($file))
        {
            $file_txt = file_get_contents($file);
            $file_regex = '/(.cuar-dev-nuance-)([^\'\s\{]*)/';

            echo '<div id="cuar-dev-nuancier"><input type="checkbox" name="cuar-dev-nuancier-toggle" id="cuar-dev-nuancier-toggle"><label for="cuar-dev-nuancier-toggle"></label><div class="cuar-dev-nuancier"><div class="cuar-dev-nuancier-wrapper">'
                 . "\n";

            if (preg_match_all($file_regex, $file_txt, $file_match))
            {
                foreach ($file_match[2] as $class)
                {
                    echo '<div class="cuar-dev-nuance cuar-dev-nuance-' . $class . '"></div>' . "\n";
                }
            }

            echo '</div></div></div>' . "\n";
        }
    }
}

if (!function_exists('cuar_dev_nuancier_print_styles'))
{
    /**
     * Load nuancier styles
     */
    function cuar_dev_nuancier_print_styles()
    {
        $current_skin_path = cuar()->get_theme_path('frontend');
        $current_skin_url = cuar()->get_theme_url('frontend');

        $css = $current_skin_path . '/assets/css/less-vars.min.css';

        if ($_SERVER['HTTP_HOST'] == 'local.wordpress.test' && file_exists($css))
        {
            wp_register_style('customer-area-master-dev-nuancier', $current_skin_url . '/assets/css/less-vars.min.css');
            wp_enqueue_style('customer-area-master-dev-nuancier');

            add_action('wp_footer', 'cuar_dev_nuancier_print_html');
        }
    }

    add_action('wp_enqueue_scripts', 'cuar_dev_nuancier_print_styles');
}

if (!function_exists('cuar_default_collection_views'))
{
    /**
     * Customize field groups on frontend
     */
    function cuar_default_collection_views($data)
    {
        $data['default_collection_view'] = [];

        $private_types = cuar()->get_private_post_types();
        foreach ($private_types as $type)
        {
            $data['default_collection_view'][$type] = 'grid';
        }

        return $data;
    }

    add_filter('cuar/core/js-messages?zone=frontend', 'cuar_default_collection_views', 10, 1);
}
